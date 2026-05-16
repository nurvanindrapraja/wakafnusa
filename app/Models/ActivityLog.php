<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'action', 'target_type', 'target_id',
        'description', 'ip_address', 'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function target()
    {
        return $this->morphTo();
    }

    // ── Helper to create log entries ─────────────────────────────

    public static function log(string $action, string $description, ?Model $target = null, array $metadata = []): static
    {
        return static::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'target_type' => $target ? get_class($target) : null,
            'target_id' => $target?->id,
            'description' => $description,
            'ip_address' => request()->ip(),
            'metadata' => $metadata ?: null,
        ]);
    }
}
