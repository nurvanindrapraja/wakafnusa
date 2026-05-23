<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WkPengajuan extends Model
{
    use HasFactory;

    protected $table = 'wk_pengajuan';

    protected $fillable = [
        'nomor_pengajuan', 'provinsi_id', 'provinsi', 'kabupaten_id', 'kabupaten',
        'kecamatan_id', 'kecamatan', 'kelurahan_id', 'kelurahan', 'alamat',
        'nama_aset', 'luas', 'nama_wakif', 'penggunaan',
        'sudah_ikrar', 'nomor_ikrar', 'tanggal_ikrar', 'nama_nazhir',
        'sudah_sertifikat', 'nomor_sertifikat', 'tanggal_sertifikat', 'atas_nama_sertifikat', 'jenis_sertifikat',
        'tindakan_aset', 'keterangan', 'hp_wa', 'status', 'tanggal_verifikasi', 'alasan_batal', 'processed_by', 'processed_at'
    ];

    protected function casts(): array
    {
        return [
            'sudah_ikrar' => 'boolean',
            'sudah_sertifikat' => 'boolean',
            'tanggal_ikrar' => 'date',
            'tanggal_sertifikat' => 'date',
            'tanggal_verifikasi' => 'date',
            'processed_at' => 'datetime',
            'luas' => 'decimal:2',
        ];
    }

    public function processor()
    {
        return $this->belongsTo(LwpUser::class, 'processed_by', 'ID_USER');
    }
}
