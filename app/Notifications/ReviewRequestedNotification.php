<?php

namespace App\Notifications;

use App\Models\Proposal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewRequestedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $proposal;

    /**
     * Create a new notification instance.
     */
    public function __construct(Proposal $proposal)
    {
        $this->proposal = $proposal;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Review Requested: ' . $this->proposal->title)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A review has been requested for the proposal: ' . $this->proposal->title)
            ->line('Please review the proposal and provide feedback.')
            ->action('Review Proposal', url(route('admin.proposals.review', $this->proposal->id)))
            ->line('Thank you for your attention to this matter.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'proposal_id' => $this->proposal->id,
            'title' => $this->proposal->title,
            'message' => 'Review requested for proposal: ' . $this->proposal->title,
            'url' => route('admin.proposals.review', $this->proposal->id),
        ];
    }
}