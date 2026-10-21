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
        $connection = app()->environment('testing') ? config('database.default') : 'mysql_no_prefix';
        if (!Schema::connection($connection)->hasTable('wk_wakaf_bojonegoro_2026')) {
            Schema::connection($connection)->create('wk_wakaf_bojonegoro_2026', function (Blueprint $table) {
                $table->id();
                $table->string('no', 20)->nullable();
                $table->string('nia', 20)->nullable()->index();
                $table->string('kecamatan', 100)->nullable();
                $table->string('desa', 100)->nullable();
                $table->string('kode_lokasi', 20)->nullable()->index();
                $table->string('nomor_hak', 100)->nullable();
                $table->string('aiw_apaiw', 100)->nullable();
                $table->text('nazhir')->nullable();
                $table->decimal('luas', 12, 2)->nullable();
                $table->string('peruntukan', 100)->nullable();
                $table->string('kode_peng', 20)->nullable()->index();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $connection = app()->environment('testing') ? config('database.default') : 'mysql_no_prefix';
        Schema::connection($connection)->dropIfExists('wk_wakaf_bojonegoro_2026');
    }
};
