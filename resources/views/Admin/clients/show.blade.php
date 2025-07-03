@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1>Client Details</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Clients
            </a>
            <a href="{{ route('admin.clients.edit', $client->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Edit Client
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Client Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 30%">Name:</th>
                            <td>{{ $client->name }}</td>
                        </tr>
                        <tr>
                            <th>Company:</th>
                            <td>{{ $client->company ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $client->email ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $client->phone ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge bg-{{ $client->status == 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($client->status) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Address Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 30%">Address:</th>
                            <td>{{ $client->address ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>City:</th>
                            <td>{{ $client->city ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>State/Province:</th>
                            <td>{{ $client->state ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>ZIP/Postal Code:</th>
                            <td>{{ $client->zip ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Country:</th>
                            <td>{{ $client->country ?: 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Notes</h5>
        </div>
        <div class="card-body">
            {{ $client->notes ?: 'No notes available.' }}
        </div>
    </div>
    
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Client Proposals</h5>
            <a href="{{ route('proposals.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle"></i> New Proposal
            </a>
        </div>
        <div class="card-body">
            @if($proposals->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($proposals as $proposal)
                            <tr>
                                <td>{{ $proposal->id }}</td>
                                <td>{{ $proposal->title }}</td>
                                <td>
                                    <span class="badge bg-{{ $proposal->status == 'approved' ? 'success' : ($proposal->status == 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($proposal->status) }}
                                    </span>
                                </td>
                                <td>{{ $proposal->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.proposals.review', $proposal->id) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $proposals->links() }}
                </div>
            @else
                <p class="text-center">No proposals found for this client.</p>
            @endif
        </div>
    </div>
</div>
@endsection