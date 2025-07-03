@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">👤 Manage Users</h2>

    <div class="container py-4">
    <!-- Back to Dashboard -->
    <div class="mb-3">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <!-- Feedback Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            ❌ {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        @foreach ($users as $user)
            <div class="col-md-6 col-12 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">{{ $user->name }}</h5>
                            <small class="text-muted d-block">{{ $user->email }}</small>
                            <small class="text-muted d-block">
                                📞 <a href="tel:{{ $user->phone }}">{{ $user->phone }}</a>
                            </small>
                        </div>
                        <div class="text-end">
                            @if (!$user->is_admin)
                                <form method="POST" action="{{ route('admin.makeAdmin', $user) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Promote to Admin">
                                        <i class="bi bi-person-plus-fill"></i> Make Admin
                                    </button>
                                </form>
                            @else
                                <span class="badge bg-success" data-bs-toggle="tooltip" title="This user is an admin">
                                    <i class="bi bi-shield-lock"></i> Admin
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <hr class="my-5">

    <h3 class="mb-4">➕ Create New User</h3>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.createUser') }}">
                @csrf

                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                    <input type="text" name="phone" class="form-control" placeholder="Phone Number" required>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_admin" id="is_admin">
                    <label class="form-check-label" for="is_admin">Make Admin</label>
                </div>

                <button type="submit" class="btn btn-success w-100">Create User</button>
            </form>
        </div>
    </div>
</div>

<!-- Tooltips -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (el) {
            return new bootstrap.Tooltip(el)
        });
    });
</script>
@endsection
