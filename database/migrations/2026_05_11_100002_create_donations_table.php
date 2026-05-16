<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('donation_code')->unique();
            $table->foreignId('wakif_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('program_id')->constrained('programs')->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->enum('payment_method', ['VIRTUAL_ACCOUNT', 'QRIS']);
            $table->string('payment_channel')->nullable();
            $table->string('payment_code')->nullable();
            $table->enum('payment_status', ['PENDING', 'PAID', 'EXPIRED', 'FAILED'])->default('PENDING');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->json('aiw_data')->nullable()->comment('e-AIW (Akta Ikrar Wakaf) form data');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
