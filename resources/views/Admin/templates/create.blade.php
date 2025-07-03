@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1>Create Template</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.templates.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Templates
            </a>
        </div>
    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h5><i class="bi bi-exclamation-triangle-fill"></i> Error</h5>
        <p>{{ session('error') }}</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.templates.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Template Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="project_category_id" class="form-label">Category</label>
                        <select class="form-select @error('project_category_id') is-invalid @enderror" id="project_category_id" name="project_category_id">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('project_category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" id="templateTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="file-tab" data-bs-toggle="tab" data-bs-target="#file-pane" type="button" role="tab" aria-controls="file-pane" aria-selected="true">Upload Template File</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="content-tab" data-bs-toggle="tab" data-bs-target="#content-pane" type="button" role="tab" aria-controls="content-pane" aria-selected="false">Online Template Content</button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="templateTabsContent">
                            <div class="tab-pane fade show active" id="file-pane" role="tabpanel" aria-labelledby="file-tab">
                                <div class="mb-3">
                                    <label for="template_file" class="form-label">Template File (PDF, DOC, DOCX)</label>
                                    <input type="file" class="form-control @error('template_file') is-invalid @enderror" id="template_file" name="template_file">
                                    <div class="form-text">Upload a pre-designed template form that users can download, fill out, and upload back.</div>
                                    @error('template_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="alert alert-info">
                                    <h5><i class="bi bi-info-circle"></i> Downloadable Template Forms</h5>
                                    <p>Upload a template form that users can:</p>
                                    <ol>
                                        <li>Download and fill out offline</li>
                                        <li>Upload back to the system with minimal additional information</li>
                                    </ol>
                                    <p>The form should include all necessary fields for a complete proposal.</p>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="content-pane" role="tabpanel" aria-labelledby="content-tab">
                                <div class="mb-3">
                                    <label for="content" class="form-label">Template Content</label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="15">{{ old('content', $sampleContent) }}</textarea>
                                    <div class="form-text">This content will be used to generate a PDF template if no file is uploaded.</div>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Save Template
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#content',
        height: 500,
        plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
        toolbar_mode: 'floating',
        toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help'
    });
    
    // Handle tab switching to ensure TinyMCE editor is properly initialized
    document.addEventListener('DOMContentLoaded', function() {
        const contentTab = document.getElementById('content-tab');
        if (contentTab) {
            contentTab.addEventListener('shown.bs.tab', function() {
                tinymce.execCommand('mceRemoveEditor', false, 'content');
                tinymce.execCommand('mceAddEditor', false, 'content');
            });
        }
    });
</script>
@endpush
@endsection