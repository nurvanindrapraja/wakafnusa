<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'donation_id', 'wakif_id', 'type',
        'certificate_number', 'file_path', 'issued_at',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
        ];
    }

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    public function wakif()
    {
        return $this->belongsTo(User::class, 'wakif_id');
    }
}
