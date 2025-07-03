<!DOCTYPE html>
<html>
<head>
    <title>Proposal Summary Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        h1, h2 {
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .summary-box {
            display: inline-block;
            width: 18%;
            margin: 1%;
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .summary-box h3 {
            margin-top: 0;
            font-size: 16px;
        }
        .summary-box p {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .budget-section {
            margin: 20px 0;
        }
        .budget-item {
            margin-bottom: 10px;
        }
        .progress-bar {
            height: 20px;
            background-color: #e9ecef;
            border-radius: 5px;
            margin-top: 5px;
        }
        .progress-bar-fill {
            height: 100%;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
        }
        .bg-success {
            background-color: #28a745;
        }
        .bg-warning {
            background-color: #ffc107;
        }
        .bg-danger {
            background-color: #dc3545;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Proposal Summary Report</h1>
        <p>Generated on: {{ now()->format('F d, Y h:i A') }}</p>
    </div>
    
    <h2>Summary Statistics</h2>
    <div>
        <div class="summary-box">
            <h3>Total Proposals</h3>
            <p>{{ $total }}</p>
        </div>
        <div class="summary-box">
            <h3>Approved</h3>
            <p>{{ $approved }}</p>
        </div>
        <div class="summary-box">
            <h3>Rejected</h3>
            <p>{{ $rejected }}</p>
        </div>
        <div class="summary-box">
            <h3>Pending</h3>
            <p>{{ $pending }}</p>
        </div>
        <div class="summary-box">
            <h3>Approval Rate</h3>
            <p>{{ $total > 0 ? round(($approved / $total) * 100, 1) : 0 }}%</p>
        </div>
    </div>
    
    <h2>Budget Utilization by Category</h2>
    <div class="budget-section">
        <p><strong>Total Budget: ${{ number_format($totalBudget, 2) }}</strong></p>
        
        @foreach($categories as $cat)
        <div class="budget-item">
            <div style="display: flex; justify-content: space-between;">
                <strong>{{ $cat->name }}</strong>
                <span>${{ number_format($cat->used_budget, 2) }} / ${{ number_format($cat->allocated_budget, 2) }}</span>
            </div>
            <div class="progress-bar">
                @php
                    $used = $cat->used_budget ?? 0;
                    $allocated = $cat->allocated_budget ?? 1;
                    $percentage = $allocated > 0 ? min(100, ($used / $allocated) * 100) : 0;
                    
                    $colorClass = 'bg-success';
                    if ($percentage > 90) $colorClass = 'bg-danger';
                    elseif ($percentage > 75) $colorClass = 'bg-warning';
                @endphp
                <div class="progress-bar-fill {{ $colorClass }}" style="width: {{ $percentage }}%">
                    {{ round($percentage) }}%
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <h2>Proposals List</h2>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Submitted By</th>
                <th>Budget</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($proposals as $proposal)
            <tr>
                <td>{{ $proposal->title }}</td>
                <td>{{ $proposal->category->name ?? 'N/A' }}</td>
                <td>{{ $proposal->submittedByUser->name ?? $proposal->submitted_by }}</td>
                <td>${{ number_format($proposal->budget, 2) }}</td>
                <td>{{ ucfirst($proposal->status) }}</td>
                <td>{{ $proposal->created_at->format('M d, Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>This report is confidential and intended for authorized personnel only.</p>
    </div>
</body>
</html>