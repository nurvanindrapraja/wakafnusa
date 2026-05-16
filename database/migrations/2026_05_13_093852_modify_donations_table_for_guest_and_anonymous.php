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
        Schema::table('donations', function (Blueprint $table) {
            $table->foreignId('wakif_id')->nullable()->change();
            $table->string('donor_name')->nullable()->after('wakif_id');
            $table->string('donor_phone')->nullable()->after('donor_name');
            $table->boolean('is_anonymous')->default(false)->after('donor_phone');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->foreignId('wakif_id')->nullable(false)->change();
            $table->dropColumn(['donor_name', 'donor_phone', 'is_anonymous']);
        });
    }
};
