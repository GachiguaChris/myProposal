@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1>Analytics Dashboard</h1>
        </div>
        <div class="col-auto">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary active" id="thisYear">This Year</button>
                <button type="button" class="btn btn-outline-primary" id="lastYear">Last Year</button>
                <button type="button" class="btn btn-outline-primary" id="allTime">All Time</button>
            </div>
        </div>
    </div>

    <!-- Proposal Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Proposals</h5>
                    <h2 class="display-4">{{ $proposalStats['total'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Approved</h5>
                    <h2 class="display-4">{{ $proposalStats['approved'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h5 class="card-title">Pending</h5>
                    <h2 class="display-4">{{ $proposalStats['pending'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Rejected</h5>
                    <h2 class="display-4">{{ $proposalStats['rejected'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Monthly Proposals Chart -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Monthly Proposals ({{ date('Y') }})</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyProposalsChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Client Stats -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Client Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Total Clients</h6>
                        <h3>{{ $clientStats['total'] }}</h3>
                    </div>
                    <div class="mb-3">
                        <h6>Active Clients</h6>
                        <h3>{{ $clientStats['active'] }}</h3>
                    </div>
                    <div>
                        <h6>Inactive Clients</h6>
                        <h3>{{ $clientStats['inactive'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Top Clients -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Top Clients</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Proposals</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topClients as $client)
                                <tr>
                                    <td>{{ $client->name }}</td>
                                    <td>{{ $client->proposals_count }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Task Stats -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Task Statistics</h5>
                </div>
                <div class="card-body">
                    <canvas id="taskStatsChart" height="260"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Monthly Proposals Chart
    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const monthlyData = @json(array_values($monthlyProposals));
    
    const monthlyProposalsChart = new Chart(
        document.getElementById('monthlyProposalsChart'),
        {
            type: 'bar',
            data: {
                labels: monthNames,
                datasets: [{
                    label: 'Proposals',
                    data: monthlyData,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgb(54, 162, 235)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
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
            type: 'pie',
            data: {
                labels: ['Completed', 'In Progress', 'Pending', 'Cancelled'],
                datasets: [{
                    data: [
                        {{ $taskStats['completed'] }}, 
                        {{ $taskStats['in_progress'] }}, 
                        {{ $taskStats['pending'] }}, 
                        {{ $taskStats['total'] - $taskStats['completed'] - $taskStats['in_progress'] - $taskStats['pending'] }}
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
</script>
@endpush
@endsection