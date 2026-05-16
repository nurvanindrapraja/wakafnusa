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
        Schema::table('nazhir_applications', function (Blueprint $table) {
            $table->string('bank_name')->after('lsp_certificate_path')->nullable();
            $table->string('bank_account_name')->after('bank_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nazhir_applications', function (Blueprint $table) {
            $table->dropColumn(['bank_name', 'bank_account_name']);
        });
    }
};
