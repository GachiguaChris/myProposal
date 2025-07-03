<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\Client;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Proposal statistics
        $totalProposals = Proposal::count();
        $approvedProposals = Proposal::where('status', 'accepted')->count();
        $pendingProposals = Proposal::where('status', 'pending')->count();
        $rejectedProposals = Proposal::where('status', 'rejected')->count();
        
        // Calculate rates
        $approvalRate = $totalProposals > 0 ? round(($approvedProposals / $totalProposals) * 100) : 0;
        $pendingRate = $totalProposals > 0 ? round(($pendingProposals / $totalProposals) * 100) : 0;
        $rejectionRate = $totalProposals > 0 ? round(($rejectedProposals / $totalProposals) * 100) : 0;
        
        // Growth calculation (simulated for demo)
        $growth = 15; // This would typically be calculated from previous period data
        
        $proposalStats = [
            'total' => $totalProposals,
            'approved' => $approvedProposals,
            'pending' => $pendingProposals,
            'rejected' => $rejectedProposals,
            'approvalRate' => $approvalRate,
            'pendingRate' => $pendingRate,
            'rejectionRate' => $rejectionRate,
            'growth' => $growth
        ];
        
        // Monthly proposal data for the current year
        $currentYear = date('Y');
        $monthlyTotal = $this->getMonthlyProposalData($currentYear);
        $monthlyApproved = $this->getMonthlyProposalData($currentYear, 'accepted');
        $monthlyRejected = $this->getMonthlyProposalData($currentYear, 'rejected');
        
        $monthlyProposals = [
            'total' => $monthlyTotal,
            'approved' => $monthlyApproved,
            'rejected' => $monthlyRejected
        ];
        
        // Task statistics
        $taskStats = [
            'total' => Task::count(),
            'completed' => Task::where('status', 'completed')->count(),
            'pending' => Task::where('status', 'pending')->count(),
            'in_progress' => Task::where('status', 'in_progress')->count(),
            'cancelled' => Task::where('status', 'cancelled')->count()
        ];
        
        // Recent proposals
        $recentProposals = Proposal::with('client')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Top clients
        $topClients = Client::withCount('proposals')
            ->orderByDesc('proposals_count')
            ->limit(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'proposalStats',
            'monthlyProposals',
            'taskStats',
            'recentProposals',
            'topClients'
        ));
    }
    
    /**
     * Get monthly proposal data for a specific year and status.
     *
     * @param int $year
     * @param string|null $status
     * @return array
     */
    private function getMonthlyProposalData($year, $status = null)
    {
        $query = Proposal::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', $year);
            
        if ($status) {
            $query->where('status', $status);
        }
        
        $data = $query->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();
            
        // Fill in missing months with zero
        $result = array_fill(0, 12, 0);
        
        foreach ($data as $month => $count) {
            $result[$month - 1] = $count;
        }
        
        return $result;
    }
}