@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1>Documents</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.documents.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Upload Document
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Size</th>
                            <th>Related To</th>
                            <th>Uploaded By</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $document)
                        <tr>
                            <td>{{ $document->name }}</td>
                            <td>{{ $document->file_type }}</td>
                            <td>{{ number_format($document->file_size / 1024, 2) }} KB</td>
                            <td>
                                @if($document->proposal)
                                    <a href="{{ route('admin.proposals.review', $document->proposal->id) }}" class="badge bg-info text-decoration-none">
                                        Proposal: {{ Str::limit($document->proposal->title, 30) }}
                                    </a>
                                @elseif($document->client)
                                    <a href="{{ route('admin.clients.show', $document->client->id) }}" class="badge bg-primary text-decoration-none">
                                        Client: {{ $document->client->name }}
                                    </a>
                                @else
                                    <span class="badge bg-secondary">General</span>
                                @endif
                            </td>
                            <td>{{ $document->user->name }}</td>
                            <td>{{ $document->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.documents.download', $document->id) }}" class="btn btn-sm btn-success">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    <a href="{{ route('admin.documents.show', $document->id) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.documents.edit', $document->id) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $document->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $document->id }}" tabindex="-1" aria-hidden="true">
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
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No documents found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $documents->links() }}
            </div>
        </div>
    </div>
</div>
@endsection