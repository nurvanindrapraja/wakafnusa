<?php

namespace App\Services;

use App\Models\Donation;
use App\Models\Program;
use App\Models\Certificate;
use Illuminate\Support\Str;

class PaymentService
{
    /**
     * Generate mock payment details for a donation.
     */
    public function createPayment(Donation $donation): array
    {
        $paymentCode = match ($donation->payment_method) {
            'VIRTUAL_ACCOUNT' => $this->generateVA($donation),
            'QRIS' => $this->generateQRIS($donation),
            default => $this->generateVA($donation),
        };

        $donation->update([
            'payment_code' => $paymentCode,
            'expired_at' => now()->addHours(24),
        ]);

        return [
            'payment_code' => $paymentCode,
            'payment_method' => $donation->payment_method,
            'expired_at' => $donation->expired_at,
            'amount' => $donation->total_amount,
        ];
    }

    /**
     * Simulate confirming a payment (mock gateway callback).
     */
    public function confirmPayment(Donation $donation): bool
    {
        if ($donation->payment_status === 'PAID') {
            return true;
        }

        $donation->update([
            'payment_status' => 'PAID',
            'paid_at' => now(),
        ]);

        // Update program collected amount
        $program = $donation->program;
        $program->increment('collected_amount', $donation->amount);

        // Check if program is fully funded
        if ($program->collected_amount >= $program->target_amount) {
            $program->update(['status' => 'COMPLETED']);
        }

        // Auto-generate receipt certificate
        $this->generateReceipt($donation);

        return true;
    }

    /**
     * Generate a virtual account number (mock).
     */
    private function generateVA(Donation $donation): string
    {
        $bankPrefixes = [
            '8800' => 'Bank Syariah Indonesia',
            '7700' => 'Bank Muamalat',
        ];
        $prefix = array_rand($bankPrefixes);
        return $prefix . str_pad($donation->id ?? rand(1000, 9999), 12, '0', STR_PAD_LEFT);
    }

    /**
     * Generate a QRIS string (mock).
     */
    private function generateQRIS(Donation $donation): string
    {
        return 'QRIS-WAKAFNUSA-' . strtoupper(Str::random(16));
    }

    /**
     * Generate receipt certificate after payment.
     */
    private function generateReceipt(Donation $donation): Certificate
    {
        return Certificate::create([
            'donation_id' => $donation->id,
            'wakif_id' => $donation->wakif_id ?? null,
            'type' => 'BUKTI_WAKAF',
            'certificate_number' => 'BWU/' . now()->format('Y/m') . '/' . str_pad($donation->id, 5, '0', STR_PAD_LEFT),
            'issued_at' => now(),
        ]);
    }
}
