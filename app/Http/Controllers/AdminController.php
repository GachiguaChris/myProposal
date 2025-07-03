<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\ProposalFeedback;
use App\Models\ProposalVersion;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProposalReviewed;

class AdminController extends Controller
{
    public function index()
    {
        $proposals = Proposal::with('submittedByUser')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.index', compact('proposals'));
    }
public function review(Request $request, $id)
{
    $proposal = Proposal::with(['submittedByUser', 'feedbacks.reviewer', 'versions'])->findOrFail($id);
    
    if ($request->isMethod('post')) {
        $validated = $request->validate([
            'status' => 'required|in:pending,accepted,rejected,revision_requested',
            'feedback' => 'required|string',
            'feedback_type' => 'required|in:comment,revision',
            'revision_fields' => 'nullable|array',
        ]);
        
        // Update proposal status
        $proposal->status = $validated['status'];
        $proposal->save();
      $feedback = new ProposalFeedback();
      $feedback->proposal_id = $proposal->id;
      $feedback->reviewer_id = Auth::id();
      $feedback->feedback = (string) $validated['feedback'];
      $feedback->type = in_array($validated['feedback_type'], ['comment', 'revision']) ? $validated['feedback_type'] : 'comment'; // fallback safety

// Send email to both: user and proposal contact_email
$recipients = [];

if ($proposal && $proposal->submittedByUser) {
    $recipients[] = $proposal->submittedByUser->email;
}

if ($proposal && $proposal->email && !in_array($proposal->email, $recipients)) {
    $recipients[] = $proposal->email;
}
foreach ($recipients as $recipient) {
   Mail::to($recipient)->send(new \App\Mail\ProposalReviewed($proposal, $feedback, $validated['status']));
}
        // Notify the proposal owner
        $notification = new Notification();
        $notification->user_id = $proposal->user_id;
        $notification->title = 'Proposal ' . ucfirst($validated['status']);

        if ($validated['status'] == 'revision_requested') {
            $notification->message = "Your proposal '{$proposal->title}' requires revisions. Please check the feedback and make the requested changes.";
            $notification->type = 'warning';
        } elseif ($validated['status'] == 'accepted') {
            $notification->message = "Congratulations! Your proposal '{$proposal->title}' has been accepted.";
            $notification->type = 'success';
        } elseif ($validated['status'] == 'rejected') {
            $notification->message = "Your proposal '{$proposal->title}' has been rejected. Please check the feedback for details.";
            $notification->type = 'danger';
        } else {
            $notification->message = "Your proposal '{$proposal->title}' status has been updated to {$validated['status']}.";
            $notification->type = 'info';
        }

        $notification->read = false;
        $notification->proposal_id = $proposal->id;
        $notification->save();

        return redirect()->route('admin.proposals.review', $proposal->id)
            ->with('success', 'Proposal review submitted successfully.');
    }

    return view('admin.proposal-review', compact('proposal'));
}

public function submitFeedback(Request $request, Proposal $proposal)
{
    $validated = $request->validate([
        'feedback' => 'required|string',
        'type' => 'required|in:comment,revision',
    ]);

    $feedback = new ProposalFeedback();
    $feedback->proposal_id = $proposal->id;
    $feedback->reviewer_id = Auth::id();
    $feedback->feedback = (string) $validated['feedback'];
    $feedback->type = in_array($validated['type'], ['comment', 'revision']) ? $validated['type'] : 'comment';

    $feedback->save();

    // If revision requested, update proposal status
    if ($validated['type'] == 'revision') {
        $proposal->status = 'revision_requested';
        $proposal->save();

        // Notify the proposal owner
        $notification = new Notification();
        $notification->user_id = $proposal->user_id;
        $notification->title = 'Revision Requested';
        $notification->message = "Revisions have been requested for your proposal '{$proposal->title}'. Please check the feedback.";
        $notification->type = 'warning';
        $notification->read = false;
        $notification->proposal_id = $proposal->id;
        $notification->save();
    }

    return redirect()->back()->with('success', 'Feedback submitted successfully.');
}

   
    public function saveProposalVersion(Request $request, Proposal $proposal)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        
        $version = new ProposalVersion();
        $version->proposal_id = $proposal->id;
        $version->content = $validated['content'];
        $version->notes = $validated['notes'] ?? 'Version saved by admin';
        $version->created_by = Auth::id();
        $version->save();
        
        return redirect()->back()->with('success', 'Proposal version saved successfully.');
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users', compact('users'));
    }

    public function makeAdmin(Request $request, User $user)
    {
        $user->is_admin = true;
        $user->save();
        
        return redirect()->back()->with('success', 'User promoted to admin successfully.');
    }

    public function createUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'is_admin' => 'boolean',
        ]);
        
        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = bcrypt($validated['password']);
        $user->is_admin = $request->has('is_admin');
        $user->save();
        
        return redirect()->back()->with('success', 'User created successfully.');
    }
}