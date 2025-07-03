<!-- resources/views/admin/reports/summary.blade.php -->
@extends('layouts.admin')

@section('content')

<div class="row mb-4">
    <div class="col-md-2">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Proposals</h5>
                <p class="card-text display-4">{{ $total ?? 0 }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Accepted</h5>
                <p class="card-text display-4">{{ $approved ?? 0 }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-white bg-danger mb-3">
            <div class="card-body">
                <h5 class="card-title">Rejected</h5>
                <p class="card-text display-4">{{ $rejected ?? 0 }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-white bg-warning mb-3">
            <div class="card-body">
                <h5 class="card-title">Pending</h5>
                <p class="card-text display-4 text-dark">{{ $pending ?? 0 }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-white bg-secondary mb-3">
            <div class="card-body">
                <h5 class="card-title">Review Requested</h5>
                <p class="card-text display-4">{{ $revised ?? 0 }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Acceptance Rate</h5>
                <p class="card-text display-4">
                    @php
                        $totalCount = $total ?? 0;
                        $approvedCount = $approved ?? 0;
                    @endphp
                    @if($totalCount > 0)
                        {{ round(($approvedCount / $totalCount) * 100, 1) }}%
                    @else
                        0%
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>


<!-- Filters and Export -->
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
            <form method="GET" action="{{ route('admin.reports.summary') }}" class="row g-3 flex-grow-1">
                <div class="col-md-3">
                    <label for="date_from" class="form-label">From</label>
                    <input type="date" id="date_from" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label">To</label>
                    <input type="date" id="date_to" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3">
                    <label for="category" class="form-label">Category</label>
                    <select id="category" name="category" class="form-select">
                        <option value="">All Categories</option>
                        @if(isset($categories) && count($categories) > 0)
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="review_requested" {{ request('status') == 'review_requested' ? 'selected' : '' }}>Review Requested</option>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="{{ route('admin.reports.summary') }}" class="btn btn-secondary">Reset</a>
                    <button type="button" class="btn btn-success" onclick="exportReport()">
                        <i class="fas fa-file-export"></i> Export
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Metrics Cards -->

<!-- Recent Proposals Table -->
<div class="card">
    <div class="card-header">Recent Proposals</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Submitted By</th>
                        <th>Budget</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($recentProposals) && count($recentProposals) > 0)
                        @foreach($recentProposals as $proposal)
                        <tr>
                            <td>{{ $proposal->title }}</td>
                            <td>{{ $proposal->category->name ?? 'N/A' }}</td>
                            <td>{{ $proposal->submittedByUser->name ?? $proposal->submitted_by }}</td>
                            <td>${{ number_format($proposal->budget, 2) }}</td>
                            <td>
                                @php
                                    $statusClass = 'secondary';
                                    if ($proposal->status == 'accepted') $statusClass = 'success';
                                    elseif ($proposal->status == 'rejected') $statusClass = 'danger';
                                    elseif ($proposal->status == 'pending') $statusClass = 'warning';
                                    elseif ($proposal->status == 'review_requested') $statusClass = 'info';
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $proposal->status)) }}</span>
                            </td>
                            <td>{{ $proposal->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.proposals.review', $proposal->id) }}" class="btn btn-sm btn-primary">View</a>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center">No proposals found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <!-- Proposals Over Time -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Proposals Over Time</span>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="trendToggle" checked>
                    <label class="form-check-label" for="trendToggle">Show Trend Line</label>
                </div>
            </div>
            <div class="card-body">
                <canvas id="proposalsOverTimeChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Status Distribution -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">Status Distribution</div>
            <div class="card-body position-relative">
                <canvas id="statusPieChart"></canvas>
            </div>
        </div>
    </div>
</div>



<!-- Approval Rates by Category -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">Acceptance Rates by Category</div>
            <div class="card-body">
                <canvas id="approvalRatesChart" height="80"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Budget Usage by Category -->



@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status Distribution Pie Chart
    const statusCtx = document.getElementById('statusPieChart').getContext('2d');
    const statusData = {
        labels: ['Accepted', 'Rejected', 'Pending', 'Review Requested'],
        datasets: [{
            data: [
                {{ $approved ?? 0 }}, 
                {{ $rejected ?? 0 }}, 
                {{ $pending ?? 0 }}, 
                {{ $revised ?? 0 }}
            ],
            backgroundColor: [
                'rgba(40, 167, 69, 0.8)',
                'rgba(220, 53, 69, 0.8)',
                'rgba(255, 193, 7, 0.8)',
                'rgba(108, 117, 125, 0.8)'
            ]
        }]
    };
    
    new Chart(statusCtx, {
        type: 'pie',
        data: statusData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
    
    // Approval Rates by Category Chart
    const approvalCtx = document.getElementById('approvalRatesChart').getContext('2d');
    const categoryData = @json($categoryData ?? ['labels' => [], 'counts' => [], 'approvalRates' => []]);
    
    new Chart(approvalCtx, {
        type: 'bar',
        data: {
            labels: categoryData.labels ?? [],
            datasets: [
                {
                    label: 'Acceptance Rate (%)',
                    data: categoryData.approvalRates ?? [],
                    backgroundColor: 'rgba(40, 167, 69, 0.7)'
                },
                {
                    label: 'Total Proposals',
                    data: categoryData.counts ?? [],
                    backgroundColor: 'rgba(0, 123, 255, 0.7)',
                    type: 'line',
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Acceptance Rate (%)'
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false
                    },
                    title: {
                        display: true,
                        text: 'Number of Proposals'
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const datasetLabel = context.dataset.label || '';
                            const value = context.raw || 0;
                            if (datasetLabel === 'Acceptance Rate (%)') {
                                return `${datasetLabel}: ${value}%`;
                            } else {
                                return `${datasetLabel}: ${value}`;
                            }
                        }
                    }
                }
            }
        }
    });
    
    // Proposals Over Time Chart
    const timeCtx = document.getElementById('proposalsOverTimeChart').getContext('2d');
    const timeData = @json($timeData ?? ['labels' => [], 'values' => [], 'trend' => []]);
    
    const timeChart = new Chart(timeCtx, {
        type: 'line',
        data: {
            labels: timeData.labels ?? [],
            datasets: [
                {
                    label: 'Proposals',
                    data: timeData.values ?? [],
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1,
                    fill: true
                },
                {
                    label: 'Trend',
                    data: timeData.trend ?? [],
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderDash: [5, 5],
                    fill: false,
                    pointRadius: 0
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
    
    // Toggle trend line
    document.getElementById('trendToggle').addEventListener('change', function() {
        timeChart.data.datasets[1].hidden = !this.checked;
        timeChart.update();
    });
    
    // Export function
    window.exportReport = function() {
        // Create a form to submit the current filters
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = '{{ route("admin.reports.summary") }}';
        form.target = '_blank';
        
        // Add export parameter
        const exportInput = document.createElement('input');
        exportInput.type = 'hidden';
        exportInput.name = 'export';
        exportInput.value = '1';
        form.appendChild(exportInput);
        
        // Add current filters
        const dateFrom = document.getElementById('date_from').value;
        if (dateFrom) {
            const dateFromInput = document.createElement('input');
            dateFromInput.type = 'hidden';
            dateFromInput.name = 'date_from';
            dateFromInput.value = dateFrom;
            form.appendChild(dateFromInput);
        }
        
        const dateTo = document.getElementById('date_to').value;
        if (dateTo) {
            const dateToInput = document.createElement('input');
            dateToInput.type = 'hidden';
            dateToInput.name = 'date_to';
            dateToInput.value = dateTo;
            form.appendChild(dateToInput);
        }
        
        const category = document.getElementById('category').value;
        if (category) {
            const categoryInput = document.createElement('input');
            categoryInput.type = 'hidden';
            categoryInput.name = 'category';
            categoryInput.value = category;
            form.appendChild(categoryInput);
        }
        
        const status = document.getElementById('status').value;
        if (status) {
            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = status;
            form.appendChild(statusInput);
        }
        
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    };
});
</script>
@endpush
@endsection