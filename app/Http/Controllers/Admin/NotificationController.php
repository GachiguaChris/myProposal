<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Models\Proposal;
use App\Models\Client;
use App\Models\Task;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the notifications.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = Notification::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Show the form for creating a new notification.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        $proposals = Proposal::all();
        $clients = Client::all();
        $tasks = Task::all();
        return view('admin.notifications.create', compact('users', 'proposals', 'clients', 'tasks'));
    }

    /**
     * Store a newly created notification in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,success,warning,danger',
            'user_id' => 'required|exists:users,id',
            'proposal_id' => 'nullable|exists:proposals,id',
            'client_id' => 'nullable|exists:clients,id',
            'task_id' => 'nullable|exists:tasks,id',
        ]);

        $validated['read'] = false;
        
        // If a proposal is selected, get its title for the notification title if not provided
        if (!empty($validated['proposal_id']) && ($validated['title'] === 'Notification about proposal' || empty($validated['title']))) {
            $proposal = Proposal::find($validated['proposal_id']);
            if ($proposal && $proposal->title) {
                $validated['title'] = 'Notification about: ' . $proposal->title;
            }
        }
        
        Notification::create($validated);

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notification created successfully.');
    }

    /**
     * Display the specified notification.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notification = Notification::with(['user', 'proposal', 'client', 'task'])->findOrFail($id);
        
        // Mark as read if not already
        if (!$notification->read) {
            $notification->update(['read' => true]);
        }
        
        return view('admin.notifications.show', compact('notification'));
    }

    /**
     * Mark notification as read.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['read' => true]);
        
        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read.
     *
     * @return \Illuminate\Http\Response
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('read', false)
            ->update(['read' => true]);
        
        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Remove the specified notification from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notification deleted successfully.');
    }
}