<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donation_id')->constrained('donations')->cascadeOnDelete();
            $table->foreignId('wakif_id')->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['BUKTI_WAKAF', 'SERTIFIKAT_WAKAF_UANG']);
            $table->string('certificate_number')->unique();
            $table->string('file_path')->nullable();
            $table->timestamp('issued_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
