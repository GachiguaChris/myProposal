@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Submit a Proposal</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs mb-4" id="proposalTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="template-tab" data-bs-toggle="tab" data-bs-target="#template-pane" type="button" role="tab" aria-controls="template-pane" aria-selected="true">
                                <i class="bi bi-file-earmark-arrow-up"></i> Upload Template Form
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="online-tab" data-bs-toggle="tab" data-bs-target="#online-pane" type="button" role="tab" aria-controls="online-pane" aria-selected="false">
                                <i class="bi bi-pencil-square"></i> Fill Online Form
                            </button>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="proposalTabsContent">
                        <!-- Template Upload Form -->
                        <div class="tab-pane fade show active" id="template-pane" role="tabpanel" aria-labelledby="template-tab">
                            <div class="alert alert-info mb-4">
                                <h5><i class="bi bi-info-circle"></i> Template Form Submission</h5>
                                <p>Follow these steps to submit your proposal using a template form:</p>
                                <ol>
                                    <li>Download a template form from the list below</li>
                                    <li>Fill out the form completely</li>
                                    <li>Upload the completed form along with basic information</li>
                                </ol>
                            </div>
                            
                            @if($templates->count() > 0)
                                <div class="mb-4">
                                    <h6>Available Templates:</h6>
                                    <div class="list-group">
                                        @foreach($templates as $template)
                                            @if($template->file_path)
                                                <a href="{{ route('admin.templates.download', $template->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong>{{ $template->name }}</strong>
                                                        @if($template->category)
                                                            <span class="badge bg-secondary ms-2">{{ $template->category->name }}</span>
                                                        @endif
                                                        <p class="mb-0 text-muted small">{{ Str::limit($template->description, 100) }}</p>
                                                    </div>
                                                    <span class="btn btn-sm btn-primary"><i class="bi bi-download"></i> Download</span>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                
                                <form method="POST" action="{{ route('proposals.store') }}" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="is_template_upload" value="1">
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="submitted_by" class="form-label">Your Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('submitted_by') is-invalid @enderror" id="submitted_by" name="submitted_by" value="{{ old('submitted_by') }}" required>
                                            @error('submitted_by')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="organization_name" class="form-label">Organization Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('organization_name') is-invalid @enderror" id="organization_name" name="organization_name" value="{{ old('organization_name') }}" required>
                                            @error('organization_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="project_category_id" class="form-label">Project Category <span class="text-danger">*</span></label>
                                        <select class="form-select @error('project_category_id') is-invalid @enderror" id="project_category_id" name="project_category_id" required>
                                            <option value="">-- Select Category --</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('project_category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }} (Budget: ${{ number_format($category->budget) }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('project_category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="template_file" class="form-label">Upload Completed Template Form <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control @error('template_file') is-invalid @enderror" id="template_file" name="template_file" required>
                                        <div class="form-text">Upload your completed template form (PDF, DOC, or DOCX format).</div>
                                        @error('template_file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-upload"></i> Submit Proposal
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="alert alert-warning">
                                    <p>No downloadable templates are currently available. Please use the online form instead.</p>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Online Form -->
                        <div class="tab-pane fade" id="online-pane" role="tabpanel" aria-labelledby="online-tab">
                            <form method="POST" action="{{ route('proposals.store') }}" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="title" class="form-label">Proposal Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="project_category_id" class="form-label">Project Category <span class="text-danger">*</span></label>
                                        <select class="form-select @error('project_category_id') is-invalid @enderror" id="project_category_id" name="project_category_id" required>
                                            <option value="">-- Select Category --</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('project_category_id') == $category->id ? 'selected' : '' }}>
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
                                        <input type="text" class="form-control @error('submitted_by') is-invalid @enderror" id="submitted_by" name="submitted_by" value="{{ old('submitted_by') }}" required>
                                        @error('submitted_by')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="organization_name" class="form-label">Organization Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('organization_name') is-invalid @enderror" id="organization_name" name="organization_name" value="{{ old('organization_name') }}" required>
                                        @error('organization_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="organization_type" class="form-label">Organization Type <span class="text-danger">*</span></label>
                                        <select class="form-select @error('organization_type') is-invalid @enderror" id="organization_type" name="organization_type" required>
                                            <option value="">-- Select Type --</option>
                                            <option value="Non-profit" {{ old('organization_type') == 'Non-profit' ? 'selected' : '' }}>Non-profit</option>
                                            <option value="Government" {{ old('organization_type') == 'Government' ? 'selected' : '' }}>Government</option>
                                            <option value="Educational" {{ old('organization_type') == 'Educational' ? 'selected' : '' }}>Educational</option>
                                            <option value="Private" {{ old('organization_type') == 'Private' ? 'selected' : '' }}>Private</option>
                                            <option value="Other" {{ old('organization_type') == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('organization_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" required>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="summary" class="form-label">Executive Summary <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('summary') is-invalid @enderror" id="summary" name="summary" rows="3" required>{{ old('summary') }}</textarea>
                                    <div class="form-text">Provide a brief overview of your proposal (2-3 sentences).</div>
                                    @error('summary')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="background" class="form-label">Background <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('background') is-invalid @enderror" id="background" name="background" rows="4" required>{{ old('background') }}</textarea>
                                    <div class="form-text">Describe the problem or opportunity that this proposal addresses.</div>
                                    @error('background')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="activities" class="form-label">Proposed Activities <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('activities') is-invalid @enderror" id="activities" name="activities" rows="4" required>{{ old('activities') }}</textarea>
                                    <div class="form-text">Outline the specific activities you plan to undertake.</div>
                                    @error('activities')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="proposal_goals" class="form-label">Goals <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('proposal_goals') is-invalid @enderror" id="proposal_goals" name="proposal_goals" rows="3" required>{{ old('proposal_goals') }}</textarea>
                                        <div class="form-text">What are the main goals of this proposal?</div>
                                        @error('proposal_goals')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="duration" class="form-label">Project Duration <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" value="{{ old('duration') }}" required>
                                        <div class="form-text">Example: "6 months", "1 year", etc.</div>
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
                                            <input type="number" step="0.01" min="0" class="form-control @error('budget') is-invalid @enderror" id="budget" name="budget" value="{{ old('budget') }}" required>
                                        </div>
                                        @error('budget')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="document" class="form-label">Supporting Document</label>
                                        <input type="file" class="form-control @error('document') is-invalid @enderror" id="document" name="document">
                                        <div class="form-text">Optional: Upload any supporting documents (PDF, DOC, DOCX).</div>
                                        @error('document')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-send"></i> Submit Proposal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection