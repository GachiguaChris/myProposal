@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1>Notification Details</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Notifications
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-{{ $notification->type }}">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 {{ in_array($notification->type, ['warning', 'danger']) ? 'text-dark' : 'text-white' }}">
                    {{ $notification->title }}
                </h5>
                <span class="badge bg-light text-dark">
                    {{ $notification->created_at->format('M d, Y H:i') }}
                </span>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <h6>Message:</h6>
                <div class="p-3 bg-light rounded">
                    {!! nl2br(e($notification->message)) !!}
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <h6>Status:</h6>
                    <p>
                        @if($notification->read)
                            <span class="badge bg-secondary">Read</span>
                        @else
                            <span class="badge bg-primary">Unread</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6">
                    <h6>For User:</h6>
                    <p>{{ $notification->user->name }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @if(!$notification->read)
                            <form action="{{ route('admin.notifications.markAsRead', $notification->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check"></i> 
                                    @if($notification->type == 'danger')
                                        Mark as Resolved
                                    @else
                                        Mark as Read
                                    @endif
                                </button>
                            </form>
                            @endif
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="bi bi-trash"></i> Delete Notification
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        @if($notification->type == 'danger')
        <div class="col-md-4">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">System Error</h5>
                </div>
                <div class="card-body">
                    <p>This notification indicates a system error that requires administrator attention.</p>
                    <p>Common actions to resolve:</p>
                    <ul>
                        <li>Check system logs for more details</li>
                        <li>Verify database connections</li>
                        <li>Check file permissions</li>
                        <li>Review recent code changes</li>
                    </ul>
                    <p>Once the issue is resolved, mark this notification as resolved.</p>
                </div>
            </div>
        </div>
        @endif
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
                Are you sure you want to delete this notification?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection