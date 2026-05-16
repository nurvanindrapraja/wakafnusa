<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Disbursement;
use App\Models\InfaqWithdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class FinancialReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : now()->endOfDay();

        // ── Dana Masuk (IN) ──────────────────────────────────────────
        $donations = Donation::where('payment_status', 'PAID')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->with('wakif', 'program')
            ->get();

        $totalWaqf = $donations->sum('amount');
        $totalServiceFee = $donations->sum('service_fee');
        $totalIn = $totalWaqf + $totalServiceFee;

        // ── Dana Keluar (OUT) ─────────────────────────────────────────
        $disbursements = Disbursement::where('status', 'DISBURSED')
            ->whereBetween('disbursed_at', [$startDate, $endDate])
            ->with('program')
            ->get();

        $infaqWithdrawals = InfaqWithdrawal::whereBetween('withdrawal_date', [$startDate, $endDate])
            ->get();

        $totalDisbursed = $disbursements->sum('amount');
        $totalInfaqOut = $infaqWithdrawals->sum('amount');
        $totalOut = $totalDisbursed + $totalInfaqOut;

        // ── Combined Ledger ──────────────────────────────────────────
        $transactions = collect();

        // Add Donations (Waqf)
        foreach ($donations as $donation) {
            $transactions->push([
                'date' => $donation->paid_at,
                'type' => 'MASUK',
                'category' => 'WAKAF',
                'description' => "Wakaf dari " . $donation->donor_display_name . " untuk " . $donation->program->title,
                'amount' => $donation->amount,
                'ref' => $donation->donation_code,
                'is_offline' => $donation->is_offline
            ]);
            
            if ($donation->service_fee > 0) {
                $transactions->push([
                    'date' => $donation->paid_at,
                    'type' => 'MASUK',
                    'category' => 'INFAQ',
                    'description' => "Infaq Layanan dari " . $donation->donor_display_name,
                    'amount' => $donation->service_fee,
                    'ref' => $donation->donation_code,
                    'is_offline' => $donation->is_offline
                ]);
            }
        }

        // Add Disbursements
        foreach ($disbursements as $disbursement) {
            $transactions->push([
                'date' => $disbursement->disbursed_at,
                'type' => 'KELUAR',
                'category' => 'PENYALURAN',
                'description' => "Penyaluran Tahap " . $disbursement->phase . " untuk " . $disbursement->program->title,
                'amount' => $disbursement->amount,
                'ref' => "DISB-" . $disbursement->id,
                'is_offline' => false
            ]);
        }

        // Add Infaq Withdrawals
        foreach ($infaqWithdrawals as $withdrawal) {
            $transactions->push([
                'date' => $withdrawal->withdrawal_date,
                'type' => 'KELUAR',
                'category' => 'INFAQ_OUT',
                'description' => "Pengambilan Infaq: " . $withdrawal->purpose,
                'amount' => $withdrawal->amount,
                'ref' => "WD-" . $withdrawal->id,
                'is_offline' => false
            ]);
        }

        // Filter by Description
        if ($request->filled('description')) {
            $transactions = $transactions->filter(function($item) use ($request) {
                return str_contains(strtolower($item['description']), strtolower($request->description)) ||
                       str_contains(strtolower($item['ref']), strtolower($request->description));
            });
        }

        $sortedLedger = $transactions->sortByDesc('date');

        // Pagination
        $perPage = 20;
        $page = $request->get('page', 1);
        $paginatedLedger = new \Illuminate\Pagination\LengthAwarePaginator(
            $sortedLedger->forPage($page, $perPage),
            $sortedLedger->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // ── Cumulative Balance ───────────────────────────────────────
        $allIn = Donation::where('payment_status', 'PAID')->sum('amount') + Donation::where('payment_status', 'PAID')->sum('service_fee');
        $allOut = Disbursement::where('status', 'DISBURSED')->sum('amount') + InfaqWithdrawal::sum('amount');
        $remainingBalance = $allIn - $allOut;

        return view('dashboard.admin.financial-reports.index', compact(
            'startDate', 'endDate', 'totalWaqf', 'totalServiceFee', 
            'totalDisbursed', 'totalInfaqOut', 'totalIn', 'totalOut', 
            'paginatedLedger', 'remainingBalance'
        ));
    }
}
