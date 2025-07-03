@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1>Task Details</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.tasks.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Tasks
            </a>
            <a href="{{ route('admin.tasks.edit', $task->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Edit Task
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">{{ $task->title }}</h5>
                        <div>
                            <span class="badge bg-{{ $task->priority == 'urgent' ? 'danger' : ($task->priority == 'high' ? 'warning' : ($task->priority == 'medium' ? 'info' : 'secondary')) }} me-2">
                                {{ ucfirst($task->priority) }}
                            </span>
                            <span class="badge bg-{{ $task->status == 'completed' ? 'success' : ($task->status == 'in_progress' ? 'primary' : ($task->status == 'pending' ? 'warning' : 'secondary')) }}">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($task->description)
                    <div class="mb-4">
                        <h6>Description:</h6>
                        <p>{{ $task->description }}</p>
                    </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Due Date:</h6>
                            <p>{{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Assigned To:</h6>
                            <p>{{ $task->user->name }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Created:</h6>
                            <p>{{ $task->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Last Updated:</h6>
                            <p>{{ $task->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @if($task->status != 'completed')
                            <form action="{{ route('admin.tasks.update', $task->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="title" value="{{ $task->title }}">
                                <input type="hidden" name="description" value="{{ $task->description }}">
                                <input type="hidden" name="due_date" value="{{ $task->due_date }}">
                                <input type="hidden" name="user_id" value="{{ $task->user_id }}">
                                <input type="hidden" name="priority" value="{{ $task->priority }}">
                                <input type="hidden" name="proposal_id" value="{{ $task->proposal_id }}">
                                <input type="hidden" name="client_id" value="{{ $task->client_id }}">
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle"></i> Mark as Completed
                                </button>
                            </form>
                            @endif
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="bi bi-trash"></i> Delete Task
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Related Items</h5>
                </div>
                <div class="card-body">
                    @if($task->proposal)
                    <div class="mb-3">
                        <h6>Related Proposal:</h6>
                        <a href="{{ route('admin.proposals.review', $task->proposal->id) }}" class="btn btn-sm btn-outline-primary">
                            {{ $task->proposal->title }}
                        </a>
                    </div>
                    @endif
                    
                    @if($task->client)
                    <div>
                        <h6>Related Client:</h6>
                        <a href="{{ route('admin.clients.show', $task->client->id) }}" class="btn btn-sm btn-outline-info">
                            {{ $task->client->name }}
                        </a>
                    </div>
                    @endif
                    
                    @if(!$task->proposal && !$task->client)
                    <p class="text-muted">No related items found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete task: <strong>{{ $task->title }}</strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.tasks.destroy', $task->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection