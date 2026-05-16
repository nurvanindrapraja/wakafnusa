<?php

namespace App\Http\Controllers\Wakif;

use App\Http\Controllers\Controller;
use App\Models\NazhirApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NazhirApplicationController extends Controller
{
    public function index()
    {
        $application = NazhirApplication::where('user_id', auth()->id())->first();
        return view('dashboard.wakif.nazhir-application.index', compact('application'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'institution_name' => 'required|string|max:255',
            'institution_short_profile' => 'required|string',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'institution_profile' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'lsp_certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'bank_name' => 'required|string|max:100',
            'bank_account_name' => 'required|string|max:150',
            'bank_account_number' => 'required|string|max:50',
        ]);

        $profilePath = $request->file('institution_profile')->store('nazhir/profiles', 'public');
        $certPath = $request->file('lsp_certificate')->store('nazhir/certificates', 'public');

        NazhirApplication::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'institution_name' => $request->institution_name,
                'institution_short_profile' => $request->institution_short_profile,
                'phone' => $request->phone,
                'address' => $request->address,
                'institution_profile_path' => $profilePath,
                'lsp_certificate_path' => $certPath,
                'bank_name' => $request->bank_name,
                'bank_account_name' => $request->bank_account_name,
                'bank_account_number' => $request->bank_account_number,
                'status' => 'PENDING',
                'rejection_reason' => null,
            ]
        );

        return back()->with('success', 'Pengajuan sebagai Pelaksana Nazhir berhasil dikirim. Tunggu konfirmasi dari Admin.');
    }
}
