<?php

namespace App\Http\Controllers\Wakif;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    /**
     * Download certificate as PDF.
     */
    public function download(Certificate $certificate)
    {
        // Permission check
        if ($certificate->wakif_id) {
            if (!auth()->check() || (auth()->user()->role === 'WAKIF' && $certificate->wakif_id != auth()->id())) {
                abort(403, 'Anda tidak memiliki akses ke sertifikat ini.');
            }
        }

        $certificate->load('donation.program', 'wakif');
        $settings = \App\Models\SystemSetting::all()->pluck('value', 'key');

        // Generate QR Code (using SVG with increased size and margin for better scannability)
        $verifyUrl = route('certificate.verify', $certificate->certificate_number);
        $qrcode = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(300)->margin(1)->generate($verifyUrl));

        $pdf = Pdf::loadView('wakif.certificates.pdf', [
            'certificate' => $certificate,
            'settings' => $settings,
            'qrcode' => $qrcode
        ]);

        $pdf->setPaper('a4', 'landscape');

        $filename = 'sertifikat-wakaf-' . str_replace('/', '-', $certificate->certificate_number) . '.pdf';

        if (request()->has('stream')) {
            return $pdf->stream($filename);
        }

        return $pdf->download($filename);
    }

    /**
     * Verify certificate authenticity.
     */
    public function verify($number)
    {
        $certificate = Certificate::where('certificate_number', $number)->with('donation.program', 'wakif')->firstOrFail();
        return view('public.certificate-verify', compact('certificate'));
    }
}
