<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\Client;
use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display the analytics dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Proposal statistics
        $proposalStats = [
            'total' => Proposal::count(),
            'approved' => Proposal::where('status', 'approved')->count(),
            'pending' => Proposal::where('status', 'pending')->count(),
            'rejected' => Proposal::where('status', 'rejected')->count(),
        ];
        
        // Monthly proposal counts for the current year
        $monthlyProposals = Proposal::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();
            
        // Fill in missing months with zero
        for ($i = 1; $i <= 12; $i++) {
            if (!isset($monthlyProposals[$i])) {
                $monthlyProposals[$i] = 0;
            }
        }
        ksort($monthlyProposals);
        
        // Client statistics
        $clientStats = [
            'total' => Client::count(),
            'active' => Client::where('status', 'active')->count(),
            'inactive' => Client::where('status', 'inactive')->count(),
        ];
        
        // Top clients by proposal count
        $topClients = Client::withCount('proposals')
            ->orderByDesc('proposals_count')
            ->limit(5)
            ->get();
            
        // Task statistics
        $taskStats = [
            'total' => Task::count(),
            'completed' => Task::where('status', 'completed')->count(),
            'pending' => Task::where('status', 'pending')->count(),
            'in_progress' => Task::where('status', 'in_progress')->count(),
        ];
        
        // User statistics
        $userStats = [
            'total' => User::count(),
            'admins' => User::where('is_admin', true)->count(),
            'regular' => User::where('is_admin', false)->count(),
        ];
        
        return view('admin.analytics.index', compact(
            'proposalStats', 
            'monthlyProposals', 
            'clientStats', 
            'topClients',
            'taskStats',
            'userStats'
        ));
    }
}