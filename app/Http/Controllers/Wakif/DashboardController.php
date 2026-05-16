<?php

namespace App\Http\Controllers\Wakif;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Program;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total_donated' => Donation::where('wakif_id', $user->id)->where('payment_status', 'PAID')->sum('amount'),
            'total_donations' => Donation::where('wakif_id', $user->id)->where('payment_status', 'PAID')->count(),
            'programs_supported' => Donation::where('wakif_id', $user->id)->where('payment_status', 'PAID')->distinct('program_id')->count('program_id'),
            'pending_payments' => Donation::where('wakif_id', $user->id)->where('payment_status', 'PENDING')->count(),
        ];

        $recentDonations = Donation::where('wakif_id', $user->id)
            ->with('program')
            ->latest()
            ->take(10)
            ->get();

        $certificates = $user->certificates()
            ->with('donation.program')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.wakif.index', compact('stats', 'recentDonations', 'certificates'));
    }
}
