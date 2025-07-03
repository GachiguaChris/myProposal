@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1>Review Proposal</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.proposals.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Proposals
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $proposal->title }}</h5>
                    <span class="badge bg-{{ 
                        $proposal->status == 'accepted' ? 'success' : 
                        ($proposal->status == 'rejected' ? 'danger' : 
                        ($proposal->status == 'revision_requested' ? 'warning' : 'info')) 
                    }}">
                        {{ ucfirst(str_replace('_', ' ', $proposal->status)) }}
                    </span>
                </div>
                <div class="card-body">
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
                                    <th>Category:</th>
                                    <td>{{ $proposal->category->name ?? 'Uncategorized' }}</td>
                                </tr>
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
                                <tr>
                                    <th>Submitted:</th>
                                    <td>{{ $proposal->created_at->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td>{{ $proposal->updated_at->format('M d, Y') }}</td>
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
            </div>
            
            @if($proposal->versions && $proposal->versions->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Revision History</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Version</th>
                                        <th>Date</th>
                                        <th>By</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($proposal->versions as $version)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $version->created_at->format('M d, Y H:i') }}</td>
                                            <td>{{ $version->creator->name ?? 'Unknown' }}</td>
                                            <td>{{ $version->notes }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Review Decision</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.proposals.review', $proposal->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pending" {{ $proposal->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="accepted" {{ $proposal->status == 'accepted' ? 'selected' : '' }}>Accept</option>
                                <option value="rejected" {{ $proposal->status == 'rejected' ? 'selected' : '' }}>Reject</option>
                                <option value="revision_requested" {{ $proposal->status == 'revision_requested' ? 'selected' : '' }}>Request Revisions</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="feedback" class="form-label">Feedback</label>
                            <textarea class="form-control" id="feedback" name="feedback" rows="5" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="feedback_type" class="form-label">Feedback Type</label>
                            <select class="form-select" id="feedback_type" name="feedback_type" required>
                              <option value="comment">General Comment</option>
                              <option value="revision">Request Revision</option>
                              <option value="approval">Approve</option>
                              <option value="rejection">Reject</option>
                            </select>
                        </div>
                        
                        <div id="revision_fields_section" class="mb-3 d-none">
                            <label class="form-label">Fields Requiring Revision</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="revision_fields[]" value="title" id="field_title">
                                <label class="form-check-label" for="field_title">Title</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="revision_fields[]" value="summary" id="field_summary">
                                <label class="form-check-label" for="field_summary">Executive Summary</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="revision_fields[]" value="background" id="field_background">
                                <label class="form-check-label" for="field_background">Background</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="revision_fields[]" value="activities" id="field_activities">
                                <label class="form-check-label" for="field_activities">Activities</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="revision_fields[]" value="budget" id="field_budget">
                                <label class="form-check-label" for="field_budget">Budget</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="revision_fields[]" value="proposal_goals" id="field_goals">
                                <label class="form-check-label" for="field_goals">Goals</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="revision_fields[]" value="duration" id="field_duration">
                                <label class="form-check-label" for="field_duration">Duration</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="revision_fields[]" value="document" id="field_document">
                                <label class="form-check-label" for="field_document">Supporting Document</label>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Submit Review</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Feedback History</h5>
                </div>
                <div class="card-body p-0">
                    @if($proposal->feedbacks && $proposal->feedbacks->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($proposal->feedbacks as $feedback)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span>
                                            <strong>{{ $feedback->reviewer->name }}</strong>
                                            <span class="badge bg-{{ $feedback->type == 'revision' ? 'warning' : 'info' }}">
                                                {{ ucfirst($feedback->type) }}
                                            </span>
                                        </span>
                                        <small class="text-muted">{{ $feedback->created_at->format('M d, Y') }}</small>
                                    </div>
                                    <p class="mb-0">{{ $feedback->feedback }}</p>
                                    
                                    @if($feedback->revision_fields)
                                        <div class="mt-2">
                                            <small class="text-muted">Fields to revise:</small>
                                            <div>
                                                @foreach(json_decode($feedback->revision_fields) as $field)
                                                    <span class="badge bg-light text-dark me-1">{{ ucfirst(str_replace('_', ' ', $field)) }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-3 text-center text-muted">
                            No feedback provided yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status');
        const feedbackTypeSelect = document.getElementById('feedback_type');
        const revisionFieldsSection = document.getElementById('revision_fields_section');
        
        function updateVisibility() {
            if (statusSelect.value === 'revision_requested' || feedbackTypeSelect.value === 'revision') {
                revisionFieldsSection.classList.remove('d-none');
            } else {
                revisionFieldsSection.classList.add('d-none');
            }
        }
        
        statusSelect.addEventListener('change', updateVisibility);
        feedbackTypeSelect.addEventListener('change', updateVisibility);
        
        // Initial check
        updateVisibility();
    });
</script>
@endpush
@endsection