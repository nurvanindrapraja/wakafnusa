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
