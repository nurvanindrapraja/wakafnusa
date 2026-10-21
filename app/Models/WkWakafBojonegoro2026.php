<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WkWakafBojonegoro2026 extends Model
{
    protected $connection = 'mysql_no_prefix';
    protected $table = 'wk_wakaf_bojonegoro_2026';
    protected $guarded = [];

    /**
     * Relationship to Location (wk_lokasi)
     */
    public function lokasi()
    {
        return $this->belongsTo(Location::class, 'kode_lokasi', 'kode_lokasi');
    }

    /**
     * Relationship to Usage (wk_penggunaan)
     */
    public function penggunaan()
    {
        return $this->belongsTo(Usage::class, 'kode_peng', 'KODE_PENG');
    }
}
