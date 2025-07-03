<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent;
class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'type',
        'read',
        'user_id',
        'proposal_id',
        'client_id',
        'task_id'
    ];

    /**
     * Get the user associated with the notification.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the proposal associated with the notification.
     */
    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    /**
     * Get the client associated with the notification.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the task associated with the notification.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}