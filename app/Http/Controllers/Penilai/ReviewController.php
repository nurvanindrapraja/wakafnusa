<?php

namespace App\Http\Controllers\Penilai;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function show(Program $program)
    {
        if ($program->status !== 'PROPOSED') {
            return redirect()->route('penilai.dashboard')->with('error', 'Program tidak dalam status untuk direview.');
        }

        return view('dashboard.penilai.reviews.show', compact('program'));
    }

    public function store(Request $request, Program $program)
    {
        $validated = $request->validate([
            'score_character' => 'required|integer|min:1|max:5',
            'score_capacity' => 'required|integer|min:1|max:5',
            'score_capital' => 'required|integer|min:1|max:5',
            'score_collateral' => 'required|integer|min:1|max:5',
            'score_condition' => 'required|integer|min:1|max:5',
            'score_people' => 'required|integer|min:1|max:5',
            'score_planet' => 'required|integer|min:1|max:5',
            'score_profit' => 'required|integer|min:1|max:5',
            'notes_5c' => 'required|string',
            'notes_3p' => 'required|string',
            'decision' => 'required|in:RECOMMENDED,REVISION_NEEDED,REJECTED',
            'decision_notes' => 'required|string',
        ]);

        DB::transaction(function () use ($validated, $program) {
            Review::create(array_merge($validated, [
                'program_id' => $program->id,
                'reviewer_id' => auth()->id(),
            ]));

            $newStatus = match ($validated['decision']) {
                'RECOMMENDED' => 'RECOMMENDED',
                'REVISION_NEEDED' => 'REVISION_NEEDED',
                'REJECTED' => 'REJECTED',
            };

            $program->update(['status' => $newStatus]);
        });

        return redirect()->route('penilai.dashboard')
            ->with('success', 'Penilaian berhasil disimpan.');
    }
    public function history(Program $program)
    {
        $review = $program->reviews()->where('reviewer_id', auth()->id())->latest()->first();
        
        if (!$review) {
            return redirect()->route('penilai.dashboard')->with('error', 'Riwayat review tidak ditemukan.');
        }

        return view('dashboard.penilai.reviews.history', compact('program', 'review'));
    }
}
