<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentCallbackController extends Controller
{
    public function __construct(
        private PaymentService $paymentService
    ) {}

    /**
     * Handle payment gateway callback to flag waqf donation payment success.
     */
    public function handle(Request $request)
    {
        // 1. Verify Security Token
        $token = $request->bearerToken() ?? $request->input('callback_token');
        $expectedToken = config('services.payment.callback_token');

        if (empty($expectedToken) || $token !== $expectedToken) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Invalid or missing security token.'
            ], 401);
        }

        // 2. Validate Payload
        $validator = Validator::make($request->all(), [
            'donation_code' => ['required', 'string'],
            'payment_status' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:0'],
            'payment_channel' => ['nullable', 'string', 'max:50'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $validator->errors()
            ], 422);
        }

        // 3. Find Donation Record
        $donation = Donation::where('donation_code', $request->donation_code)->first();

        if (!$donation) {
            return response()->json([
                'success' => false,
                'message' => 'Donation not found.'
            ], 404);
        }

        // 4. Validate Amount
        // Ensure paid amount matches total amount (donation + service fee)
        $expectedTotal = (float) $donation->total_amount;
        $paidAmount = (float) $request->amount;

        if (abs($paidAmount - $expectedTotal) > 0.01) {
            return response()->json([
                'success' => false,
                'message' => "Amount mismatch. Expected: {$expectedTotal}, Got: {$paidAmount}."
            ], 400);
        }

        // 5. Check if already paid
        if ($donation->isPaid()) {
            return response()->json([
                'success' => true,
                'message' => 'Donation was already flagged as PAID.',
                'data' => [
                    'donation_code' => $donation->donation_code,
                    'payment_status' => $donation->payment_status,
                    'paid_at' => $donation->paid_at ? $donation->paid_at->toIso8601String() : null
                ]
            ], 200);
        }

        // 6. Flag successful payment
        $successStatuses = ['success', 'settlement', 'capture', 'paid'];
        $incomingStatus = strtolower($request->payment_status);

        if (in_array($incomingStatus, $successStatuses)) {
            // Update channel/method details if provided
            $updateData = [];
            if ($request->filled('payment_channel')) {
                $updateData['payment_channel'] = $request->payment_channel;
            }
            if ($request->filled('payment_method')) {
                $updateData['payment_method'] = $request->payment_method;
            }
            if (!empty($updateData)) {
                $donation->update($updateData);
            }

            // Confirm payment using service
            $this->paymentService->confirmPayment($donation);

            return response()->json([
                'success' => true,
                'message' => 'Payment processed and flagged as PAID successfully.',
                'data' => [
                    'donation_code' => $donation->donation_code,
                    'payment_status' => $donation->payment_status,
                    'paid_at' => $donation->paid_at ? $donation->paid_at->toIso8601String() : null
                ]
            ], 200);
        }

        // Handle other payment states (e.g. pending, expired, denied)
        if (in_array($incomingStatus, ['expire', 'expired', 'cancel', 'deny', 'failed'])) {
            $donation->update(['payment_status' => 'EXPIRED']);
            
            return response()->json([
                'success' => true,
                'message' => 'Payment flagged as EXPIRED.',
                'data' => [
                    'donation_code' => $donation->donation_code,
                    'payment_status' => $donation->payment_status
                ]
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment status received but no action required.',
            'data' => [
                'donation_code' => $donation->donation_code,
                'payment_status' => $donation->payment_status
            ]
        ], 200);
    }
}
