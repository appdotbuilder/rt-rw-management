<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Event;
use App\Models\FinancialRecord;
use App\Models\Household;
use App\Models\Resident;
use App\Models\AdministrativeLetter;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get summary statistics
        $stats = [
            'households' => Household::active()->count(),
            'residents' => Resident::active()->count(),
            'pending_letters' => AdministrativeLetter::pending()->count(),
            'upcoming_events' => Event::upcoming()->count(),
        ];

        // Get recent announcements
        $recentAnnouncements = Announcement::where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('publish_at')
                    ->orWhere('publish_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->with('creator')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get upcoming events
        $upcomingEvents = Event::upcoming()
            ->with('organizer')
            ->limit(3)
            ->get();

        // Get recent financial activity
        $recentFinancials = FinancialRecord::with(['household', 'recordedBy'])
            ->orderBy('transaction_date', 'desc')
            ->limit(5)
            ->get();

        // Get monthly financial summary
        $currentMonth = now()->format('Y-m');
        $monthlyIncome = FinancialRecord::income()
            ->where('transaction_date', 'like', $currentMonth . '%')
            ->where('status', 'completed')
            ->sum('amount');
        
        $monthlyExpense = FinancialRecord::expense()
            ->where('transaction_date', 'like', $currentMonth . '%')
            ->where('status', 'completed')
            ->sum('amount');

        $financialSummary = [
            'monthly_income' => $monthlyIncome,
            'monthly_expense' => $monthlyExpense,
            'monthly_balance' => $monthlyIncome - $monthlyExpense,
        ];

        return Inertia::render('dashboard', [
            'stats' => $stats,
            'recent_announcements' => $recentAnnouncements,
            'upcoming_events' => $upcomingEvents,
            'recent_financials' => $recentFinancials,
            'financial_summary' => $financialSummary,
            'user_role' => $user->role,
            'user_rt_rw' => $user->rt_rw,
        ]);
    }
}