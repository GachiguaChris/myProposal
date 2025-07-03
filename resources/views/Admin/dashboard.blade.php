@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1>Dashboard</h1>
        </div>
          <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Access</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="{{ route('admin.proposals.index') }}" class="text-decoration-none">
                                <div class="text-center">
                                    <div class="fs-1 text-primary mb-2">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </div>
                                    <h6>Proposals</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="{{ route('admin.clients.index') }}" class="text-decoration-none">
                                <div class="text-center">
                                    <div class="fs-1 text-info mb-2">
                                        <i class="bi bi-briefcase"></i>
                                    </div>
                                    <h6>Clients</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="{{ route('admin.templates.index') }}" class="text-decoration-none">
                                <div class="text-center">
                                    <div class="fs-1 text-success mb-2">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </div>
                                    <h6>Templates</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="{{ route('admin.tasks.index') }}" class="text-decoration-none">
                                <div class="text-center">
                                    <div class="fs-1 text-warning mb-2">
                                        <i class="bi bi-calendar-check"></i>
                                    </div>
                                    <h6>Tasks</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="{{ route('admin.documents.index') }}" class="text-decoration-none">
                                <div class="text-center">
                                    <div class="fs-1 text-danger mb-2">
                                        <i class="bi bi-file-earmark"></i>
                                    </div>
                                    <h6>Documents</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="{{ route('admin.notifications.index') }}" class="text-decoration-none">
                                <div class="text-center">
                                    <div class="fs-1 text-secondary mb-2">
                                        <i class="bi bi-bell"></i>
                                    </div>
                                    <h6>Notifications</h6>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="col-auto">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary active" id="thisMonth">This Month</button>
                <button type="button" class="btn btn-outline-primary" id="lastMonth">Last Month</button>
                <button type="button" class="btn btn-outline-primary" id="thisYear">This Year</button>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Proposals</h5>
                    <h2 class="display-4">{{ $proposalStats['total'] }}</h2>
                    <p class="mb-0">
                        <span class="badge bg-light text-dark">
                            <i class="bi bi-arrow-up"></i> {{ $proposalStats['growth'] }}%
                        </span>
                        vs previous period
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Accepted</h5>
                    <h2 class="display-4">{{ $proposalStats['approved'] }}</h2>
                    <p class="mb-0">
                        <span class="badge bg-light text-dark">
                            {{ $proposalStats['approvalRate'] }}%
                        </span>
                        approval rate
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h5 class="card-title">Pending</h5>
                    <h2 class="display-4">{{ $proposalStats['pending'] }}</h2>
                    <p class="mb-0">
                        <span class="badge bg-light text-dark">
                            {{ $proposalStats['pendingRate'] }}%
                        </span>
                        of total
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Rejected</h5>
                    <h2 class="display-4">{{ $proposalStats['rejected'] }}</h2>
                    <p class="mb-0">
                        <span class="badge bg-light text-dark">
                            {{ $proposalStats['rejectionRate'] }}%
                        </span>
                        rejection rate
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Monthly Proposals Chart -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Proposal Trends</h5>
                    <a href="{{ route('admin.reports.summary') }}" class="btn btn-sm btn-outline-primary">Detailed Reports</a>
                </div>
                <div class="card-body">
                    <canvas id="proposalTrendsChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Task Stats -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Task Status</h5>
                    <a href="{{ route('admin.tasks.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <canvas id="taskStatsChart" height="260"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Module Quick Access -->
  

  
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Proposal Trends Chart
    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const proposalData = @json($monthlyProposals);
    
    const proposalTrendsChart = new Chart(
        document.getElementById('proposalTrendsChart'),
        {
            type: 'line',
            data: {
                labels: monthNames,
                datasets: [
                    {
                        label: 'Total',
                        data: proposalData.total,
                        borderColor: 'rgb(54, 162, 235)',
                        backgroundColor: 'rgba(54, 162, 235, 0.1)',
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Approved',
                        data: proposalData.approved,
                        borderColor: 'rgb(40, 167, 69)',
                        backgroundColor: 'transparent',
                        tension: 0.3
                    },
                    {
                        label: 'Rejected',
                        data: proposalData.rejected,
                        borderColor: 'rgb(220, 53, 69)',
                        backgroundColor: 'transparent',
                        tension: 0.3
                    }
                ]
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
    
    // Task Stats Chart
    const taskStatsChart = new Chart(
        document.getElementById('taskStatsChart'),
        {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'In Progress', 'Pending', 'Cancelled'],
                datasets: [{
                    data: [
                        {{ $taskStats['completed'] }}, 
                        {{ $taskStats['in_progress'] }}, 
                        {{ $taskStats['pending'] }}, 
                        {{ $taskStats['cancelled'] }}
                    ],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.7)',
                        'rgba(0, 123, 255, 0.7)',
                        'rgba(255, 193, 7, 0.7)',
                        'rgba(220, 53, 69, 0.7)'
                    ],
                    borderColor: [
                        'rgb(40, 167, 69)',
                        'rgb(0, 123, 255)',
                        'rgb(255, 193, 7)',
                        'rgb(220, 53, 69)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        }
    );
    
    // Period filter functionality
    document.getElementById('thisMonth').addEventListener('click', function() {
        updateCharts('thisMonth');
        setActiveButton(this);
    });
    
    document.getElementById('lastMonth').addEventListener('click', function() {
        updateCharts('lastMonth');
        setActiveButton(this);
    });
    
    document.getElementById('thisYear').addEventListener('click', function() {
        updateCharts('thisYear');
        setActiveButton(this);
    });
    
    function updateCharts(period) {
        // This would typically fetch data via AJAX
        // For demo purposes, we'll just simulate changes
        
        // Update proposal trends chart
        if (period === 'thisMonth') {
            proposalTrendsChart.data.datasets[0].data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, {{ $proposalStats['total'] }}];
            proposalTrendsChart.data.datasets[1].data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, {{ $proposalStats['approved'] }}];
            proposalTrendsChart.data.datasets[2].data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, {{ $proposalStats['rejected'] }}];
        } else if (period === 'lastMonth') {
            proposalTrendsChart.data.datasets[0].data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, {{ $proposalStats['total'] - 2 }}, 0];
            proposalTrendsChart.data.datasets[1].data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, {{ $proposalStats['approved'] - 1 }}, 0];
            proposalTrendsChart.data.datasets[2].data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, {{ $proposalStats['rejected'] - 1 }}, 0];
        } else {
            proposalTrendsChart.data.datasets[0].data = proposalData.total;
            proposalTrendsChart.data.datasets[1].data = proposalData.approved;
            proposalTrendsChart.data.datasets[2].data = proposalData.rejected;
        }
        
        proposalTrendsChart.update();
    }
    
    function setActiveButton(button) {
        document.querySelectorAll('.btn-group .btn').forEach(btn => {
            btn.classList.remove('active');
        });
        button.classList.add('active');
    }
</script>
@endpush
@endsection