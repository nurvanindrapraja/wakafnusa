<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wk_pengajuan', function (Blueprint $table) {
            $table->id();
            
            // Location
            $table->string('provinsi_id');
            $table->string('provinsi');
            $table->string('kabupaten_id');
            $table->string('kabupaten');
            $table->string('kecamatan_id');
            $table->string('kecamatan');
            $table->string('kelurahan_id');
            $table->string('kelurahan');
            $table->text('alamat');
            
            // Asset details
            $table->string('nama_aset')->nullable();
            $table->decimal('luas', 15, 2);
            $table->string('nama_wakif');
            $table->string('penggunaan');
            
            // Ikrar
            $table->boolean('sudah_ikrar')->default(false);
            $table->string('nomor_ikrar')->nullable();
            $table->date('tanggal_ikrar')->nullable();
            $table->string('nama_nazhir')->nullable();
            
            // Certificate
            $table->boolean('sudah_sertifikat')->default(false);
            $table->string('nomor_sertifikat')->nullable();
            $table->date('tanggal_sertifikat')->nullable();
            $table->string('atas_nama_sertifikat')->nullable();
            $table->string('jenis_sertifikat')->nullable(); // SHM, SHGB, WAKAF
            
            // Intended Action & Contact
            $table->string('tindakan_aset'); // WAKAF_NU, ASET_NU, GANTI_NAZHIR_NU
            $table->text('keterangan')->nullable();
            $table->string('hp_wa');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wk_pengajuan');
    }
};
