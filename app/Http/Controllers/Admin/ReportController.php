<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $pendingReports = Report::with(['program', 'submitter'])
            ->where('status', 'SUBMITTED')
            ->latest()
            ->get();

        return view('dashboard.admin.reports.index', compact('pendingReports'));
    }

    public function validateReport(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status' => 'required|in:VALIDATED,REJECTED',
            'rejection_notes' => 'required_if:status,REJECTED|nullable|string',
        ]);

        $report->update([
            'status' => $validated['status'],
            'validated_by' => auth()->id(),
            'validated_at' => now(),
            'rejection_notes' => $validated['rejection_notes'] ?? null,
        ]);

        // If validated, link to the oldest disbursement that doesn't have a report yet
        if ($validated['status'] === 'VALIDATED') {
            $disbursement = \App\Models\Disbursement::where('program_id', $report->program_id)
                ->whereNull('report_id')
                ->orderBy('phase')
                ->first();
            
            if ($disbursement) {
                $disbursement->update(['report_id' => $report->id]);
            }
        }

        return back()->with('success', $validated['status'] === 'VALIDATED' ? 'Laporan berhasil divalidasi.' : 'Laporan ditolak.');
    }
}
