<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\Template;
use App\Models\Document;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\ProposalSubmittedMail;
use App\Mail\BudgetExceededNotification;
use App\Models\ProjectCategory;

class ProposalController extends Controller
{
    // Show all proposals (with filters, sorting, pagination)
    public function index()
    {
        $query = Proposal::query();

        // Restrict to own proposals if not admin
        if (!auth()->user()->is_admin) {
            $query->where('user_id', auth()->id());
        }

        // Apply filters
        $query->when(request('search'), function ($q) {
            $q->where('title', 'like', '%' . request('search') . '%')
                ->orWhere('organization_name', 'like', '%' . request('search') . '%')
                ->orWhere('email', 'like', '%' . request('search') . '%');
        });

        $query->when(request('status'), function ($q) {
            $q->where('status', request('status'));
        });

        // Apply sorting
        $query->orderBy(request('sort', 'id'), request('direction', 'desc'));

        $proposals = $query->paginate(10);

        return view('proposals.index', compact('proposals'));
    }

    // Show form to create new proposal
    public function create()
    {
        $proposal = new Proposal();
        $categories = ProjectCategory::all();
        $templates = Template::where('is_active', true)->get();
        
        return view('proposals.create', compact('proposal', 'categories', 'templates'));
    }

    // Store a new proposal
    public function store(Request $request)
    {
        Log::info('Store method hit');

        // Check if this is a template form upload
        if ($request->has('is_template_upload') && $request->is_template_upload) {
            return $this->storeTemplateUpload($request);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'email' => 'required|email',
            'organization_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'summary' => 'required|string',
            'background' => 'required|string',
            'activities' => 'required|string',
            'budget' => 'required|numeric|min:0',
            'proposal_goals' => 'required|string|max:255',
            'duration' => 'required|string',
            'organization_type' => 'required|string',
            'document' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'project_category_id' => 'required|exists:project_categories,id',
        ]);

        // Handle document upload
        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('documents', 'public');
            
            // Create document record
            $document = new Document();
            $document->name = $request->file('document')->getClientOriginalName();
            $document->file_path = $documentPath;
            $document->file_type = $request->file('document')->getClientMimeType();
            $document->file_size = $request->file('document')->getSize();
            $document->description = "Document for proposal: " . $validated['title'];
            $document->user_id = Auth::id();
            $document->save();
            
            // Notify admins about the new document
            $this->notifyAdminsAboutDocument($document, $validated['title']);
        }

        // Find project category and budget info
        $category = ProjectCategory::findOrFail($validated['project_category_id']);

        // Calculate approved budget sum for this category
        $approvedBudgetSum = Proposal::where('project_category_id', $category->id)
            ->where('status', 'accepted')
            ->sum('budget');

        // Check if new budget fits within category budget
        if (($approvedBudgetSum + $validated['budget']) > $category->budget) {
            // Budget exceeded - auto reject
            $proposal = new Proposal();
            $proposal->fill($validated);
            $proposal->document = $documentPath;
            $proposal->status = 'rejected';
            $proposal->user_id = Auth::id();
            $proposal->submitted_by = Auth::user()->name;
            $proposal->project_category_id = $category->id;
            $proposal->save();

            // Save document relationship if exists
            if (isset($document)) {
                $document->proposal_id = $proposal->id;
                $document->save();
            }

            // Send budget exceeded notification email to user
            $proposal->load('category');
            Mail::to(Auth::user()->email)->send(new BudgetExceededNotification($proposal));
            Mail::to($proposal->email)->send(new BudgetExceededNotification($proposal));

            return redirect()->back()->with('error', 'Project automatically rejected due to budget constraints.');
        }

        // Budget available - save proposal as pending
        $proposal = new Proposal();
        $proposal->fill($validated);
        $proposal->document = $documentPath;
        $proposal->status = 'pending';
        $proposal->user_id = Auth::id();
        $proposal->submitted_by = Auth::user()->name;
        $proposal->save();
        
        // Save document relationship if exists
        if (isset($document)) {
            $document->proposal_id = $proposal->id;
            $document->save();
        }

        // Send confirmation emails to user and form email
        $userEmail = Auth::user()->email;
        $formEmail = $proposal->email;

        if ($userEmail !== $formEmail) {
            Mail::to($userEmail)->send(new ProposalSubmittedMail($proposal));
            Mail::to($formEmail)->send(new ProposalSubmittedMail($proposal));
        } else {
            Mail::to($userEmail)->send(new ProposalSubmittedMail($proposal));
        }

        return redirect()->route('proposals.index')->with('success', 'Proposal submitted successfully.');
    }
    
    /**
     * Store a proposal from a template form upload
     */
    protected function storeTemplateUpload(Request $request)
    {
        $validated = $request->validate([
            'submitted_by' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'organization_name' => 'required|string|max:255',
            'project_category_id' => 'required|exists:project_categories,id',
            'template_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);
        
        // Handle document upload
        $documentPath = null;
        if ($request->hasFile('template_file')) {
            $documentPath = $request->file('template_file')->store('documents', 'public');
            
            // Create document record
            $document = new Document();
            $document->name = $request->file('template_file')->getClientOriginalName();
            $document->file_path = $documentPath;
            $document->file_type = $request->file('template_file')->getClientMimeType();
            $document->file_size = $request->file('template_file')->getSize();
            $document->description = "Template form for proposal from " . $validated['organization_name'];
            $document->user_id = Auth::id();
            $document->save();
            
            // Notify admins about the new document
            $this->notifyAdminsAboutDocument($document, "Template form from " . $validated['organization_name']);
        }
        
        // Find project category
        $category = ProjectCategory::findOrFail($validated['project_category_id']);
        
        // Create proposal with minimal information
        $proposal = new Proposal();
        $proposal->title = 'Proposal from ' . $validated['organization_name'];
        $proposal->submitted_by = $validated['submitted_by'];
        $proposal->email = $validated['email'];
        $proposal->phone = $validated['phone'];
        $proposal->organization_name = $validated['organization_name'];
        $proposal->project_category_id = $validated['project_category_id'];
        $proposal->document = $documentPath;
        $proposal->status = 'pending';
        $proposal->user_id = Auth::id();
        
        // Set default values for required fields
        $proposal->address = 'See uploaded document';
        $proposal->summary = 'See uploaded document';
        $proposal->background = 'See uploaded document';
        $proposal->activities = 'See uploaded document';
        $proposal->budget = 0; // Will be reviewed by admin
        $proposal->proposal_goals = 'See uploaded document';
        $proposal->duration = 'See uploaded document';
        $proposal->organization_type = 'See uploaded document';
        
        $proposal->save();
        
        // Save document relationship if exists
        if (isset($document)) {
            $document->proposal_id = $proposal->id;
            $document->save();
        }
        
        // Send confirmation email
        Mail::to(Auth::user()->email)->send(new ProposalSubmittedMail($proposal));
        
        return redirect()->route('proposals.index')
            ->with('success', 'Your proposal template has been uploaded successfully and is pending review.');
    }

    public function show(string $id)
    {
        $proposal = Proposal::findOrFail($id);

        if (!auth()->user()->is_admin && $proposal->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        return view('proposals.show', compact('proposal'));
    }

    // Show edit form
    public function edit(string $id)
    {
        $proposal = Proposal::findOrFail($id);

        if (!auth()->user()->is_admin && $proposal->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $categories = ProjectCategory::all();

        return view('proposals.edit', compact('proposal', 'categories'));
    }

    // Update proposal
    public function update(Request $request, string $id)
    {
        try {
            Log::info('Update request received.', $request->all());

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'email' => 'required|email',
                'organization_name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'summary' => 'required|string',
                'background' => 'required|string',
                'activities' => 'required|string',
                'budget' => 'required|string',
                'proposal_goals' => 'required|string|max:255',
                'duration' => 'required|string',
                'organization_type' => 'required|string',
                'document' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);

            $proposal = Proposal::findOrFail($id);

            if (!auth()->user()->is_admin && $proposal->user_id !== auth()->id()) {
                abort(403, 'Unauthorized');
            }

            $documentPath = $proposal->document;

            if ($request->hasFile('document')) {
                $documentPath = $request->file('document')->store('documents', 'public');
                
                // Create document record
                $document = new Document();
                $document->name = $request->file('document')->getClientOriginalName();
                $document->file_path = $documentPath;
                $document->file_type = $request->file('document')->getClientMimeType();
                $document->file_size = $request->file('document')->getSize();
                $document->description = "Updated document for proposal: " . $validated['title'];
                $document->user_id = Auth::id();
                $document->proposal_id = $proposal->id;
                $document->save();
                
                // Notify admins about the updated document
                $this->notifyAdminsAboutDocument($document, "Updated document for " . $validated['title']);
            }

            $proposal->update(array_merge($validated, [
                'document' => $documentPath,
            ]));

            return redirect()->route('proposals.index')->with('success', '✅ Your changes have been saved. The proposal was updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating proposal: ' . $e->getMessage());
            return redirect()->back()->with('error', '❌ An error occurred while updating the proposal. Please try again.');
        }
    }

    // Delete a proposal
    public function destroy(string $id)
    {
        $proposal = Proposal::findOrFail($id);

        if (!auth()->user()->is_admin && $proposal->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $proposal->delete();

        return redirect()->route('proposals.index')->with('success', 'Proposal deleted.');
    }

    // Return proposal with related user
    public function getProposal($id)
    {
        return Proposal::with('submittedByUser')->find($id);
    }

    // Export proposals to CSV
    public function export()
    {
        $proposals = Proposal::all();
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=proposals.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($proposals) {
            $FH = fopen('php://output', 'w');
            fputcsv($FH, ['ID', 'Title', 'Email']); // Header row

            foreach ($proposals as $proposal) {
                fputcsv($FH, [$proposal->id, $proposal->title, $proposal->email]);
            }

            fclose($FH);
        };

        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Request a review for a proposal
     * Only sends a notification to admin if the project is about to start and hasn't been reviewed
     *
     * @param Proposal $proposal
     * @return \Illuminate\Http\RedirectResponse
     */
    public function requestReview(Proposal $proposal)
    {
        // Check if user owns this proposal
        if ($proposal->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
        
        // Check if proposal has already been reviewed
        if (in_array($proposal->status, ['accepted', 'rejected'])) {
            return redirect()->route('proposals.show', $proposal)
                ->with('error', 'This proposal has already been reviewed.');
        }
        
        // Check if project timeline is about to start
        $startDate = null;
        try {
            // Try to parse the duration field for a start date
            if (preg_match('/start.*?(\d{4}-\d{2}-\d{2})/i', $proposal->duration, $matches)) {
                $startDate = new \DateTime($matches[1]);
            }
        } catch (\Exception $e) {
            // If parsing fails, continue without date check
        }
        
        // If we have a start date, check if it's within 14 days
        if ($startDate) {
            $now = new \DateTime();
            $daysDiff = $now->diff($startDate)->days;
            
            if ($daysDiff > 14) {
                return redirect()->route('proposals.show', $proposal)
                    ->with('error', 'Review can only be requested when the project is about to start (within 14 days).');
            }
        }
        
        // Notify admins
        $admins = User::where('is_admin', true)->get();
        foreach ($admins as $admin) {
            Notification::create([
                'title' => 'Review Requested',
                'message' => "A review has been requested for proposal: {$proposal->title}. The project is scheduled to start soon.",
                'type' => 'warning', // Higher priority
                'read' => false,
                'user_id' => $admin->id,
                'proposal_id' => $proposal->id
            ]);
        }
        
        return redirect()->route('proposals.show', $proposal)
            ->with('success', 'Review request sent to administrators. You will be notified when the review is complete.');
    }
    
    /**
     * Submit revisions for a proposal
     *
     * @param Request $request
     * @param Proposal $proposal
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitRevisions(Request $request, Proposal $proposal)
    {
        // Check if user owns this proposal
        if ($proposal->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
        
        $validated = $request->validate([
            'content' => 'required|string',
            'revision_notes' => 'required|string',
        ]);
        
        // Update proposal
        $proposal->content = $validated['content'];
        $proposal->status = 'pending'; // Reset to pending for review
        $proposal->save();
        
        // Create revision record
        $revision = new \App\Models\ProposalVersion();
        $revision->proposal_id = $proposal->id;
        $revision->content = $validated['content'];
        $revision->notes = $validated['revision_notes'];
        $revision->created_by = auth()->id();
        $revision->save();
        
        // Notify admins
        $admins = User::where('is_admin', true)->get();
        foreach ($admins as $admin) {
            Notification::create([
                'title' => 'Proposal Revised',
                'message' => "Revisions have been submitted for proposal: {$proposal->title}\n\nNotes: {$validated['revision_notes']}",
                'type' => 'info',
                'read' => false,
                'user_id' => $admin->id,
                'proposal_id' => $proposal->id
            ]);
        }
        
        return redirect()->route('proposals.show', $proposal)
            ->with('success', 'Revisions submitted successfully.');
    }
    
    /**
     * Notify all admin users about a new document
     *
     * @param Document $document
     * @param string $proposalTitle
     * @return void
     */
    private function notifyAdminsAboutDocument($document, $proposalTitle)
    {
        try {
            // Get all admin users
            $admins = User::where('is_admin', true)->get();
            
            foreach ($admins as $admin) {
                // Create a notification record
                Notification::create([
                    'title' => 'New Document Uploaded',
                    'message' => "A new document has been uploaded for proposal: {$proposalTitle}\n\nDocument: {$document->name}\nSize: " . 
                        number_format($document->file_size / 1024, 2) . " KB\nType: {$document->file_type}",
                    'type' => 'info',
                    'read' => false,
                    'user_id' => $admin->id,
                    'proposal_id' => $document->proposal_id
                ]);
            }
            
            Log::info('Admin notifications sent for new document upload');
        } catch (\Exception $e) {
            Log::error('Failed to notify admins about document: ' . $e->getMessage());
        }
    }
}