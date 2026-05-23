<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'phone', 'nik', 'address', 'institution_short_profile', 'province', 'city', 'district', 'avatar', 'role', 'is_active', 'sk_number', 'lsp_certificate', 'institution_profile_doc', 'bank_name', 'bank_account_number', 'bank_account_name'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // ── Role Helpers ─────────────────────────────────────────────

    public function isWakif(): bool
    {
        return $this->role === 'WAKIF';
    }

    public function isPelaksana(): bool
    {
        return $this->role === 'PELAKSANA';
    }

    public function isPenilai(): bool
    {
        return $this->role === 'PENILAI';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'ADMIN';
    }

    // ── Relationships ────────────────────────────────────────────

    public function donations()
    {
        return $this->hasMany(Donation::class, 'wakif_id');
    }

    public function programs()
    {
        return $this->hasMany(Program::class, 'pelaksana_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'submitted_by');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'wakif_id');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPasswordIndonesian($token));
    }
}
