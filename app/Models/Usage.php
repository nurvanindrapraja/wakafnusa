<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usage extends Model
{
    protected $connection = 'mysql_no_prefix';
    protected $table = 'wk_penggunaan';
    protected $primaryKey = 'KODE_PENG';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'KODE_PENG',
        'PENGGUNAAN',
        'KODE_PENG_OLD',
        'ICON',
        'ISDEL',
    ];

    /**
     * Scope for active records
     */
    public function scopeActive($query)
    {
        return $query->where('ISDEL', 0);
    }

    /**
     * Check if this is a parent category
     */
    public function isParent(): bool
    {
        return !str_contains($this->KODE_PENG, '.');
    }

    /**
     * Get parent category code
     */
    public function getParentCodeAttribute(): ?string
    {
        if (str_contains($this->KODE_PENG, '.')) {
            return explode('.', $this->KODE_PENG)[0];
        }
        return null;
    }

    /**
     * Get the current connection name for the model.
     *
     * @return string|null
     */
    public function getConnectionName()
    {
        return app()->environment('testing') ? config('database.default') : $this->connection;
    }
}
