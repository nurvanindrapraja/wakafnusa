<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\User;
use App\Models\Donation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'total_wakif' => User::role('wakif')->count(),
            'total_funds' => Donation::where('payment_status', 'PAID')->sum('amount'),
            'total_programs' => Program::count(),
            'pending_approval' => Program::where('status', 'RECOMMENDED')->count(),
            'active_programs' => Program::where('status', 'PUBLISHED')->count(),
        ];

        $donationQuery = Donation::with(['wakif', 'program'])
            ->where('payment_status', 'PAID')
            ->latest();

        if ($request->search) {
            $donationQuery->where(function($q) use ($request) {
                $q->whereHas('wakif', function($wq) use ($request) {
                    $wq->where('name', 'like', "%{$request->search}%");
                })->orWhereHas('program', function($pq) use ($request) {
                    $pq->where('title', 'like', "%{$request->search}%");
                });
            });
        }

        if ($request->amount_range) {
            match($request->amount_range) {
                'lt100k' => $donationQuery->where('amount', '<', 100000),
                '1k_1m' => $donationQuery->whereBetween('amount', [1000, 999999]),
                '1m_5m' => $donationQuery->whereBetween('amount', [1000000, 4999999]),
                '5m_10m' => $donationQuery->whereBetween('amount', [5000000, 9999999]),
                '10m_50m' => $donationQuery->whereBetween('amount', [10000000, 49999999]),
                '50m_100m' => $donationQuery->whereBetween('amount', [50000000, 100000000]),
                'gt100m' => $donationQuery->where('amount', '>', 100000000),
                default => null
            };
        }

        $recentDonations = $donationQuery->paginate(15)->withQueryString();

        $programComposition = Program::selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->get()
            ->pluck('total', 'category');

        return view('dashboard.admin.index', compact('stats', 'recentDonations', 'programComposition'));
    }
}
