<?php

namespace App\Http\Controllers\Pelaksana;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $query = Program::with(['disbursements'])->where('pelaksana_id', auth()->id())->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $programs = $query->paginate(10);

        $stats = [
            'total_target' => Program::where('pelaksana_id', auth()->id())->sum('target_amount'),
            'total_disbursed' => \App\Models\Disbursement::whereHas('program', function($q) {
                $q->where('pelaksana_id', auth()->id());
            })->sum('amount'),
            'total_collected' => Program::where('pelaksana_id', auth()->id())->sum('collected_amount'),
        ];
        $stats['total_remaining'] = $stats['total_collected'] - $stats['total_disbursed'];

        return view('dashboard.pelaksana.programs.index', compact('programs', 'stats'));
    }

    public function create()
    {
        return view('dashboard.pelaksana.programs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:EKONOMI,PENDIDIKAN,KESEHATAN,SOSIAL,DAKWAH',
            'description' => 'required|string',
            'target_amount' => 'required|numeric|min:1000000',
            'location_province' => 'required|string',
            'location_city' => 'required|string',
            'location_detail' => 'nullable|string',
            'beneficiary_count' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'rab_total' => 'required|numeric|min:1000000',
            'cover_image' => 'nullable|image|max:2048',
            'rab_document' => 'nullable|file|mimes:pdf,xlsx,xls|max:5120',
            'implementation_plan_doc' => 'nullable|file|mimes:pdf|max:5120',
            'supporting_docs' => 'nullable|array',
            'supporting_docs.*' => 'file|max:5120',
        ]);

        $validated['pelaksana_id'] = auth()->id();
        $validated['status'] = 'PROPOSED';
        $validated['collected_amount'] = 0;

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('programs/covers', 'public');
        }

        if ($request->hasFile('rab_document')) {
            $validated['rab_document'] = $request->file('rab_document')->store('programs/rab', 'public');
        }

        if ($request->hasFile('implementation_plan_doc')) {
            $validated['implementation_plan_doc'] = $request->file('implementation_plan_doc')->store('programs/implementation_plans', 'public');
        }

        if ($request->hasFile('supporting_docs')) {
            $docs = [];
            foreach ($request->file('supporting_docs') as $file) {
                $docs[] = $file->store('programs/docs', 'public');
            }
            $validated['supporting_docs'] = $docs;
        }

        Program::create($validated);

        return redirect()->route('pelaksana.dashboard')
            ->with('success', 'Program berhasil diusulkan dan menunggu penilaian.');
    }

    public function show(Program $program)
    {
        if ($program->pelaksana_id != auth()->id()) {
            abort(403);
        }

        $program->load(['pks', 'reviews']);

        return view('dashboard.pelaksana.programs.show', compact('program'));
    }

    public function uploadPks(Request $request, Program $program)
    {
        if ($program->pelaksana_id != auth()->id()) {
            abort(403);
        }

        $request->validate([
            'signed_pks' => 'required|file|mimes:pdf|max:5120',
        ]);

        if (!$program->pks) {
            return back()->with('error', 'Draft PKS belum tersedia.');
        }

        $path = $request->file('signed_pks')->store('programs/pks', 'public');

        $program->pks->update([
            'document_path' => $path,
            'status' => 'SIGNED',
            'signed_by_pelaksana' => auth()->id(),
            'signed_at' => now(),
        ]);

        return back()->with('success', 'PKS yang ditandatangani berhasil diunggah. Menunggu verifikasi admin.');
    }

    public function edit(Program $program)
    {
        if ($program->pelaksana_id != auth()->id() || !in_array($program->status, ['PROPOSED', 'REVISION_NEEDED'])) {
            abort(403);
        }

        return view('dashboard.pelaksana.programs.edit', compact('program'));
    }

    public function update(Request $request, Program $program)
    {
        if ($program->pelaksana_id != auth()->id() || !in_array($program->status, ['PROPOSED', 'REVISION_NEEDED'])) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:EKONOMI,PENDIDIKAN,KESEHATAN,SOSIAL,DAKWAH',
            'description' => 'required|string',
            'target_amount' => 'required|numeric|min:1000000',
            'location_province' => 'required|string',
            'location_city' => 'required|string',
            'location_detail' => 'nullable|string',
            'beneficiary_count' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'rab_total' => 'required|numeric|min:1000000',
            'cover_image' => 'nullable|image|max:2048',
            'rab_document' => 'nullable|file|mimes:pdf,xlsx,xls|max:5120',
            'implementation_plan_doc' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        if ($request->hasFile('cover_image')) {
            if ($program->cover_image) {
                Storage::disk('public')->delete($program->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('programs/covers', 'public');
        }

        if ($request->hasFile('rab_document')) {
            if ($program->rab_document) {
                Storage::disk('public')->delete($program->rab_document);
            }
            $validated['rab_document'] = $request->file('rab_document')->store('programs/rab', 'public');
        }

        if ($request->hasFile('implementation_plan_doc')) {
            if ($program->implementation_plan_doc) {
                Storage::disk('public')->delete($program->implementation_plan_doc);
            }
            $validated['implementation_plan_doc'] = $request->file('implementation_plan_doc')->store('programs/implementation_plans', 'public');
        }

        // If it was a revision, set it back to proposed
        if ($program->status === 'REVISION_NEEDED') {
            $validated['status'] = 'PROPOSED';
        }

        $program->update($validated);

        return redirect()->route('pelaksana.dashboard')
            ->with('success', 'Program berhasil diperbarui.');
    }

    public function destroy(Program $program)
    {
        if ($program->pelaksana_id != auth()->id() || in_array($program->status, ['PUBLISHED', 'FUNDED', 'IMPLEMENTING', 'COMPLETED'])) {
            abort(403, 'Program yang sudah disetujui tidak dapat dihapus.');
        }

        // Delete associated files
        if ($program->cover_image) Storage::disk('public')->delete($program->cover_image);
        if ($program->rab_document) Storage::disk('public')->delete($program->rab_document);
        if ($program->implementation_plan_doc) Storage::disk('public')->delete($program->implementation_plan_doc);
        if ($program->supporting_docs) {
            foreach ($program->supporting_docs as $doc) {
                Storage::disk('public')->delete($doc);
            }
        }

        $program->delete();

        return redirect()->route('pelaksana.programs.index')->with('success', 'Usulan program berhasil dihapus.');
    }
}
