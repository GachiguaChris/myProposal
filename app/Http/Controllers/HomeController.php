<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proposal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get proposal statistics
        $totalProposals = Proposal::where('user_id', $user->id)->count();
        $acceptedProposals = Proposal::where('user_id', $user->id)->where('status', 'accepted')->count();
        $pendingProposals = Proposal::where('user_id', $user->id)->where('status', 'pending')->count();
        $rejectedProposals = Proposal::where('user_id', $user->id)->where('status', 'rejected')->count();
        
        // Calculate rates
        $acceptanceRate = $totalProposals > 0 ? round(($acceptedProposals / $totalProposals) * 100) : 0;
        
        // Get recent proposals
        $recentProposals = Proposal::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get monthly proposal data for the current year
        $currentYear = date('Y');
        $monthlyData = $this->getMonthlyProposalData($user->id, $currentYear);
        
        return view('home', compact(
            'totalProposals',
            'acceptedProposals',
            'pendingProposals',
            'rejectedProposals',
            'acceptanceRate',
            'recentProposals',
            'monthlyData'
        ));
    }
    
    /**
     * Get monthly proposal data for a specific user and year.
     *
     * @param int $userId
     * @param int $year
     * @return array
     */
    private function getMonthlyProposalData($userId, $year)
    {
        $data = Proposal::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->where('user_id', $userId)
            ->whereYear('created_at', $year)
            ->groupBy('month')
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