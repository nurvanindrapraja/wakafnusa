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
        Schema::table('wk_pengajuan', function (Blueprint $table) {
            $table->string('nomor_pengajuan')->nullable()->after('id');
            $table->string('status')->default('PENDING')->after('hp_wa'); // PENDING, VERIFIKASI_LAPANGAN, SELESAI, DIBATALKAN
            $table->date('tanggal_verifikasi')->nullable()->after('status');
            $table->text('alasan_batal')->nullable()->after('tanggal_verifikasi');
            $table->unsignedInteger('processed_by')->nullable()->after('alasan_batal');
            $table->timestamp('processed_at')->nullable()->after('processed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wk_pengajuan', function (Blueprint $table) {
            $table->dropColumn([
                'nomor_pengajuan',
                'status',
                'tanggal_verifikasi',
                'alasan_batal',
                'processed_by',
                'processed_at'
            ]);
        });
    }
};
