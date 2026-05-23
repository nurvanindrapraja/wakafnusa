<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class LwpUser extends Authenticatable
{
    use Notifiable;

    protected $connection = 'mysql_no_prefix';
    protected $table = 'wk_user';
    protected $primaryKey = 'ID_USER'; // Uppercase

    /**
     * Get the current connection name for the model.
     *
     * @return string|null
     */
    public function getConnectionName()
    {
        return app()->environment('testing') ? config('database.default') : $this->connection;
    }

    protected $fillable = [
        'ID_CABANG',
        'NAMA_USER',
        'USERNAME_USER',
        'PASSWORD_USER',
        'EMAIL_USER',
        'TELP_USER',
        'LEVEL',
        'TOKEN',
        'KODE_LOKASI',
        'PATH_PROFIL',
        'PATH_KARTANU',
        'NO_KARTANU',
        'ALAMAT_USER',
        'ISDEL',
        'RESPASS',
    ];

    protected $hidden = [
        'PASSWORD_USER',
    ];

    public $timestamps = false;

    public function getAuthPassword()
    {
        return $this->PASSWORD_USER;
    }
}
