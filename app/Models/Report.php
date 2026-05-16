<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id', 'submitted_by', 'report_period', 'title',
        'description', 'realized_amount', 'evidence_docs',
        'evidence_photos', 'status', 'validated_by', 'validated_at',
        'rejection_notes',
    ];

    protected function casts(): array
    {
        return [
            'realized_amount' => 'decimal:2',
            'evidence_docs' => 'array',
            'evidence_photos' => 'array',
            'validated_at' => 'datetime',
        ];
    }

    // ── Relationships ────────────────────────────────────────────

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function disbursement()
    {
        return $this->hasOne(Disbursement::class);
    }
}
