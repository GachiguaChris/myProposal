@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 text-primary"><i class="bi bi-journal-text me-2"></i>All Proposals</h2>
        <a href="{{ route('proposals.create') }}" class="btn btn-success me-2">
            <i class="bi bi-plus-circle me-1"></i> New Proposal
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('proposals.index') }}">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Search by title, organization or email..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="status">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-grid">
                        <button class="btn btn-primary" type="submit">Apply Filters</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($proposals->isNotEmpty())
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0 text-nowrap">
                    <thead class="table-light">
                        <tr>
                            @php
                                $sort = request('sort');
                                $direction = request('direction');
                            @endphp
                            <th scope="col" class="sortable {{ $sort === 'id' ? 'table-primary' : '' }}" data-sort="id">
                                ID <i class="bi bi-arrow-down-up"></i>
                            </th>
                            <th scope="col" class="sortable {{ $sort === 'title' ? 'table-primary' : '' }}" data-sort="title">
                                Title <i class="bi bi-arrow-down-up"></i>
                            </th>
                            <th scope="col">Organization</th>
                            <th scope="col">Submitted By</th>
                            <th scope="col">Email</th>
                            <th scope="col" class="sortable {{ $sort === 'status' ? 'table-primary' : '' }}" data-sort="status">
                                Status <i class="bi bi-arrow-down-up"></i>
                            </th>
                            <th scope="col" class="sortable {{ $sort === 'created_at' ? 'table-primary' : '' }}" data-sort="created_at">
                                Submitted <i class="bi bi-arrow-down-up"></i>
                            </th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($proposals as $proposal)
                            <tr>
                                <td class="fw-bold">#{{ $proposal->id }}</td>
                                <td>
                                    <a href="{{ route('proposals.show', $proposal) }}" class="text-decoration-none">
                                        {{ Str::limit(e($proposal->title), 40) }}
                                    </a>
                                </td>
                                <td>{{ e($proposal->organization_name) }}</td>
                                <td>{{ e($proposal->submitted_by) }}</td>
                                <td>
                                    <a href="mailto:{{ e($proposal->email) }}" class="text-decoration-none">
                                        {{ e($proposal->email) }}
                                    </a>
                                </td>
                                <td>
                                    @php
                                        $badgeClass = 'secondary';
                                        $icon = 'bi-question-circle';

                                        switch ($proposal->status) {
                                            case 'approved':
                                                $badgeClass = 'success';
                                                $icon = 'bi-check-circle';
                                                break;
                                            case 'pending':
                                                $badgeClass = 'warning';
                                                $icon = 'bi-clock';
                                                break;
                                            case 'rejected':
                                                $badgeClass = 'danger';
                                                $icon = 'bi-x-circle';
                                                break;
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $badgeClass }}">
                                        <i class="bi {{ $icon }} me-1"></i>{{ ucfirst(e($proposal->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <span title="{{ $proposal->created_at->format('M d, Y h:i A') }}">
                                        {{ $proposal->created_at->diffForHumans() }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('proposals.show', $proposal) }}" class="btn btn-sm btn-outline-info me-1" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('proposals.edit', $proposal) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('proposals.destroy', $proposal) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this proposal permanently?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
     

            <!-- @if($proposals->hasPages())
                <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Showing {{ $proposals->firstItem() }} - {{ $proposals->lastItem() }} of {{ $proposals->total() }} results
                    </div>
                    <div>
                        {{ $proposals->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-folder-x text-muted" style="font-size: 3rem;"></i>
                </div> -->

                @if(request()->has('search') || request()->has('status') || request()->has('sort'))
                    <h4 class="text-muted">No proposals match your filters</h4>
                    <p class="text-muted">Try adjusting your search or filter criteria</p>
                    <a href="{{ route('proposals.index') }}" class="btn btn-outline-primary mt-3">
                        <i class="bi bi-arrow-repeat me-1"></i> Reset Filters
                    </a>
                @else
                    <h4 class="text-muted">You haven’t submitted any proposals yet</h4>
                    <p class="text-muted">Start by creating your first proposal. It’s quick and easy!</p>
                    <a href="{{ route('proposals.create') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-plus-circle me-1"></i> Create Proposal
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            const toastEl = document.getElementById('liveToast');
            if (toastEl) {
                const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
                toast.show();
            }
        @endif

        document.querySelectorAll('.sortable').forEach(header => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', function() {
                const sortField = this.dataset.sort;
                const currentUrl = new URL(window.location.href);
                let sortDirection = 'asc';

                if(currentUrl.searchParams.get('sort') === sortField) {
                    sortDirection = currentUrl.searchParams.get('direction') === 'asc' ? 'desc' : 'asc';
                }

                currentUrl.searchParams.set('sort', sortField);
                currentUrl.searchParams.set('direction', sortDirection);
                window.location.href = currentUrl.toString();
            });
        });
    });

     document.querySelectorAll('.delete-proposal-form').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault(); // stop default submission

      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then((result) => {
        if (result.isConfirmed) {
          // Show success alert first (optional)
          Swal.fire({
            title: "Deleted!",
            text: "Your file has been deleted.",
            icon: "success",
            timer: 1000,
            showConfirmButton: false
          });

          // Submit the form after a brief delay
          setTimeout(() => {
            form.submit();
          }, 1100);
        }
      });
    });
  });
</script>
@endpush

@if(session('success'))
<!-- Bootstrap Toast -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1080;">
    <div id="liveToast" class="toast align-items-center text-bg-success border-0 shadow" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
@endif
