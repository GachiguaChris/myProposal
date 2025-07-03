@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Welcome Message -->
            <div class="alert alert-primary d-flex align-items-center" role="alert">
                <i class="bi bi-person-check-fill fs-4 me-2"></i>
                <div>
                    Welcome back, <strong>{{ Auth::user()->name }}</strong>! You're successfully logged in.
                </div>
            </div>

            <!-- Proposal Statistics -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title text-primary">Total Proposals</h5>
                            <h2 class="display-4">{{ $totalProposals }}</h2>
                            <p class="text-muted">All your submitted proposals</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title text-success">Accepted</h5>
                            <h2 class="display-4">{{ $acceptedProposals }}</h2>
                            <p class="text-muted">{{ $acceptanceRate }}% acceptance rate</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title text-warning">Pending</h5>
                            <h2 class="display-4">{{ $pendingProposals }}</h2>
                            <p class="text-muted">Awaiting review</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title text-danger">Rejected</h5>
                            <h2 class="display-4">{{ $rejectedProposals }}</h2>
                            <p class="text-muted">Not approved</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Access Cards -->
            <div class="row row-cols-1 row-cols-md-3 g-4 mb-4">
                <div class="col">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-file-earmark-text fs-1 text-primary mb-3"></i>
                            <h5 class="card-title">My Proposals</h5>
                            <p class="card-text text-muted">View and manage your submitted proposals easily.</p>
                            <a href="{{ route('proposals.index') }}" class="btn btn-primary btn-sm rounded-pill">Go to Proposals</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-plus-square fs-1 text-success mb-3"></i>
                            <h5 class="card-title">Submit New</h5>
                            <p class="card-text text-muted">Have an idea? Submit a new proposal in just a few steps.</p>
                            <a href="{{ route('proposals.create') }}" class="btn btn-success btn-sm rounded-pill">New Proposal</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-download fs-1 text-info mb-3"></i>
                            <h5 class="card-title">Export Data</h5>
                            <p class="card-text text-muted">Download your proposal data in CSV format.</p>
                            <a href="{{ route('proposals.export') }}" class="btn btn-info btn-sm rounded-pill text-white">Export to CSV</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Proposals -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Proposals</h5>
                        <a href="{{ route('proposals.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Organization</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentProposals as $proposal)
                                <tr>
                                    <td>{{ \Illuminate\Support\Str::limit($proposal->title, 30) }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($proposal->organization_name, 20) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $proposal->status == 'accepted' ? 'success' : ($proposal->status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($proposal->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $proposal->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('proposals.show', $proposal->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3">No proposals found. <a href="{{ route('proposals.create') }}">Create your first proposal</a>.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Proposal Activity Chart -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Your Proposal Activity ({{ date('Y') }})</h5>
                </div>
                <div class="card-body">
                    <canvas id="proposalActivityChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Proposal Activity Chart
    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const monthlyData = @json($monthlyData);
    
    const proposalActivityChart = new Chart(
        document.getElementById('proposalActivityChart'),
        {
            type: 'bar',
            data: {
                labels: monthNames,
                datasets: [{
                    label: 'Proposals Submitted',
                    data: monthlyData,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgb(54, 162, 235)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        }
    );
</script>
@endpush
@endsection