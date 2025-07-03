<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Proposal;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use App\Notifications\TaskCompletedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::query();
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('priority') && $request->priority != '') {
            $query->where('priority', $request->priority);
        }

        if ($request->has('proposal_id') && $request->proposal_id != '') {
            $query->where('proposal_id', $request->proposal_id);
        }

        if ($request->has('assigned_to') && $request->assigned_to != '') {
            $query->where('assigned_to', $request->assigned_to);
        }

        $tasks = $query->with(['proposal', 'assignedUser', 'creator'])
                      ->orderBy('due_date')
                      ->paginate(15);

        $proposals = Proposal::select('id', 'title')->get();
        $users = User::select('id', 'name')->get();

        return view('admin.tasks.index', compact('tasks', 'proposals', 'users'));
    }

    public function create()
    {
        $proposals = Proposal::select('id', 'title')->get();
        $users = User::select('id', 'name')->get();

        return view('admin.tasks.create', compact('proposals', 'users'));
    }

  

public function store(Request $request)
{
    // dd($request->all());

    $validated = $request->validate([
        
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'due_date' => 'required|date',
        'status' => 'required|in:pending,in_progress,completed,cancelled',
        'priority' => 'required|in:low,medium,high,urgent',
        'assigned_to' => 'required|exists:users,id',
        'proposal_id' => 'nullable|exists:proposals,id',
    ]);

    // Automatically attach the currently logged-in user
    // $validated['created_by'] = Auth::id();
 $validated['created_by'] = auth()->id(); 
 $validated['client_id'] = auth()->user()->client_id ?? null;// set creator
    //  dd($validated);

    $task = Task::create($validated);

    // Notify the assigned user
    // $assignedUser = User::find($validated['assigned_to']);
    // $assignedUser->notify(new TaskAssignedNotification($task));

    return redirect()->route('admin.tasks.index')
        ->with('success', 'Task created successfully and notification sent to assigned user.');
}



    public function show(Task $task)
    {
        return view('admin.tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $proposals = Proposal::select('id', 'title')->get();
        $users = User::select('id', 'name')->get();

        return view('admin.tasks.edit', compact('task', 'proposals', 'users'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'required|exists:users,id',
            'proposal_id' => 'nullable|exists:proposals,id',
        ]);

        $oldStatus = $task->status;
        $oldAssignedTo = $task->assigned_to;

     $task->update($validated);

        // Notify the assigned user if the status is changed to "Completed"
        if ($oldStatus != 'completed' && $validated['status'] == 'completed') {
            $assignedUser = User::find($validated['assigned_to']);
            $assignedUser->notify(new TaskCompletedNotification($task));
        }

        if ($oldStatus != 'completed' && $validated['status'] == 'completed') {
            $admin = User::where('is_admin', true)->first();
            if ($admin) {
                $admin->notify(new TaskCompletedNotification($task));
            }
        }

        if ($oldAssignedTo != $validated['assigned_to']) {
            $newAssignedUser = User::find($validated['assigned_to']);
            $newAssignedUser->notify(new TaskAssignedNotification($task));
        }

        return redirect()->route('admin.tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('admin.tasks.index')
            ->with('success', 'Task deleted successfully.');
    }

    public function markAsCompleted(Task $task)
    {
        $task->status = 'completed';
        $task->save();

        $admin = User::where('is_admin', true)->first();
        if ($admin) {
            $admin->notify(new TaskCompletedNotification($task));
        }

        return redirect()->back()->with('success', 'Task marked as completed.');
    }
}
