<?php

namespace App\Http\Controllers\Pelaksana;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $programs = Program::where('pelaksana_id', $user->id)
            ->latest()
            ->get();

        $stats = [
            'total_programs' => $programs->count(),
            'active_programs' => $programs->whereIn('status', ['PUBLISHED', 'FUNDED', 'IMPLEMENTING'])->count(),
            'pending_review' => $programs->where('status', 'PROPOSED')->count(),
            'total_collected' => $programs->sum('collected_amount'),
        ];

        return view('dashboard.pelaksana.index', compact('programs', 'stats'));
    }
}
