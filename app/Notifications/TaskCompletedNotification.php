<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;

    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
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
            ->subject('Task Completed: ' . $this->task->title)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A task has been completed: ' . $this->task->title)
            ->line('Completed by: ' . ($this->task->assignedUser->name ?? 'Unknown'))
            ->action('View Task', url('/admin/tasks/' . $this->task->id))
            ->line('You are receiving this notification because you are an administrator.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'completed_by' => $this->task->assignedUser->id ?? null,
            'completed_by_name' => $this->task->assignedUser->name ?? 'Unknown',
            'message' => 'Task completed: ' . $this->task->title,
            'url' => '/admin/tasks/' . $this->task->id,
        ];
    }
}