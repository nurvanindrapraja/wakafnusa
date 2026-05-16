<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Disbursement;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DisbursementController extends Controller
{
    public function index(Request $request)
    {
        // Get published programs
        $programs = Program::active()->with(['pelaksana', 'disbursements.report'])
            ->get()
            ->filter(function($program) {
                $nextPhase = $program->disbursements->count() + 1;
                $requiredThreshold = $nextPhase * 0.25;
                
                // Must have at least 25% to even show up, 
                // but specifically needs (phase * 25%) to be eligible for next disbursement
                return $program->collected_amount >= ($program->target_amount * 0.25);
            });

        $query = Disbursement::with(['program.pelaksana']);

        if ($request->filled('search')) {
            $query->whereHas('program', function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhereHas('pelaksana', function($pq) use ($request) {
                      $pq->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('disbursed_at', $request->date);
        }

        $disbursements = $query->latest('disbursed_at')->paginate(15)->withQueryString();

        $stats = [
            'total_disbursed' => Disbursement::sum('amount'),
            'program_count' => Disbursement::distinct('program_id')->count('program_id'),
            'pelaksana_count' => Program::whereHas('disbursements')->distinct('pelaksana_id')->count('pelaksana_id'),
        ];

        return view('dashboard.admin.disbursements.index', compact('programs', 'disbursements', 'stats'));
    }

    public function store(Request $request, Program $program)
    {
        // 0. Check if Pelaksana has bank details
        if (!$program->pelaksana->bank_name || !$program->pelaksana->bank_account_number || !$program->pelaksana->bank_account_name) {
            return back()->with('error', 'Penyaluran dana tidak dapat dilakukan karena Pelaksana belum melengkapi data rekening bank di profil mereka.');
        }

        // 1. Check if previous disbursement has a validated report
        $pendingReport = Disbursement::where('program_id', $program->id)
            ->where(function($q) {
                $q->whereNull('report_id')
                  ->orWhereHas('report', function($rq) {
                      $rq->where('status', '!=', 'VALIDATED');
                  });
            })->exists();

        if ($pendingReport) {
            return back()->with('error', 'Penyaluran dana tidak dapat dilakukan karena masih ada laporan realisasi tahap sebelumnya yang belum diselesaikan atau divalidasi.');
        }

        // 2. Calculate max amount
        $totalDisbursed = $program->disbursements()->sum('amount');
        $maxAmount = $program->collected_amount - $totalDisbursed;

        $request->validate([
            'amount' => ['required', 'numeric', 'min:1000', 'max:' . $maxAmount],
            'proof_path' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'notes' => ['nullable', 'string'],
        ], [
            'amount.max' => 'Jumlah penyaluran tidak boleh melebihi sisa dana yang tersedia (Rp ' . number_format($maxAmount, 0, ',', '.') . ').'
        ]);

        $proofPath = $request->file('proof_path')->store('programs/disbursements', 'public');

        $program->disbursements()->create([
            'amount' => $request->amount,
            'phase' => $program->disbursements()->count() + 1,
            'status' => 'DISBURSED',
            'disbursed_at' => now(),
            'approved_by' => auth()->id(),
            'notes' => $request->notes,
            'proof_path' => $proofPath,
        ]);

        return back()->with('success', 'Dana berhasil disalurkan ke Pelaksana.');
    }
}
