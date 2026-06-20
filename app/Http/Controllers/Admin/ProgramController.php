<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $query = Program::with(['pelaksana', 'latestReview', 'disbursements'])->latest();

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhereHas('pelaksana', function($pq) use ($request) {
                      $pq->where('name', 'like', "%{$request->search}%");
                  });
            });
        }

        $programs = $query->paginate(15)->withQueryString();

        return view('dashboard.admin.programs.index', compact('programs'));
    }

    public function show(Program $program)
    {
        $program->load(['pelaksana', 'reviews.reviewer', 'reports', 'pks', 'donations' => function($q) {
            $q->where('is_offline', true)->latest('paid_at');
        }]);
        return view('dashboard.admin.programs.show', compact('program'));
    }

    public function verifyPks(Program $program)
    {
        if (!$program->pks || $program->pks->status !== 'SIGNED') {
            return back()->with('error', 'Dokumen PKS belum ditandatangani oleh pelaksana.');
        }

        $program->pks->update([
            'status' => 'ACTIVE',
            'signed_by_admin' => auth()->id(),
            'signed_at' => now(),
        ]);

        return back()->with('success', 'PKS berhasil diverifikasi. Program kini dapat dipublikasikan.');
    }

    public function generatePks(Request $request, Program $program)
    {
        if ($program->status !== 'RECOMMENDED') {
            return back()->with('error', 'Status program tidak valid untuk pembuatan PKS.');
        }

        $request->validate([
            'pks_document' => 'required|file|mimes:doc,docx|max:5120',
        ]);

        // Create Pks record
        $pksNumber = 'PKS/LWPNU/' . date('Y') . '/' . str_pad($program->id, 4, '0', STR_PAD_LEFT);
        
        $path = $request->file('pks_document')->store('programs/pks_drafts', 'public');

        \App\Models\Pks::updateOrCreate(
            ['program_id' => $program->id],
            [
                'pks_number' => $pksNumber,
                'status' => 'DRAFT',
                'document_path' => $path
            ]
        );

        $program->update([
            'status' => 'PKS_PENDING',
        ]);

        return back()->with('success', "Draft PKS $pksNumber berhasil dibuat. Menunggu tanda tangan pelaksana.");
    }

    public function publish(Program $program)
    {
        if (!$program->pks || $program->pks->status !== 'ACTIVE') {
            return back()->with('error', 'PKS harus diverifikasi terlebih dahulu sebelum publikasi.');
        }

        $updateData = ['published_at' => now()];
        if ($program->status === 'PKS_PENDING' || $program->status === 'RECOMMENDED') {
            $updateData['status'] = 'PUBLISHED';
        }

        $program->update($updateData);

        return back()->with('success', 'Program berhasil dipublikasikan ke katalog publik.');
    }

    public function unpublish(Program $program)
    {
        if (is_null($program->published_at)) {
            return back()->with('error', 'Program sudah tidak dipublikasikan.');
        }

        $updateData = ['published_at' => null];
        if ($program->status === 'PUBLISHED') {
            $updateData['status'] = 'PKS_PENDING';
        }

        $program->update($updateData);

        return back()->with('success', 'Program berhasil disembunyikan dari publik.');
    }

    public function extendPeriod(Request $request, Program $program)
    {
        $request->validate([
            'end_date' => 'required|date|after:today',
        ]);

        $program->update([
            'end_date' => $request->end_date,
        ]);

        return back()->with('success', 'Periode program berhasil diperpanjang hingga ' . $program->end_date->format('d M Y'));
    }

    public function storeOfflineDonation(Request $request, Program $program)
    {
        $request->validate([
            'donor_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1000',
            'paid_at' => 'required|date|before_or_equal:today',
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'is_anonymous' => 'nullable|boolean',
        ]);

        $proofPath = $request->file('payment_proof')->store('donations/offline_proofs', 'public');

        $donation = \App\Models\Donation::create([
            'program_id' => $program->id,
            'donor_name' => $request->donor_name,
            'amount' => $request->amount,
            'paid_at' => $request->paid_at,
            'payment_status' => 'PAID',
            'payment_method' => 'OFFLINE',
            'payment_channel' => 'DIRECT_BANK',
            'is_offline' => true,
            'is_anonymous' => $request->has('is_anonymous'),
            'payment_proof' => $proofPath,
            'service_fee' => 0,
        ]);

        // Update collected amount in program
        $program->increment('collected_amount', $donation->amount);

        return back()->with('success', 'Dana offline berhasil dicatat dan menambah total perolehan program.');
    }
}
