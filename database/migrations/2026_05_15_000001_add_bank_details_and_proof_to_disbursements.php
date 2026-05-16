<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('bank_name')->nullable()->after('address');
            $table->string('bank_account_number')->nullable()->after('bank_name');
            $table->string('bank_account_name')->nullable()->after('bank_account_number');
        });

        Schema::table('disbursements', function (Blueprint $table) {
            $table->string('proof_path')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['bank_name', 'bank_account_number', 'bank_account_name']);
        });

        Schema::table('disbursements', function (Blueprint $table) {
            $table->dropColumn('proof_path');
        });
    }
};
