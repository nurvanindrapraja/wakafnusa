<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Program::active()->with('pelaksana');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('location')) {
            $query->where('location_province', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'popular' => $query->orderByDesc('collected_amount'),
            'ending' => $query->orderBy('end_date'),
            'target' => $query->orderByDesc('target_amount'),
            default => $query->latest('published_at'),
        };

        $programs = $query->paginate(12);
        $categories = ['PENDIDIKAN', 'KESEHATAN', 'EKONOMI', 'IBADAH', 'SOSIAL'];

        if (auth()->check()) {
            $role = strtolower(auth()->user()->role);
            $sidebar = "dashboard.{$role}.partials.nav";
            return view('dashboard.wakif.catalog', compact('programs', 'categories', 'sidebar'));
        }

        return view('public.catalog', compact('programs', 'categories'));
    }

    public function show(string $slug)
    {
        $program = Program::where('slug', $slug)
            ->with(['pelaksana', 'donations' => function ($q) {
                $q->where('payment_status', 'PAID')->latest()->take(10);
            }, 'reports' => function ($q) {
                $q->where('status', 'VALIDATED')->latest();
            }])
            ->firstOrFail();

        $relatedPrograms = Program::active()
            ->where('category', $program->category)
            ->where('id', '!=', $program->id)
            ->take(3)
            ->get();

        if (auth()->check()) {
            $role = strtolower(auth()->user()->role);
            $sidebar = "dashboard.{$role}.partials.nav";
            return view('dashboard.wakif.program-show', compact('program', 'relatedPrograms', 'sidebar'));
        }

        return view('public.program-detail', compact('program', 'relatedPrograms'));
    }
}
