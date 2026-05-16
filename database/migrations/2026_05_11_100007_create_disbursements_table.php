<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disbursements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('programs')->cascadeOnDelete();
            $table->foreignId('report_id')->nullable()->constrained('reports')->nullOnDelete();
            $table->decimal('amount', 15, 2);
            $table->integer('phase')->default(1);
            $table->enum('status', ['PENDING', 'APPROVED', 'DISBURSED', 'LOCKED'])->default('PENDING');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('disbursed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disbursements');
    }
};
