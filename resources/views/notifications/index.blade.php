@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            {{-- Header --}}
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h2 class="mb-1 fw-bold text-dark">Notifications</h2>
                    <p class="text-muted mb-0">Stay updated with your latest activities</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary rounded-pill">{{ $notifications->total() }} Total</span>
                </div>
            </div>

            {{-- Filters --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body py-3">
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <span class="text-muted me-2 fw-medium">Filter by:</span>
                        <a href="{{ route('notifications.index') }}" 
                           class="btn btn-outline-secondary btn-sm rounded-pill px-3 {{ !request('filter') ? 'active' : '' }}">
                            <i class="fas fa-list me-1"></i>All
                        </a>
                        <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" 
                           class="btn btn-outline-primary btn-sm rounded-pill px-3 {{ request('filter') === 'unread' ? 'active' : '' }}">
                            <i class="fas fa-envelope me-1"></i>Unread
                        </a>
                        <a href="{{ route('notifications.index', ['filter' => 'read']) }}" 
                           class="btn btn-outline-success btn-sm rounded-pill px-3 {{ request('filter') === 'read' ? 'active' : '' }}">
                            <i class="fas fa-envelope-open me-1"></i>Read
                        </a>
                    </div>
                </div>
            </div>

            {{-- Notification Cards --}}
            <div class="notifications-container">
                @forelse($notifications as $notification)
                    <div class="card border-0 shadow-sm mb-3 notification-card {{ !$notification->read ? 'unread-notification' : '' }}" 
                         id="notification-{{ $notification->id }}">
                        <div class="card-header border-0 bg-white d-flex justify-content-between align-items-center py-3">
                            <div class="d-flex align-items-center">
                                {{-- Notification Icon --}}
                                <div class="notification-icon me-3">
                                    @switch($notification->type)
                                        @case('success')
                                            <div class="icon-circle bg-success bg-opacity-10">
                                                <i class="fas fa-check-circle text-success"></i>
                                            </div>
                                            @break
                                        @case('warning')
                                            <div class="icon-circle bg-warning bg-opacity-10">
                                                <i class="fas fa-exclamation-triangle text-warning"></i>
                                            </div>
                                            @break
                                        @case('danger')
                                            <div class="icon-circle bg-danger bg-opacity-10">
                                                <i class="fas fa-times-circle text-danger"></i>
                                            </div>
                                            @break
                                        @case('info')
                                            <div class="icon-circle bg-info bg-opacity-10">
                                                <i class="fas fa-info-circle text-info"></i>
                                            </div>
                                            @break
                                        @default
                                            <div class="icon-circle bg-primary bg-opacity-10">
                                                <i class="fas fa-bell text-primary"></i>
                                            </div>
                                    @endswitch
                                </div>
                                
                                {{-- Notification Content --}}
                                <div>
                                    <h6 class="mb-1 fw-semibold text-dark">{{ $notification->title }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $notification->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="d-flex align-items-center gap-2">
                                @if(!$notification->read)
                                    <span class="badge bg-primary rounded-pill">New</span>
                                @endif
                                
                                <button class="btn btn-outline-primary btn-sm rounded-pill toggle-message" 
                                        data-id="{{ $notification->id }}">
                                    <i class="fas fa-eye me-1"></i>
                                    <span class="btn-text">View</span>
                                </button>
                                
                                @if(!$notification->read)
                                    <button class="btn btn-success btn-sm rounded-pill mark-as-read" 
                                            data-id="{{ $notification->id }}">
                                        <i class="fas fa-check me-1"></i>
                                        Mark Read
                                    </button>
                                @endif
                            </div>
                        </div>
                        
                        {{-- Message Content --}}
                        <div class="card-body d-none pt-0" id="message-{{ $notification->id }}">
                            <div class="notification-message">
                                <div class="message-content p-3 bg-light rounded-3 border-start border-4 border-{{ $notification->type }}">
                                    {!! nl2br(e($notification->message)) !!}
                                </div>
                                
                                <div class="message-meta mt-3 pt-3 border-top">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-tag text-muted me-2"></i>
                                                <span class="text-muted me-2">Status:</span>
                                                <span class="badge notification-status {{ $notification->read ? 'bg-secondary' : 'bg-primary' }} rounded-pill">
                                                    {{ $notification->read ? 'Read' : 'Unread' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-calendar text-muted me-2"></i>
                                                <span class="text-muted me-2">Created:</span>
                                                <span class="text-dark">{{ $notification->created_at->format('M d, Y H:i') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <div class="empty-state">
                            <i class="fas fa-bell-slash text-muted mb-3" style="font-size: 3rem;"></i>
                            <h5 class="text-muted mb-2">No notifications found</h5>
                            <p class="text-muted">You're all caught up! New notifications will appear here.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($notifications->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    <div class="pagination-wrapper">
                        {{ $notifications->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Custom Styles --}}
<style>
.notification-card {
    transition: all 0.3s ease;
    border-left: 4px solid transparent !important;
}

.notification-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.unread-notification {
    border-left-color: #0d6efd !important;
    background: linear-gradient(135deg, rgba(13, 110, 253, 0.02) 0%, rgba(13, 110, 253, 0.05) 100%);
}

.icon-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}

.notification-message {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.message-content {
    line-height: 1.6;
}

.message-meta {
    font-size: 0.9rem;
}

.empty-state i {
    opacity: 0.5;
}

.btn.active {
    background-color: var(--bs-primary) !important;
    color: white !important;
    border-color: var(--bs-primary) !important;
}

.btn-outline-secondary.active {
    background-color: var(--bs-secondary) !important;
    border-color: var(--bs-secondary) !important;
}

.btn-outline-success.active {
    background-color: var(--bs-success) !important;
    border-color: var(--bs-success) !important;
}

.pagination-wrapper .page-link {
    border-radius: 8px;
    margin: 0 2px;
    border: none;
    color: #6c757d;
}

.pagination-wrapper .page-item.active .page-link {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.mark-as-read:hover {
    transform: scale(1.05);
}

.toggle-message:hover {
    transform: scale(1.05);
}

.notification-status {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}
</style>
@endsection
<audio id="notificationSound" src="{{ asset('sounds/notify.mp3') }}" preload="auto"></audio>
@push('scripts')
<script>
document.querySelectorAll('.toggle-message').forEach(button => {
    button.addEventListener('click', function () {
        const id = this.getAttribute('data-id');
        const message = document.getElementById(`message-${id}`);
        const icon = this.querySelector('i');
        const text = this.querySelector('.btn-text');
        
        message.classList.toggle('d-none');
        
        if (message.classList.contains('d-none')) {
            icon.className = 'fas fa-eye me-1';
            text.textContent = 'View';
            this.classList.remove('btn-primary');
            this.classList.add('btn-outline-primary');
        } else {
            icon.className = 'fas fa-eye-slash me-1';
            text.textContent = 'Hide';
            this.classList.remove('btn-outline-primary');
            this.classList.add('btn-primary');
        }
    });
});

document.querySelectorAll('.mark-as-read').forEach(button => {
    button.addEventListener('click', async function () {
        const id = this.getAttribute('data-id');
        
        // Add loading state
        const originalHTML = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Marking...';
        this.disabled = true;
        
        try {
            const response = await fetch(`/notifications/${id}/mark-as-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            });
            
            if (response.ok) {
                const card = document.getElementById(`notification-${id}`);
                
                // Remove the button with animation
                this.style.transform = 'scale(0)';
                setTimeout(() => {
                    this.remove();
                }, 200);
                
                // Update the status badge
                const badge = card.querySelector('.notification-status.bg-primary');
                if (badge) {
                    badge.classList.remove('bg-primary');
                    badge.classList.add('bg-secondary');
                    badge.textContent = 'Read';
                }
                
                // Remove "New" badge
                const newBadge = card.querySelector('.badge.bg-primary.rounded-pill');
                if (newBadge && newBadge.textContent === 'New') {
                    newBadge.style.transform = 'scale(0)';
                    setTimeout(() => {
                        newBadge.remove();
                    }, 200);
                }
                
                // Remove unread styling
                card.classList.remove('unread-notification');
                
            } else {
                // Restore button on error
                this.innerHTML = originalHTML;
                this.disabled = false;
                alert('Failed to mark notification as read. Please try again.');
            }
        } catch (error) {
            // Restore button on error
            this.innerHTML = originalHTML;
            this.disabled = false;
            alert('An error occurred. Please try again.');
        }
    });
});let lastNotificationId = 0;

    function checkForNewNotifications() {
        fetch("{{ route('notifications.index') }}?ajax=1")
            .then(response => response.json())
            .then(data => {
                if (data.latest_id > lastNotificationId) {
                    document.getElementById('notificationSound').play();
                    lastNotificationId = data.latest_id;

                    // Optional: Vibrate for mobile devices
                    if ("vibrate" in navigator) {
                        navigator.vibrate([100, 50, 100]);
                    }

                    // Optional: Update badge count
                    document.querySelector('.nav-link .badge').innerText = data.unread_count;
                }
            });
    }

    setInterval(checkForNewNotifications, 15000); // every 15 seconds
</script>
@endpush