<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NazhirApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'institution_name',
        'institution_short_profile',
        'phone',
        'address',
        'institution_profile_path',
        'lsp_certificate_path',
        'bank_name',
        'bank_account_name',
        'bank_account_number',
        'status',
        'rejection_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
