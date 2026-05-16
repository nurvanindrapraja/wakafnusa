<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_code', 'wakif_id', 'donor_name', 'donor_phone', 'is_anonymous',
        'is_offline', 'payment_proof', 'program_id', 'amount', 'service_fee',
        'payment_method', 'payment_channel', 'payment_code',
        'payment_status', 'paid_at', 'expired_at', 'aiw_data',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'service_fee' => 'decimal:2',
            'paid_at' => 'datetime',
            'expired_at' => 'datetime',
            'aiw_data' => 'array',
            'is_anonymous' => 'boolean',
        ];
    }

    // ── Auto-generate donation code ──────────────────────────────

    protected static function booted(): void
    {
        static::creating(function (Donation $donation) {
            if (empty($donation->donation_code)) {
                $donation->donation_code = 'WKF-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
            }
        });
    }

    // ── Helpers ──────────────────────────────────────────────────

    public function isPaid(): bool
    {
        return $this->payment_status === 'PAID';
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getFormattedServiceFeeAttribute(): string
    {
        return 'Rp ' . number_format($this->service_fee, 0, ',', '.');
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->amount + $this->service_fee, 0, ',', '.');
    }

    public function getTotalAmountAttribute(): float
    {
        return (float) ($this->amount + $this->service_fee);
    }

    public function getDonorDisplayNameAttribute(): string
    {
        if ($this->is_anonymous) {
            return 'Hamba Allah';
        }

        if ($this->wakif_id) {
            return $this->wakif->name;
        }

        return $this->donor_name ?? 'Hamba Allah';
    }

    // ── Relationships ────────────────────────────────────────────

    public function wakif()
    {
        return $this->belongsTo(User::class, 'wakif_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
}
