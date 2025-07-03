<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\ProposalFeedback;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ProposalFeedbackSubmitted;

class ProposalFeedbackController extends Controller
{
    public function store(Request $request, $proposalId)
    {
        // Validate against frontend-friendly values
        $request->validate([
            'type' => 'required|in:comment,revision,approval,rejection',
            'feedback' => 'nullable|string|max:5000',
            'attachment' => 'nullable|file|max:10240',
            'revision_requested' => 'required|boolean',
        ]);

        $proposal = Proposal::findOrFail($proposalId);

        $feedback = new ProposalFeedback();
        $feedback->proposal_id = $proposal->id;
        $feedback->reviewer_id = Auth::id();
        $feedback->feedback = (string) $request->input('feedback');
        $feedback->revision_requested = (bool) $request->input('revision_requested');

        // ✅ Map frontend 'type' to DB ENUM values
        $frontendType = $request->input('type');
        $mappedType = match ($frontendType) {
            'revision' => 'revision',
            'approval', 'rejection' => 'conditional',
            default => 'general', // for 'comment' or any fallback
        };

        $feedback->type = $mappedType;

        // Handle attachment if provided
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('feedback_attachments', $filename, 'public');
            $feedback->attachment = $path;
        }

        $feedback->save();

        // Notify the proposal submitter
        $proposal->user->notify(new ProposalFeedbackSubmitted($feedback));

        return redirect()->back()->with('success', 'Feedback submitted and user notified.');
    }
}
