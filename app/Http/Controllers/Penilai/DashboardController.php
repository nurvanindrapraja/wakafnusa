<?php

namespace App\Http\Controllers\Penilai;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingPrograms = Program::where('status', 'PROPOSED')
            ->latest()
            ->take(10)
            ->get();

        $reviewedPrograms = Program::whereHas('reviews', function($query) {
                $query->where('reviewer_id', auth()->id());
            })
            ->latest()
            ->take(10)
            ->get();

        $stats = [
            'pending' => Program::where('status', 'PROPOSED')->count(),
            'reviewed' => Program::whereHas('reviews', function($query) {
                $query->where('reviewer_id', auth()->id());
            })->count()
        ];

        return view('dashboard.penilai.index', compact('pendingPrograms', 'reviewedPrograms', 'stats'));
    }

    public function queue(Request $request)
    {
        $query = Program::where('status', 'PROPOSED')->latest();

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhereHas('pelaksana', function($pq) use ($request) {
                      $pq->where('name', 'like', "%{$request->search}%");
                  });
            });
        }

        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $programs = $query->paginate(15)->withQueryString();

        return view('dashboard.penilai.queue', compact('programs'));
    }

    public function history(Request $request)
    {
        $query = Program::whereHas('reviews', function($q) {
            $q->where('reviewer_id', auth()->id());
        })->latest();

        if ($request->decision) {
             $status = match ($request->decision) {
                 'DISETUJU' => 'RECOMMENDED',
                 'REVISI' => 'REVISION_NEEDED',
                 'DITOLAK' => 'REJECTED',
                 default => null
             };
             if ($status) {
                 $query->where('status', $status);
             }
        }

        $programs = $query->paginate(15)->withQueryString();

        return view('dashboard.penilai.history', compact('programs'));
    }
}
