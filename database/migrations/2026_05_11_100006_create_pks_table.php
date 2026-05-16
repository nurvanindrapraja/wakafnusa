<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('programs')->cascadeOnDelete();
            $table->string('pks_number')->unique();
            $table->foreignId('signed_by_admin')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('signed_by_pelaksana')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('signed_at')->nullable();
            $table->string('document_path')->nullable();
            $table->enum('status', ['DRAFT', 'SIGNED', 'ACTIVE', 'EXPIRED'])->default('DRAFT');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pks');
    }
};
