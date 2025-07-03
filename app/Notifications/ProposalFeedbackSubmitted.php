<?php
namespace App\Notifications;

use App\Models\ProposalFeedback;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ProposalFeedbackSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $feedback;

    public function __construct(ProposalFeedback $feedback)
    {
        $this->feedback = $feedback;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

   public function toMail($notifiable)
{
    $proposal = $this->feedback->proposal;

    $mail = (new MailMessage)
        ->subject('New Feedback on Your Proposal')
        ->greeting('Hello ' . $notifiable->name . ',')
        ->line("New feedback has been submitted for your proposal titled **{$proposal->title}**.")
        ->line('Type: ' . ucfirst($this->feedback->type))
        ->line('Feedback:')
        ->line($this->feedback->feedback);

    // ✅ Add revision fields if applicable
    if (
        $this->feedback->type === 'revision' &&
        is_array($this->feedback->revision_fields) &&
        count($this->feedback->revision_fields) > 0
    ) {
        $mail->line('⚠️ **Revisions are requested for the following parts of your proposal:**');

        foreach ($this->feedback->revision_fields as $field) {
            $mail->line('- ' . ucfirst(str_replace('_', ' ', $field)));
        }
    }

    // ✅ Attach supporting file if present
    if ($this->feedback->attachment) {
        $mail->attach(storage_path('app/public/' . $this->feedback->attachment));
    }

    return $mail
        ->action('View Proposal', url(route('proposals.show', $proposal->id)))
        ->line('Thank you for your continued collaboration.');
}


    public function toDatabase($notifiable)
    {
        return [
            'proposal_id' => $this->feedback->proposal_id,
            'title' => $this->feedback->proposal->title,
            'type' => $this->feedback->type,
            'feedback' => $this->feedback->feedback,
            'reviewed_by' => $this->feedback->reviewer->name,
        ];
    }
}
