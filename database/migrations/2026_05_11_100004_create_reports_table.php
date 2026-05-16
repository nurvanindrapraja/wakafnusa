<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('programs')->cascadeOnDelete();
            $table->foreignId('submitted_by')->constrained('users')->cascadeOnDelete();
            $table->string('report_period')->comment('e.g. Q1-2026');
            $table->string('title');
            $table->text('description');
            $table->decimal('realized_amount', 15, 2)->default(0);
            $table->json('evidence_docs')->nullable();
            $table->json('evidence_photos')->nullable();
            $table->enum('status', ['SUBMITTED', 'VALIDATED', 'REJECTED'])->default('SUBMITTED');
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('validated_at')->nullable();
            $table->text('rejection_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
