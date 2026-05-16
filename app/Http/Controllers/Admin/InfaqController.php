<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;

class InfaqController extends Controller
{
    public function index(Request $request)
    {
        // 1. Infaq Income (from Donations)
        $incomeQuery = Donation::query()
            ->where('payment_status', 'PAID')
            ->where('service_fee', '>', 0)
            ->with(['program', 'wakif'])
            ->latest('paid_at');

        // Filters for Income
        if ($request->filled('search')) {
            $incomeQuery->where(function ($q) use ($request) {
                $q->whereHas('wakif', function ($sq) use ($request) {
                    $sq->where('name', 'like', '%' . $request->search . '%');
                })
                ->orWhere('donor_name', 'like', '%' . $request->search . '%')
                ->orWhereHas('program', function ($sq) use ($request) {
                    $sq->where('title', 'like', '%' . $request->search . '%');
                });
            });
        }

        if ($request->filled('date')) {
            $incomeQuery->whereDate('paid_at', $request->date);
        }

        $infaqList = $incomeQuery->paginate(15, ['*'], 'income_page')->withQueryString();
        
        // 2. Infaq Withdrawals (History)
        $withdrawalQuery = \App\Models\InfaqWithdrawal::with('creator')->latest('withdrawal_date');

        if ($request->filled('w_search')) {
            $withdrawalQuery->where(function($q) use ($request) {
                $q->where('bank_account_name', 'like', '%' . $request->w_search . '%')
                  ->orWhere('purpose', 'like', '%' . $request->w_search . '%');
            });
        }

        if ($request->filled('w_date')) {
            $withdrawalQuery->whereDate('withdrawal_date', $request->w_date);
        }

        $withdrawals = $withdrawalQuery->paginate(15, ['*'], 'withdrawal_page')->withQueryString();

        // 3. Stats
        $totalInfaq = Donation::where('payment_status', 'PAID')->sum('service_fee');
        $totalWithdrawn = \App\Models\InfaqWithdrawal::sum('amount');
        $balance = $totalInfaq - $totalWithdrawn;

        return view('dashboard.admin.infaq.index', compact('infaqList', 'totalInfaq', 'withdrawals', 'totalWithdrawn', 'balance'));
    }

    public function storeWithdrawal(Request $request)
    {
        $request->validate([
            'withdrawal_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'bank_name' => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:255',
            'bank_account_name' => 'required|string|max:255',
            'purpose' => 'required|string',
            'proof_file' => 'required|image|max:5120',
        ]);

        $totalInfaq = Donation::where('payment_status', 'PAID')->sum('service_fee');
        $totalWithdrawn = \App\Models\InfaqWithdrawal::sum('amount');
        $balance = $totalInfaq - $totalWithdrawn;

        if ($request->amount > $balance) {
            return back()->with('error', 'Saldo infaq tidak mencukupi untuk penarikan ini.');
        }

        $path = $request->file('proof_file')->store('infaq/withdrawals', 'public');

        \App\Models\InfaqWithdrawal::create([
            'withdrawal_date' => $request->withdrawal_date,
            'amount' => $request->amount,
            'bank_name' => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_account_name' => $request->bank_account_name,
            'purpose' => $request->purpose,
            'proof_path' => $path,
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Pencatatan pengambilan dana infaq berhasil disimpan.');
    }
}
