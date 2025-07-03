<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssignedNotification extends Notification implements ShouldQueue
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
        $dueDate = $this->task->due_date ? $this->task->due_date->format('M d, Y h:i A') : 'No deadline';
        
        return (new MailMessage)
            ->subject('New Task Assigned: ' . $this->task->title)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('You have been assigned a new task: ' . $this->task->title)
            ->line('Priority: ' . ucfirst($this->task->priority))
            ->line('Due Date: ' . $dueDate)
            ->line('Description: ' . $this->task->description)
            ->action('View Task', url('/tasks/' . $this->task->id))
            ->line('Please complete this task before the deadline.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'due_date' => $this->task->due_date,
            'priority' => $this->task->priority,
            'message' => 'You have been assigned a new task: ' . $this->task->title,
            'url' => '/tasks/' . $this->task->id,
        ];
    }
    /**
 * Get the database representation of the notification.
 */
public function toDatabase($notifiable)
{
    return $this->toArray($notifiable);
}

}