@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 fw-bold text-primary"><i class="bi bi-gear-fill"></i> Admin Settings</h2>

    <div class="row g-4">
        <!-- Manage Users -->
        <div class="col-md-6">
            <a href="{{ route('admin.users') }}" class="text-decoration-none">
                <div class="card shadow-sm border-0 h-100 hover-shadow transition">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <div class="bg-primary text-white rounded-circle p-3">
                                <i class="bi bi-people-fill fs-3"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="card-title mb-1 text-dark">Manage Users</h5>
                            <p class="card-text text-muted small">Promote, demote, or create new users.</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Manage Categories -->
        <div class="col-md-6">
            <a href="{{ route('admin.project-categories.index') }}" class="text-decoration-none">
                <div class="card shadow-sm border-0 h-100 hover-shadow transition">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <div class="bg-warning text-white rounded-circle p-3">
                                <i class="bi bi-tags-fill fs-3"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="card-title mb-1 text-dark">Manage Categories</h5>
                            <p class="card-text text-muted small">Organize proposals into project categories.</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<style>
    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
        transition: all 0.3s ease-in-out;
    }

    .transition {
        transition: all 0.3s ease-in-out;
    }
</style>
@endsection
