@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1>Create Notification</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Notifications
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.notifications.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="4" required>{{ old('message') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="info" {{ old('type', 'info') == 'info' ? 'selected' : '' }}>Info</option>
                            <option value="success" {{ old('type') == 'success' ? 'selected' : '' }}>Success</option>
                            <option value="warning" {{ old('type') == 'warning' ? 'selected' : '' }}>Warning</option>
                            <option value="danger" {{ old('type') == 'danger' ? 'selected' : '' }}>Danger</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="user_id" class="form-label">User <span class="text-danger">*</span></label>
                        <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required onchange="loadUserProposals(this.value)">
                            <option value="">-- Select User --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="proposal_id" class="form-label">Related Proposal</label>
                        <select class="form-select @error('proposal_id') is-invalid @enderror" id="proposal_id" name="proposal_id">
                            <option value="">-- None --</option>
                            @foreach($proposals ?? [] as $proposal)
                                <option value="{{ $proposal->id }}" {{ old('proposal_id') == $proposal->id ? 'selected' : '' }} data-user="{{ $proposal->user_id }}">
                                    {{ $proposal->title ?? 'Proposal #' . $proposal->id }}
                                </option>
                            @endforeach
                        </select>
                        @error('proposal_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label for="client_id" class="form-label">Related Client</label>
                        <select class="form-select @error('client_id') is-invalid @enderror" id="client_id" name="client_id">
                            <option value="">-- None --</option>
                            @foreach($clients ?? [] as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label for="task_id" class="form-label">Related Task</label>
                        <select class="form-select @error('task_id') is-invalid @enderror" id="task_id" name="task_id">
                            <option value="">-- None --</option>
                            @foreach($tasks ?? [] as $task)
                                <option value="{{ $task->id }}" {{ old('task_id') == $task->id ? 'selected' : '' }}>
                                    {{ $task->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('task_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-bell"></i> Create Notification
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function loadUserProposals(userId) {
        if (!userId) return;
        
        // Hide all proposal options except the "None" option
        const proposalSelect = document.getElementById('proposal_id');
        for (let i = 0; i < proposalSelect.options.length; i++) {
            const option = proposalSelect.options[i];
            if (i === 0) continue; // Skip the "None" option
            
            const proposalUserId = option.getAttribute('data-user');
            if (proposalUserId === userId) {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        }
        
        // Reset selection to "None"
        proposalSelect.value = '';
    }
    
    // Initialize on page load if a user is already selected
    document.addEventListener('DOMContentLoaded', function() {
        const userSelect = document.getElementById('user_id');
        if (userSelect.value) {
            loadUserProposals(userSelect.value);
        }
    });
</script>
@endpush

@endsection