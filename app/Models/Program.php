<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Program extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pelaksana_id', 'title', 'slug', 'category', 'description',
        'target_amount', 'collected_amount', 'location_province', 'location_city',
        'location_detail', 'beneficiary_count', 'start_date', 'end_date',
        'rab_total', 'rab_operational_pct', 'rab_document', 'implementation_plan_doc', 'supporting_docs',
        'cover_image', 'gallery', 'status', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'target_amount' => 'decimal:2',
            'collected_amount' => 'decimal:2',
            'rab_total' => 'decimal:2',
            'rab_operational_pct' => 'decimal:2',
            'supporting_docs' => 'array',
            'gallery' => 'array',
            'start_date' => 'date',
            'end_date' => 'date',
            'published_at' => 'datetime',
        ];
    }

    // ── Auto-generate slug ───────────────────────────────────────

    protected static function booted(): void
    {
        static::creating(function (Program $program) {
            if (empty($program->slug)) {
                $program->slug = Str::slug($program->title) . '-' . Str::random(6);
            }
        });
    }

    // ── Computed Attributes ──────────────────────────────────────

    public function getProgressPercentageAttribute(): float
    {
        if ($this->target_amount <= 0) return 0;
        return min(100, round(($this->collected_amount / $this->target_amount) * 100, 1));
    }

    public function getDaysRemainingAttribute(): int
    {
        return max(0, now()->diffInDays($this->end_date, false));
    }

    public function getDonorCountAttribute(): int
    {
        return $this->donations()->where('payment_status', 'PAID')->count();
    }

    public function getIsDonationOpenAttribute(): bool
    {
        if ($this->status === 'COMPLETED') return false;
        if ($this->collected_amount >= $this->target_amount) return false;
        if (now()->startOfDay()->gt($this->end_date)) return false;
        if (is_null($this->published_at)) return false;
        
        return in_array($this->status, ['PUBLISHED', 'FUNDED', 'IMPLEMENTING']);
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->status === 'COMPLETED' || $this->collected_amount >= $this->target_amount;
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->is_completed) return 'Selesai';
        if (now()->startOfDay()->gt($this->end_date)) return 'Periode Berakhir';
        
        return match($this->status) {
            'PUBLISHED' => 'Berjalan',
            'FUNDED' => 'Terdanai',
            'IMPLEMENTING' => 'Pelaksanaan',
            'COMPLETED' => 'Selesai',
            default => $this->status
        };
    }

    public function getFormattedTargetAttribute(): string
    {
        return 'Rp ' . number_format($this->target_amount, 0, ',', '.');
    }

    public function getFormattedCollectedAttribute(): string
    {
        return 'Rp ' . number_format($this->collected_amount, 0, ',', '.');
    }

    // ── Scopes ───────────────────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->where('status', 'PUBLISHED');
    }

    public function scopeActive($query)
    {
        return $query->whereNotNull('published_at')->whereIn('status', ['PUBLISHED', 'FUNDED', 'IMPLEMENTING', 'COMPLETED']);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    // ── Relationships ────────────────────────────────────────────

    public function pelaksana()
    {
        return $this->belongsTo(User::class, 'pelaksana_id');
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function latestReview()
    {
        return $this->hasOne(Review::class)->latestOfMany();
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function pks()
    {
        return $this->hasOne(Pks::class);
    }

    public function disbursements()
    {
        return $this->hasMany(Disbursement::class);
    }

    public function certificates()
    {
        return $this->hasManyThrough(Certificate::class, Donation::class);
    }
}
