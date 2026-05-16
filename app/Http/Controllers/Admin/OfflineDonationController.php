<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Program;
use Illuminate\Http\Request;

class OfflineDonationController extends Controller
{
    public function index(Request $request)
    {
        $query = Donation::where('is_offline', true)->with('program')->latest('paid_at');

        if ($request->filled('date')) {
            $query->whereDate('paid_at', $request->date);
        }

        if ($request->filled('program')) {
            $query->whereHas('program', function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->program . '%');
            });
        }

        if ($request->filled('search')) {
            $query->where('donor_name', 'like', '%' . $request->search . '%');
        }

        $donations = $query->paginate(15)->withQueryString();
        $programs = Program::select('id', 'title')->get();

        return view('dashboard.admin.offline-donations.index', compact('donations', 'programs'));
    }
}
