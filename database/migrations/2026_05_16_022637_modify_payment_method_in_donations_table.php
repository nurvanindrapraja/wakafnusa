<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Enums in MySQL are best changed via raw SQL
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE wn_donations MODIFY COLUMN payment_method ENUM('VIRTUAL_ACCOUNT', 'QRIS', 'OFFLINE')");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE wn_donations MODIFY COLUMN payment_method ENUM('VIRTUAL_ACCOUNT', 'QRIS')");
        }
    }
};
