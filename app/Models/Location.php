<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $connection = 'mysql_no_prefix';
    protected $table = 'wk_lokasi';
    protected $primaryKey = 'kode_lokasi';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
        'kode_lokasi',
        'nama_lokasi',
    ];

    /**
     * Get hierarchy level: provinsi, kabupaten, kecamatan, or kelurahan
     */
    public function getLevelAttribute(): string
    {
        $len = strlen($this->kode_lokasi ?? '');
        return match($len) {
            2 => 'provinsi',
            5 => 'kabupaten',
            8 => 'kecamatan',
            13 => 'kelurahan',
            default => 'lainnya',
        };
    }

    /**
     * Get human readable level label
     */
    public function getLevelLabelAttribute(): string
    {
        return match($this->level) {
            'provinsi' => 'Provinsi',
            'kabupaten' => 'Kabupaten / Kota',
            'kecamatan' => 'Kecamatan',
            'kelurahan' => 'Desa / Kelurahan',
            default => 'Lainnya',
        };
    }

    /**
     * Get parent code if applicable
     */
    public function getParentCodeAttribute(): ?string
    {
        $len = strlen($this->kode_lokasi ?? '');
        return match($len) {
            5 => substr($this->kode_lokasi, 0, 2),
            8 => substr($this->kode_lokasi, 0, 5),
            13 => substr($this->kode_lokasi, 0, 8),
            default => null,
        };
    }

    /**
     * Scope for Provinsi (length = 2)
     */
    public function scopeProvinsi($query)
    {
        return $query->whereRaw('LENGTH(kode_lokasi) = 2');
    }

    /**
     * Scope for Kabupaten/Kota (length = 5)
     */
    public function scopeKabupaten($query, $provCode = null)
    {
        $q = $query->whereRaw('LENGTH(kode_lokasi) = 5');
        if ($provCode) {
            $q->where('kode_lokasi', 'like', $provCode . '.%');
        }
        return $q;
    }

    /**
     * Scope for Kecamatan (length = 8)
     */
    public function scopeKecamatan($query, $kabCode = null)
    {
        $q = $query->whereRaw('LENGTH(kode_lokasi) = 8');
        if ($kabCode) {
            $q->where('kode_lokasi', 'like', $kabCode . '.%');
        }
        return $q;
    }

    /**
     * Scope for Desa/Kelurahan (length = 13)
     */
    public function scopeKelurahan($query, $kecCode = null)
    {
        $q = $query->whereRaw('LENGTH(kode_lokasi) = 13');
        if ($kecCode) {
            $q->where('kode_lokasi', 'like', $kecCode . '.%');
        }
        return $q;
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
