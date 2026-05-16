<?php

namespace App\Http\Controllers\Wakif;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Program;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function __construct(
        private PaymentService $paymentService
    ) {}

    /**
     * Step 1: Show donation form (amount + method).
     */
    public function create(string $slug)
    {
        $program = Program::where('slug', $slug)
            ->whereIn('status', ['PUBLISHED', 'FUNDED', 'IMPLEMENTING'])
            ->firstOrFail();

        $sidebar = $this->getSidebar();

        return view('wakif.donation.create', compact('program', 'sidebar'));
    }

    private function getSidebar()
    {
        if (!auth()->check()) return 'dashboard.wakif.partials.nav';
        
        return match(auth()->user()->role) {
            'ADMIN' => 'dashboard.admin.partials.nav',
            'PELAKSANA' => 'dashboard.pelaksana.partials.nav',
            'PENILAI' => 'dashboard.penilai.partials.nav',
            default => 'dashboard.wakif.partials.nav'
        };
    }

    /**
     * Step 2: Show e-AIW form.
     */
    public function aiw(Request $request, string $slug)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'amount' => ['required', 'numeric', 'min:10000'],
                'service_fee' => ['required', 'numeric', 'min:0'],
                'payment_method' => ['required', 'in:VIRTUAL_ACCOUNT,QRIS'],
                'donor_name' => ['nullable', 'string', 'max:255'],
                'donor_phone' => ['nullable', 'string', 'max:20'],
                'is_anonymous' => ['nullable', 'boolean'],
            ]);

            $program = Program::where('slug', $slug)
                ->whereIn('status', ['PUBLISHED', 'FUNDED', 'IMPLEMENTING'])
                ->firstOrFail();

            // Store in session for next step
            session([
                'donation_data' => [
                    'program_id' => $program->id,
                    'amount' => $request->amount,
                    'service_fee' => $request->service_fee,
                    'payment_method' => $request->payment_method,
                    'donor_name' => $request->donor_name,
                    'donor_phone' => $request->donor_phone,
                    'is_anonymous' => $request->has('is_anonymous'),
                ]
            ]);
        } else {
            // Handle GET (back redirect from validation error in store)
            $donationData = session('donation_data');
            if (!$donationData) {
                return redirect()->route('wakaf.create', $slug)
                    ->with('error', 'Silakan pilih nominal wakaf terlebih dahulu.');
            }
            $program = Program::where('slug', $slug)
                ->whereIn('status', ['PUBLISHED', 'FUNDED', 'IMPLEMENTING'])
                ->firstOrFail();
        }

        $sidebar = $this->getSidebar();
        return view('wakif.donation.aiw', compact('program', 'sidebar'));
    }

    /**
     * Step 3: Process donation and show payment page.
     */
    public function store(Request $request)
    {
        $request->validate([
            'wakif_name' => ['required', 'string'],
            'wakif_address' => ['nullable', 'string'],
            'wakif_nik' => ['nullable', 'string', 'size:16'],
            'niat_wakaf' => ['required', 'string'],
            'agreement' => ['required', 'accepted'],
        ]);

        $donationData = session('donation_data');
        if (!$donationData) {
            return redirect()->route('catalog')->with('error', 'Sesi donasi tidak valid.');
        }

        $program = Program::findOrFail($donationData['program_id']);

        // Save AIW data
        $aiwData = [
            'wakif_name' => $request->wakif_name,
            'wakif_address' => $request->wakif_address,
            'wakif_nik' => $request->wakif_nik,
            'niat_wakaf' => $request->niat_wakaf,
            'signed_at' => now()->toISOString(),
            'program_title' => $program->title,
        ];

        // Create donation record
        $donation = Donation::create([
            'wakif_id' => auth()->id(),
            'donor_name' => $donationData['donor_name'],
            'donor_phone' => $donationData['donor_phone'],
            'is_anonymous' => $donationData['is_anonymous'] ?? false,
            'program_id' => $program->id,
            'amount' => $donationData['amount'],
            'service_fee' => $donationData['service_fee'] ?? 0,
            'payment_method' => $donationData['payment_method'],
            'payment_status' => 'PENDING',
            'aiw_data' => $aiwData,
        ]);

        // Generate payment
        $this->paymentService->createPayment($donation);

        // Clear session
        session()->forget('donation_data');

        return redirect()->route('wakaf.payment', $donation->id);
    }

    /**
     * Show payment instructions page.
     */
    public function payment(Donation $donation)
    {
        $sidebar = $this->getSidebar();
        return view('wakif.donation.payment', compact('donation', 'sidebar'));
    }

    /**
     * Mock: Simulate payment confirmation.
     */
    public function confirmMock(Donation $donation)
    {
        $this->paymentService->confirmPayment($donation);

        return redirect()->route('wakaf.success', $donation->id)
            ->with('success', 'Pembayaran berhasil dikonfirmasi!');
    }

    /**
     * Show success page after payment.
     */
    public function success(Donation $donation)
    {
        $donation->load('program', 'certificates');

        $sidebar = $this->getSidebar();
        return view('wakif.donation.success', compact('donation', 'sidebar'));
    }

    /**
     * Download or Stream e-AIW PDF.
     */
    public function aiwDownload(Donation $donation)
    {
        $donation->load('program');
        $settings = \App\Models\SystemSetting::all()->pluck('value', 'key');
        
        // Generate QR Code
        $verifyUrl = route('wakaf.aiw.verify', $donation->donation_code);
        $qrcode = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(200)->margin(1)->generate($verifyUrl));

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('wakif.donation.aiw-pdf', [
            'donation' => $donation,
            'settings' => $settings,
            'qrcode' => $qrcode
        ]);

        // Set paper to portrait
        $pdf->setPaper('a4', 'portrait');

        $filename = 'e-AIW-' . $donation->donation_code . '.pdf';

        if (request()->has('stream')) {
            return $pdf->stream($filename);
        }

        return $pdf->download($filename);
    }

    /**
     * Verify AIW authenticity.
     */
    public function verifyAiw($code)
    {
        $donation = Donation::where('donation_code', $code)->with('program', 'wakif')->firstOrFail();
        return view('public.aiw-verify', compact('donation'));
    }
}
