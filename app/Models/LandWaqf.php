<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LandWaqf extends Model
{
    protected $connection = 'mysql_no_prefix';
    protected $table = 'vw_wakaf_detail';
    protected $primaryKey = 'ID_WAKAF';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'ID_WAKAF' => 'integer',
        'LUAS' => 'float',
        'TGL_AIW' => 'date',
        'TGL_SERT' => 'date',
        'ISDEL' => 'integer',
        'ISOK' => 'integer',
    ];

    protected $appends = ['nia'];

    public function getNiaAttribute()
    {
        return DB::connection('mysql_no_prefix')
            ->table('wk_wakaf')
            ->where('ID_WAKAF', $this->ID_WAKAF)
            ->value('NIA');
    }
}
