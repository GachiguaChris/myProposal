@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Admin Dashboard</h2>
</div>

@if(session('message'))
<div class="alert alert-success">{{ session('message') }}</div>
@endif

<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Title</th>
            <th>Org</th>
            <th>Stage</th>
            <th>Status</th>
            <th>Submitted By</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($proposals as $proposal)
        <tr>
            <td>{{ $proposal->title }}</td>
            <td>{{ $proposal->organization_name }}</td>
            <td>Stage {{ $proposal->stage }}</td>
            <td>
                @if($proposal->status === 'accepted')
                <span class="badge bg-success">Accepted</span>
                @elseif($proposal->status === 'rejected')
                <span class="badge bg-danger">Rejected</span>
                @else
                <span class="badge bg-warning">Pending</span>
                @endif
            </td>
            <td>{{ $proposal->user?->name ?? 'Unknown User' }}
            </td>
            <td>
                <a href="{{ $proposal->id}}" class="btn btn-sm btn-primary">Review</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection