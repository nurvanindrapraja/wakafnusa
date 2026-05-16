<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Donation;
use App\Models\User;

class LandingController extends Controller
{
    public function index()
    {
        $stats = [
            'total_wakif' => User::where('role', 'WAKIF')->count(),
            'total_collected' => Donation::where('payment_status', 'PAID')->sum('amount'),
            'total_programs' => Program::active()->count(),
            'total_beneficiaries' => Program::active()->sum('beneficiary_count'),
        ];

        $featuredPrograms = Program::active()->with('pelaksana')
            ->latest('published_at')
            ->take(6)
            ->get();

        return view('public.landing', compact('stats', 'featuredPrograms'));
    }

    public function about()
    {
        return view('public.about');
    }

    public function team()
    {
        $members = \App\Models\TeamMember::orderBy('order_weight')->get();
        
        $groups = [
            'PENILAI' => $members->where('category', 'PENILAI'),
            'NAZHIR' => $members->where('category', 'NAZHIR'),
            'PENGEMBANG' => $members->where('category', 'PENGEMBANG'),
        ];

        return view('public.team', compact('groups'));
    }
}
