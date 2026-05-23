<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentCallbackTest extends TestCase
{
    use RefreshDatabase;

    protected $token = 'test_secret_callback_token_123';

    protected function setUp(): void
    {
        parent::setUp();

        // Configure payment services config dynamically for testing
        config(['services.payment.callback_token' => $this->token]);
    }

    public function test_unauthorized_callback_requests_are_rejected()
    {
        $response = $this->postJson('/api/payment/callback', [
            'donation_code' => 'WKF-12345',
            'payment_status' => 'success',
            'amount' => 50000,
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'success' => false,
            'message' => 'Unauthorized: Invalid or missing security token.'
        ]);
    }

    public function test_invalid_payloads_are_rejected()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/payment/callback', [
            // Missing donation_code and payment_status
            'amount' => 50000,
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'success',
            'message',
            'errors' => [
                'donation_code',
                'payment_status'
            ]
        ]);
    }

    public function test_non_existent_donation_returns_404()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/payment/callback', [
            'donation_code' => 'NON-EXISTENT-CODE',
            'payment_status' => 'success',
            'amount' => 50000,
        ]);

        $response->assertStatus(404);
        $response->assertJson([
            'success' => false,
            'message' => 'Donation not found.'
        ]);
    }

    public function test_mismatched_amount_returns_400()
    {
        $pelaksana = User::factory()->create(['role' => 'PELAKSANA']);

        $program = Program::create([
            'pelaksana_id' => $pelaksana->id,
            'title' => 'Program Test',
            'category' => 'SOSIAL',
            'description' => 'Test description',
            'location_province' => 'Jawa Timur',
            'location_city' => 'Surabaya',
            'target_amount' => 10000000,
            'collected_amount' => 0,
            'rab_total' => 1000000,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDays(10),
            'status' => 'PUBLISHED',
        ]);

        $donation = Donation::create([
            'donor_name' => 'John Doe',
            'program_id' => $program->id,
            'amount' => 50000,
            'service_fee' => 0,
            'payment_method' => 'QRIS',
            'payment_status' => 'PENDING',
        ]);

        // Send incorrect amount: 40000 instead of 50000
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/payment/callback', [
            'donation_code' => $donation->donation_code,
            'payment_status' => 'success',
            'amount' => 40000,
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'Amount mismatch. Expected: 50000, Got: 40000.'
        ]);
    }

    public function test_successful_callback_flags_payment_and_updates_collected_amount()
    {
        $pelaksana = User::factory()->create(['role' => 'PELAKSANA']);

        $program = Program::create([
            'pelaksana_id' => $pelaksana->id,
            'title' => 'Program Test',
            'category' => 'SOSIAL',
            'description' => 'Test description',
            'location_province' => 'Jawa Timur',
            'location_city' => 'Surabaya',
            'target_amount' => 10000000,
            'collected_amount' => 100000,
            'rab_total' => 1000000,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDays(10),
            'status' => 'PUBLISHED',
        ]);

        $donation = Donation::create([
            'donor_name' => 'John Doe',
            'program_id' => $program->id,
            'amount' => 50000,
            'service_fee' => 2000,
            'payment_method' => 'QRIS',
            'payment_status' => 'PENDING',
        ]);

        $totalAmountExpected = 52000; // amount (50000) + service_fee (2000)

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/payment/callback', [
            'donation_code' => $donation->donation_code,
            'payment_status' => 'success',
            'amount' => $totalAmountExpected,
            'payment_channel' => 'GOPAY',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Payment processed and flagged as PAID successfully.'
        ]);

        // Assert database changes
        $donation->refresh();
        $this->assertEquals('PAID', $donation->payment_status);
        $this->assertEquals('GOPAY', $donation->payment_channel);
        $this->assertNotNull($donation->paid_at);

        $program->refresh();
        // Program collected_amount should be incremented by donation amount (50000)
        $this->assertEquals(150000, $program->collected_amount);

        // Receipt certificate should be generated
        $this->assertDatabaseHas('certificates', [
            'donation_id' => $donation->id,
            'type' => 'BUKTI_WAKAF'
        ]);
    }

    public function test_expired_callback_updates_status_to_expired()
    {
        $pelaksana = User::factory()->create(['role' => 'PELAKSANA']);

        $program = Program::create([
            'pelaksana_id' => $pelaksana->id,
            'title' => 'Program Test',
            'category' => 'SOSIAL',
            'description' => 'Test description',
            'location_province' => 'Jawa Timur',
            'location_city' => 'Surabaya',
            'target_amount' => 10000000,
            'collected_amount' => 0,
            'rab_total' => 1000000,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDays(10),
            'status' => 'PUBLISHED',
        ]);

        $donation = Donation::create([
            'donor_name' => 'John Doe',
            'program_id' => $program->id,
            'amount' => 50000,
            'service_fee' => 0,
            'payment_method' => 'QRIS',
            'payment_status' => 'PENDING',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/payment/callback', [
            'donation_code' => $donation->donation_code,
            'payment_status' => 'expire',
            'amount' => 50000,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Payment flagged as EXPIRED.'
        ]);

        $donation->refresh();
        $this->assertEquals('EXPIRED', $donation->payment_status);
    }
}
