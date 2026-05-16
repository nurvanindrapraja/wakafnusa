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
            $table->text('institution_short_profile')->after('institution_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nazhir_applications', function (Blueprint $table) {
            $table->dropColumn('institution_short_profile');
        });
    }
};
