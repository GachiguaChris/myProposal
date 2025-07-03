@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Proposal</h5>
                    <a href="{{ route('proposals.show', $proposal->id) }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Proposal
                    </a>
                </div>
                <div class="card-body">
                    @if($proposal->feedbacks && $proposal->feedbacks->where('type', 'revision')->count() > 0)
                        @php
                            $revisionFeedback = $proposal->feedbacks->where('type', 'revision')->last();
                            $fieldsToRevise = $revisionFeedback->revision_fields ? json_decode($revisionFeedback->revision_fields) : [];
                        @endphp
                        
                        <div class="alert alert-warning mb-4">
                            <h5><i class="bi bi-exclamation-triangle"></i> Revision Requested</h5>
                            <p>{{ $revisionFeedback->feedback }}</p>
                            
                            @if(count($fieldsToRevise) > 0)
                                <p class="mb-1"><strong>Please revise the following fields:</strong></p>
                                <ul class="mb-0">
                                    @foreach($fieldsToRevise as $field)
                                        <li>{{ ucfirst(str_replace('_', ' ', $field)) }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('proposals.update', $proposal->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="title" class="form-label">Proposal Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror @if(isset($fieldsToRevise) && in_array('title', $fieldsToRevise)) border-warning @endif" id="title" name="title" value="{{ old('title', $proposal->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="project_category_id" class="form-label">Project Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('project_category_id') is-invalid @enderror" id="project_category_id" name="project_category_id" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('project_category_id', $proposal->project_category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }} (Budget: ${{ number_format($category->budget) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('project_category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="submitted_by" class="form-label">Your Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('submitted_by') is-invalid @enderror" id="submitted_by" name="submitted_by" value="{{ old('submitted_by', $proposal->submitted_by) }}" required>
                                @error('submitted_by')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $proposal->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="organization_name" class="form-label">Organization Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('organization_name') is-invalid @enderror" id="organization_name" name="organization_name" value="{{ old('organization_name', $proposal->organization_name) }}" required>
                                @error('organization_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="organization_type" class="form-label">Organization Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('organization_type') is-invalid @enderror" id="organization_type" name="organization_type" required>
                                    <option value="">-- Select Type --</option>
                                    <option value="Non-profit" {{ old('organization_type', $proposal->organization_type) == 'Non-profit' ? 'selected' : '' }}>Non-profit</option>
                                    <option value="Government" {{ old('organization_type', $proposal->organization_type) == 'Government' ? 'selected' : '' }}>Government</option>
                                    <option value="Educational" {{ old('organization_type', $proposal->organization_type) == 'Educational' ? 'selected' : '' }}>Educational</option>
                                    <option value="Private" {{ old('organization_type', $proposal->organization_type) == 'Private' ? 'selected' : '' }}>Private</option>
                                    <option value="Other" {{ old('organization_type', $proposal->organization_type) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('organization_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $proposal->phone) }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $proposal->address) }}" required>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="summary" class="form-label">Executive Summary <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('summary') is-invalid @enderror @if(isset($fieldsToRevise) && in_array('summary', $fieldsToRevise)) border-warning @endif" id="summary" name="summary" rows="3" required>{{ old('summary', $proposal->summary) }}</textarea>
                            @error('summary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="background" class="form-label">Background <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('background') is-invalid @enderror @if(isset($fieldsToRevise) && in_array('background', $fieldsToRevise)) border-warning @endif" id="background" name="background" rows="4" required>{{ old('background', $proposal->background) }}</textarea>
                            @error('background')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="activities" class="form-label">Proposed Activities <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('activities') is-invalid @enderror @if(isset($fieldsToRevise) && in_array('activities', $fieldsToRevise)) border-warning @endif" id="activities" name="activities" rows="4" required>{{ old('activities', $proposal->activities) }}</textarea>
                            @error('activities')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="proposal_goals" class="form-label">Goals <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('proposal_goals') is-invalid @enderror @if(isset($fieldsToRevise) && in_array('proposal_goals', $fieldsToRevise)) border-warning @endif" id="proposal_goals" name="proposal_goals" rows="3" required>{{ old('proposal_goals', $proposal->proposal_goals) }}</textarea>
                                @error('proposal_goals')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="duration" class="form-label">Project Duration <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('duration') is-invalid @enderror @if(isset($fieldsToRevise) && in_array('duration', $fieldsToRevise)) border-warning @endif" id="duration" name="duration" value="{{ old('duration', $proposal->duration) }}" required>
                                <div class="form-text">Example: "6 months", "1 year", or specific dates like "Jan 2023 - Jun 2023"</div>
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="budget" class="form-label">Budget (USD) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" min="0" class="form-control @error('budget') is-invalid @enderror @if(isset($fieldsToRevise) && in_array('budget', $fieldsToRevise)) border-warning @endif" id="budget" name="budget" value="{{ old('budget', $proposal->budget) }}" required>
                                </div>
                                @error('budget')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="document" class="form-label">Supporting Document @if(isset($fieldsToRevise) && in_array('document', $fieldsToRevise)) <span class="text-warning">(Revision Requested)</span> @endif</label>
                                <input type="file" class="form-control @error('document') is-invalid @enderror @if(isset($fieldsToRevise) && in_array('document', $fieldsToRevise)) border-warning @endif" id="document" name="document">
                                <div class="form-text">
                                    @if($proposal->document)
                                        Current document: <a href="{{ asset('storage/' . $proposal->document) }}" target="_blank">View</a>
                                    @else
                                        No document uploaded yet.
                                    @endif
                                </div>
                                @error('document')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        @if($proposal->status == 'revision_requested')
                        <div class="mb-3">
                            <label for="revision_notes" class="form-label">Revision Notes <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('revision_notes') is-invalid @enderror" id="revision_notes" name="revision_notes" rows="3" placeholder="Describe the changes you made..." required>{{ old('revision_notes') }}</textarea>
                            @error('revision_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @endif
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                @if($proposal->status == 'revision_requested')
                                    <i class="bi bi-check-circle"></i> Submit Revisions
                                @else
                                    <i class="bi bi-save"></i> Save Changes
                                @endif
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection