<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InfaqWithdrawal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'withdrawal_date',
        'amount',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'purpose',
        'proof_path',
        'created_by',
    ];

    protected $casts = [
        'withdrawal_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
