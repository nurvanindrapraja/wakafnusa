<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disbursement extends Model
{
    protected $fillable = [
        'program_id', 'report_id', 'amount', 'phase',
        'status', 'approved_by', 'disbursed_at', 'notes', 'proof_path',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'disbursed_at' => 'datetime',
        ];
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
