@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-primary">
                <i class="bi bi-folder-fill"></i> Project Categories
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm mt-2">
                <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
            </a>
        </div>
        <a href="{{ route('admin.project-categories.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> New Category
        </a>
    </div>

    <!-- Flash Message -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <table class="table table-hover table-striped">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Budget</th>
                <th>Created</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $cat)
                <tr>
                    <td>{{ $cat->name }}</td>
                    <td><span class="badge bg-success">${{ number_format($cat->budget, 2) }}</span></td>
                    <td>{{ $cat->created_at->format('M d, Y') }}</td>
                    <td class="text-end">
                        {{-- Uncomment this if you want the edit button --}}
                        {{-- <a href="{{ route('admin.project-categories.edit', $cat) }}" class="btn btn-sm btn-outline-primary me-1">
                            <i class="bi bi-pencil"></i> Edit
                        </a> --}}
                        <form method="POST" action="{{ route('admin.project-categories.destroy', $cat) }}" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this category?')">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">No categories created yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
