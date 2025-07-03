<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\ProjectCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Get proposals over time data for chart
     */
    private function getProposalsOverTime()
    {
        // Get proposals by date
        $proposals = Proposal::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        $labels = $proposals->pluck('date')->toArray();
        $values = $proposals->pluck('count')->toArray();
        
        // Simple linear regression for trend line
        $trend = [];
        if (count($values) > 1) {
            $x = array_keys($values);
            $y = array_values($values);
            $n = count($x);
            
            $sumX = array_sum($x);
            $sumY = array_sum($y);
            $sumXY = 0;
            $sumX2 = 0;
            
            for ($i = 0; $i < $n; $i++) {
                $sumXY += ($x[$i] * $y[$i]);
                $sumX2 += ($x[$i] * $x[$i]);
            }
            
            // Prevent division by zero
            $denominator = ($n * $sumX2 - $sumX * $sumX);
            if ($denominator != 0) {
                $slope = ($n * $sumXY - $sumX * $sumY) / $denominator;
                $intercept = ($sumY - $slope * $sumX) / $n;
                
                foreach ($x as $point) {
                    $trend[] = $slope * $point + $intercept;
                }
            }
        }
        
        return [
            'labels' => $labels,
            'values' => $values,
            'trend' => $trend
        ];
    }
    
    /**
     * Get category approval rates data for chart
     */
    private function getCategoryApprovalRates()
    {
        $categories = ProjectCategory::withCount(['proposals as total_count'])
            ->withCount(['proposals as approved_count' => function ($query) {
                $query->where('status', 'accepted');
            }])
            ->get();
        
        $labels = [];
        $counts = [];
        $approvalRates = [];
        
        foreach ($categories as $category) {
            $labels[] = $category->name;
            $counts[] = $category->total_count;
            
            if ($category->total_count > 0) {
                $approvalRates[] = round(($category->approved_count / $category->total_count) * 100, 1);
            } else {
                $approvalRates[] = 0;
            }
        }
        
        return [
            'labels' => $labels,
            'counts' => $counts,
            'approvalRates' => $approvalRates
        ];
    }

    /**
     * Display summary metrics for proposals.
     */
    public function summary()
    {
        // Check if export request
        if (request()->has('export')) {
            return $this->exportSummary();
        }
        
        // Total counts
        $total = Proposal::count();
        $approved = Proposal::where('status', 'accepted')->count();
        $rejected = Proposal::where('status', 'rejected')->count();
        $pending = Proposal::where('status', 'pending')->count();
        $revised = Proposal::where('status', 'review_requested')->count();

        // Apply filters if any
        $query = Proposal::query();
        
        if (request('date_from')) {
            $query->whereDate('created_at', '>=', request('date_from'));
        }
        
        if (request('date_to')) {
            $query->whereDate('created_at', '<=', request('date_to'));
        }
        
        if (request('category')) {
            $query->where('project_category_id', request('category'));
        }
        
        if (request('status')) {
            $query->where('status', request('status'));
        }
        
        // Category breakdown with budget info
        $categories = ProjectCategory::withCount(['proposals as total_count'])
            ->withCount(['proposals as approved_count' => function ($query) {
                $query->where('status', 'accepted');
            }])
            ->get()
            ->map(function ($category) {
                $category->allocated_budget = $category->budget ?? 0;
                $category->used_budget = Proposal::where('project_category_id', $category->id)
                    ->where('status', 'accepted')
                    ->sum('budget') ?? 0;
                return $category;
            });
        
        // Get recent proposals with real data
        $recentProposals = $query->with(['category', 'submittedByUser', 'versions', 'feedbacks'])
                          ->latest()
                          ->take(10)
                          ->get();
        
        // Calculate total budget
        $totalBudget = ProjectCategory::sum('budget');
        
        // Prepare data for charts
        $timeData = $this->getProposalsOverTime();
        $categoryData = $this->getCategoryApprovalRates();

        return view('admin.reports.summary', compact(
            'total', 'approved', 'rejected', 'pending', 'revised', 
            'categories', 'recentProposals', 'totalBudget',
            'timeData', 'categoryData'
        ));
    }
    
    /**
     * Export summary report as PDF or Excel
     */
    private function exportSummary()
    {
        // This is a placeholder for the export functionality
        // You can implement PDF or Excel export here
        
        // For now, we'll just return a simple view that can be printed
        $data = $this->getSummaryData();
        
        return view('admin.reports.export', $data);
    }
    
    /**
     * Get all data needed for summary report
     */
    private function getSummaryData()
    {
        // Total counts
        $total      = Proposal::count();
        $approved   = Proposal::where('status', 'accepted')->count();
        $rejected   = Proposal::where('status', 'rejected')->count();
        $pending    = Proposal::where('status', 'pending')->count();
        $revised    = Proposal::where('status', 'review_requested')->count();
        
        // Apply filters if any
        $query = Proposal::query();
        
        if (request('date_from')) {
            $query->whereDate('created_at', '>=', request('date_from'));
        }
        
        if (request('date_to')) {
            $query->whereDate('created_at', '<=', request('date_to'));
        }
        
        if (request('category')) {
            $query->where('project_category_id', request('category'));
        }
        
        if (request('status')) {
            $query->where('status', request('status'));
        }
        
        // Category breakdown
        $categories = ProjectCategory::withCount(['proposals as total_count'])
            ->withCount(['proposals as approved_count' => function ($query) {
                $query->where('status', 'accepted');
            }])
            ->get()
            ->map(function ($category) {
                $category->allocated_budget = $category->budget ?? 0;
                $category->used_budget = Proposal::where('project_category_id', $category->id)
                    ->where('status', 'accepted')
                    ->sum('budget') ?? 0;
                return $category;
            });
        
        // Get proposals with real data
        $proposals = $query->with(['category', 'submittedByUser'])
                    ->latest()
                    ->get();
        
        // Calculate total budget
        $totalBudget = ProjectCategory::sum('budget');
        
        // Prepare data for charts
        $timeData = $this->getProposalsOverTime();
        $categoryData = $this->getCategoryApprovalRates();
        
        return compact(
            'total', 'approved', 'rejected', 'pending', 'revised',
            'categories', 'proposals', 'totalBudget',
            'timeData', 'categoryData'
        );
    }
}