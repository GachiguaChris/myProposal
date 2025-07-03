<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Top Clients</h5>
    </div>
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            @forelse($topClients as $client)
                <a href="{{ route('admin.clients.show', $client->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">{{ $client->name }}</h6>
                        <small class="text-muted">{{ $client->company }}</small>
                    </div>
                    <span class="badge bg-primary rounded-pill">{{ $client->proposals_count }} proposals</span>
                </a>
            @empty
                <div class="list-group-item">No clients found</div>
            @endforelse
        </div>
    </div>
</div>