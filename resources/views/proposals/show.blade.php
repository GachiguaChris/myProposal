@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $proposal->title }}</h5>
                    <div>
                        @if($proposal->user_id == Auth::id())
                            @if($proposal->status == 'revision_requested')
                                <a href="{{ route('proposals.edit', $proposal) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Make Requested Revisions
                                </a>
                            @elseif($proposal->status == 'pending')
                                <a href="{{ route('proposals.edit', $proposal) }}" class="btn btn-primary">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('proposals.request-review', $proposal) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-info">
                                        <i class="bi bi-bell"></i> Request Review
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <span class="badge bg-secondary me-2">Category: {{ $proposal->category->name ?? 'Uncategorized' }}</span>
                                @php
                                    $statusClass = match($proposal->status) {
                                        'pending' => 'warning',
                                        'accepted' => 'success',
                                        'rejected' => 'danger',
                                        'review_requested' => 'info',
                                        'approved_with_conditions' => 'primary',
                                        'revision_requested' => 'warning',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">
                                    Status: {{ ucfirst(str_replace('_', ' ', $proposal->status)) }}
                                </span>
                                @if($proposal->stage)
                                <span class="badge bg-info ms-2">
                                    Stage: {{ $proposal->stage }}
                                </span>
                                @endif
                            </div>
                            <div>
                                <small class="text-muted">Submitted: {{ $proposal->created_at->format('M d, Y') }}</small>
                            </div>
                        </div>
                        
                        @if($proposal->status == 'revision_requested')
                            <div class="alert alert-info">
                                <h5><i class="bi bi-info-circle"></i> Revision Requested</h5>
                                <p>Please review the feedback below and make the requested changes to your proposal.</p>
                            </div>
                        @endif
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6>Organization Information</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <th>Name:</th>
                                        <td>{{ $proposal->organization_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Type:</th>
                                        <td>{{ $proposal->organization_type }}</td>
                                    </tr>
                                    <tr>
                                        <th>Contact:</th>
                                        <td>{{ $proposal->submitted_by }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{{ $proposal->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone:</th>
                                        <td>{{ $proposal->phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>Address:</th>
                                        <td>{{ $proposal->address }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6>Proposal Details</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <th>Budget:</th>
                                        <td>${{ number_format($proposal->budget, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Duration:</th>
                                        <td>{{ $proposal->duration }}</td>
                                    </tr>
                                    <tr>
                                        <th>Goals:</th>
                                        <td>{{ $proposal->proposal_goals }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h6>Executive Summary</h6>
                            <p>{{ $proposal->summary }}</p>
                        </div>
                        
                        <div class="mb-4">
                            <h6>Background</h6>
                            <p>{{ $proposal->background }}</p>
                        </div>
                        
                        <div class="mb-4">
                            <h6>Proposed Activities</h6>
                            <p>{{ $proposal->activities }}</p>
                        </div>
                        
                        @if($proposal->document)
                            <div class="mb-4">
                                <h6>Supporting Document</h6>
                                <a href="{{ asset('storage/' . $proposal->document) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                    <i class="bi bi-file-earmark"></i> View Document
                                </a>
                            </div>
                        @endif
                    </div>
                    
                    @if($proposal->feedbacks && $proposal->feedbacks->count() > 0)
                        <hr>
                        <h5 class="mb-3">Feedback</h5>
                     @foreach($proposal->feedbacks as $feedback)
                            <div class="card mb-3 {{ $feedback->type == 'revision' ? 'border-warning' : 'border-info' }}">
                                <div class="card-header bg-{{ $feedback->type == 'revision' ? 'warning' : 'info' }} bg-opacity-10">
                                    <div class="d-flex justify-content-between">
                                        <span>
                                            <strong>{{ $feedback->reviewer->name }}</strong> 
                                            <span class="badge bg-{{ $feedback->type == 'revision' ? 'warning' : 'info' }}">
                                                {{ ucfirst($feedback->type) }}
                                            </span>
                                        </span>
                                        <small>{{ $feedback->created_at->format('M d, Y h:i A') }}</small>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $feedback->feedback }}</p>
                                    @if($feedback->attachment)
                                        <div class="mt-2">
                                            <a href="{{ asset('storage/' . $feedback->attachment) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                                                <i class="bi bi-paperclip"></i> View Attachment
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                    
                    @if($proposal->status == 'revision_requested' && $proposal->user_id == Auth::id())
                        <hr>
                        <h5 class="mb-3">Submit Revisions</h5>
                        <form action="{{ route('proposals.submit-revisions', $proposal) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="revision_notes" class="form-label">Revision Notes</label>
                                <textarea class="form-control" id="revision_notes" name="revision_notes" rows="3" placeholder="Describe the changes you made..." required>{{ old('revision_notes') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Revisions</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection