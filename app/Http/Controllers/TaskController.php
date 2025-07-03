<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::query();
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Filter by priority
        if ($request->has('priority') && $request->priority != '') {
            $query->where('priority', $request->priority);
        }
        
        // Filter by proposal
        if ($request->has('proposal_id') && $request->proposal_id != '') {
            $query->where('proposal_id', $request->proposal_id);
        }
        
        // Filter by assigned user
        if ($request->has('assigned_to') && $request->assigned_to != '') {
            $query->where('assigned_to', $request->assigned_to);
        }
        
        // Show only tasks assigned to current user if not admin
        if (!Auth::user()->is_admin) {
            $query->where('assigned_to', Auth::id());
        }
        
        $tasks = $query->with(['proposal', 'assignedUser', 'creator'])
                      ->orderBy('due_date')
                      ->paginate(15);
        
        $proposals = Proposal::select('id', 'title')->get();
        $users = User::select('id', 'name')->get();
        
        return view('tasks.index', compact('tasks', 'proposals', 'users'));
    }

    public function create()
    {
        $proposals = Proposal::select('id', 'title')->get();
        $users = User::select('id', 'name')->get();
        
        return view('tasks.create', compact('proposals', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'proposal_id' => 'nullable|exists:proposals,id',
        ]);
        
        $validated['created_by'] = Auth::id();
        
        Task::create($validated);
        
        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        
        $proposals = Proposal::select('id', 'title')->get();
        $users = User::select('id', 'name')->get();
        
        return view('tasks.edit', compact('task', 'proposals', 'users'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'proposal_id' => 'nullable|exists:proposals,id',
        ]);
        
        $task->update($validated);
        
        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        
        $task->delete();
        
        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }
}