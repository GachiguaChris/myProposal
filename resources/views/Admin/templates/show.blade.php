@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1>Template Details</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.templates.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Templates
            </a>
            <a href="{{ route('admin.templates.edit', $template->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Edit Template
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">{{ $template->name }}</h5>
                <span class="badge bg-{{ $template->status == 'active' ? 'success' : 'secondary' }}">
                    {{ ucfirst($template->status) }}
                </span>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Category:</strong> {{ $template->category ? $template->category->name : 'N/A' }}
                </div>
                <div class="col-md-6">
                    <strong>Created:</strong> {{ $template->created_at->format('M d, Y') }}
                </div>
            </div>
            
            @if($template->description)
            <div class="mb-4">
                <strong>Description:</strong>
                <p class="mt-2">{{ $template->description }}</p>
            </div>
            @endif
            
            <div class="mb-3">
                <strong>Template Content:</strong>
                <div class="border rounded p-3 mt-2 bg-light">
                    {!! $template->content !!}
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
                    <a href="{{ route('admin.proposals.create') }}?template_id={{ $template->id }}" class="btn btn-success">
                        <i class="bi bi-file-earmark-plus"></i> Create Proposal from Template
                    </a>
                </div>
                <div class="col-md-6 text-end">
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="bi bi-trash"></i> Delete Template
                    </button>
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
                Are you sure you want to delete template: <strong>{{ $template->name }}</strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.templates.destroy', $template->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection