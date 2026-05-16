<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('programs')->cascadeOnDelete();
            $table->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete();
            // 5C Scores (1-5)
            $table->tinyInteger('score_character')->default(0);
            $table->tinyInteger('score_capacity')->default(0);
            $table->tinyInteger('score_capital')->default(0);
            $table->tinyInteger('score_collateral')->default(0);
            $table->tinyInteger('score_condition')->default(0);
            // 3P Scores (1-5)
            $table->tinyInteger('score_people')->default(0);
            $table->tinyInteger('score_planet')->default(0);
            $table->tinyInteger('score_profit')->default(0);
            // Notes
            $table->text('notes_5c')->nullable();
            $table->text('notes_3p')->nullable();
            // Decision
            $table->enum('decision', ['RECOMMENDED', 'REVISION_NEEDED', 'REJECTED']);
            $table->text('decision_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
