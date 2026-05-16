<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pks extends Model
{
    protected $table = 'pks';

    protected $fillable = [
        'program_id', 'pks_number', 'signed_by_admin',
        'signed_by_pelaksana', 'signed_at', 'document_path', 'status',
    ];

    protected function casts(): array
    {
        return [
            'signed_at' => 'datetime',
        ];
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'signed_by_admin');
    }

    public function pelaksana()
    {
        return $this->belongsTo(User::class, 'signed_by_pelaksana');
    }
}
