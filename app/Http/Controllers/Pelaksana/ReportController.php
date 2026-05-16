<?php

namespace App\Http\Controllers\Pelaksana;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::where('submitted_by', auth()->id())
            ->with('program')
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('program')) {
            $query->whereHas('program', function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->program . '%');
            });
        }

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        $reports = $query->paginate(10);

        return view('dashboard.pelaksana.reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        if ($report->submitted_by !== auth()->id()) {
            abort(403);
        }

        return view('dashboard.pelaksana.reports.show', compact('report'));
    }
    public function create(Program $program)
    {
        if ($program->pelaksana_id !== auth()->id()) {
            abort(403);
        }

        // Only allow reports for programs that are approved and in progress
        if (!in_array($program->status, ['PUBLISHED', 'FUNDED', 'IMPLEMENTING'])) {
            return redirect()->route('pelaksana.dashboard')->with('error', 'Program belum dalam tahap pelaksanaan.');
        }

        return view('dashboard.pelaksana.reports.create', compact('program'));
    }

    public function store(Request $request, Program $program)
    {
        if ($program->pelaksana_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'report_period' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'realized_amount' => 'required|numeric|min:0',
            'evidence_photos' => 'nullable|array',
            'evidence_photos.*' => 'image|max:2048',
            'evidence_docs' => 'nullable|array',
            'evidence_docs.*' => 'file|max:5120',
        ], [
            'evidence_photos.*.max' => 'Ukuran File Gambar Terlalu Besar, maksimal 2 MB',
            'evidence_photos.*.image' => 'File bukti harus berupa gambar (JPG, PNG, WEBP)',
            'evidence_docs.*.max' => 'Ukuran File Dokumen Terlalu Besar, maksimal 5 MB',
        ]);

        $validated['program_id'] = $program->id;
        $validated['submitted_by'] = auth()->id();
        $validated['status'] = 'SUBMITTED';

        if ($request->hasFile('evidence_photos')) {
            $photos = [];
            foreach ($request->file('evidence_photos') as $file) {
                $photos[] = $file->store('reports/photos', 'public');
            }
            $validated['evidence_photos'] = $photos;
        }

        if ($request->hasFile('evidence_docs')) {
            $docs = [];
            foreach ($request->file('evidence_docs') as $file) {
                $docs[] = $file->store('reports/docs', 'public');
            }
            $validated['evidence_docs'] = $docs;
        }

        Report::create($validated);

        // Update program status to IMPLEMENTING if it was just PUBLISHED/FUNDED
        if (in_array($program->status, ['PUBLISHED', 'FUNDED'])) {
            $program->update(['status' => 'IMPLEMENTING']);
        }

        return redirect()->route('pelaksana.dashboard')
            ->with('success', 'Laporan realisasi berhasil dikirim dan menunggu validasi Admin.');
    }
}
