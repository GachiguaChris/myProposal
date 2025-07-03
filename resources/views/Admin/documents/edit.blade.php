@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1>Edit Document</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.documents.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Documents
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.documents.update', $document->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="name" class="form-label">Document Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $document->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $document->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="file" class="form-label">File</label>
                    <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file">
                    <div class="form-text">
                        Current file: {{ $document->file_path ? basename($document->file_path) : 'None' }}
                        <br>Leave empty to keep the current file. Maximum file size: 10MB
                    </div>
                    @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="proposal_id" class="form-label">Related Proposal</label>
                        <select class="form-select @error('proposal_id') is-invalid @enderror" id="proposal_id" name="proposal_id">
                            <option value="">-- None --</option>
                            @foreach($proposals as $proposal)
                                <option value="{{ $proposal->id }}" {{ old('proposal_id', $document->proposal_id) == $proposal->id ? 'selected' : '' }}>
                                    {{ $proposal->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('proposal_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="client_id" class="form-label">Related Client</label>
                        <select class="form-select @error('client_id') is-invalid @enderror" id="client_id" name="client_id">
                            <option value="">-- None --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id', $document->client_id) == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Update Document
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection