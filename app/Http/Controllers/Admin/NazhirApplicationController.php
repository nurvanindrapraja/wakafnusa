<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NazhirApplication;
use App\Models\User;
use Illuminate\Http\Request;

class NazhirApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = NazhirApplication::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $applications = $query->paginate(15)->withQueryString();
        return view('dashboard.admin.nazhir-applications.index', compact('applications'));
    }

    public function approve(NazhirApplication $application)
    {
        $application->update(['status' => 'APPROVED']);
        
        $user = $application->user;
        $user->update([
            'role' => 'PELAKSANA',
            'name' => $application->institution_name, // Update user name to institution name
            'phone' => $application->phone,
            'address' => $application->address,
            'institution_short_profile' => $application->institution_short_profile,
            'bank_name' => $application->bank_name,
            'bank_account_name' => $application->bank_account_name,
            'bank_account_number' => $application->bank_account_number,
            'institution_profile_doc' => $application->institution_profile_path,
            'lsp_certificate' => $application->lsp_certificate_path,
        ]);

        return back()->with('success', "Pengajuan akun {$user->name} berhasil disetujui. Peran akun kini telah berubah menjadi Pelaksana Nazhir.");
    }

    public function reject(Request $request, NazhirApplication $application)
    {
        $request->validate([
            'reason' => 'required|string',
        ]);

        $application->update([
            'status' => 'REJECTED',
            'rejection_reason' => $request->reason,
        ]);

        return back()->with('success', "Pengajuan akun {$application->user->name} telah ditolak.");
    }
}
