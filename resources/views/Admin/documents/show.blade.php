@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1>Document Details</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.documents.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Documents
            </a>
            <a href="{{ route('admin.documents.edit', $document->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Edit Document
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ $document->name }}</h5>
                </div>
                <div class="card-body">
                    @if($document->description)
                    <div class="mb-4">
                        <h6>Description:</h6>
                        <p>{{ $document->description }}</p>
                    </div>
                    @endif
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6>File Type:</h6>
                            <p>{{ $document->file_type }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>File Size:</h6>
                            <p>{{ number_format($document->file_size / 1024, 2) }} KB</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Uploaded By:</h6>
                            <p>{{ $document->user->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Upload Date:</h6>
                            <p>{{ $document->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('admin.documents.download', $document->id) }}" class="btn btn-success">
                            <i class="bi bi-download"></i> Download Document
                        </a>
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
                            <a href="{{ route('admin.documents.edit', $document->id) }}" class="btn btn-primary">
                                <i class="bi bi-pencil"></i> Edit Document
                            </a>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="bi bi-trash"></i> Delete Document
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
                    @if($document->proposal)
                    <div class="mb-3">
                        <h6>Related Proposal:</h6>
                        <a href="{{ route('admin.proposals.review', $document->proposal->id) }}" class="btn btn-sm btn-outline-primary">
                            {{ $document->proposal->title }}
                        </a>
                    </div>
                    @endif
                    
                    @if($document->client)
                    <div>
                        <h6>Related Client:</h6>
                        <a href="{{ route('admin.clients.show', $document->client->id) }}" class="btn btn-sm btn-outline-info">
                            {{ $document->client->name }}
                        </a>
                    </div>
                    @endif
                    
                    @if(!$document->proposal && !$document->client)
                    <p class="text-muted">No related items found.</p>
                    @endif
                </div>
            </div>
            
            @if(in_array($document->file_type, ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml']))
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Preview</h5>
                </div>
                <div class="card-body text-center">
                    <img src="{{ Storage::url($document->file_path) }}" alt="{{ $document->name }}" class="img-fluid rounded">
                </div>
            </div>
            @endif
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
                Are you sure you want to delete document: <strong>{{ $document->name }}</strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.documents.destroy', $document->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection