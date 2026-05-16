<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelaksana_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('category', ['PENDIDIKAN', 'KESEHATAN', 'EKONOMI', 'IBADAH', 'SOSIAL']);
            $table->text('description');
            $table->decimal('target_amount', 15, 2);
            $table->decimal('collected_amount', 15, 2)->default(0);
            $table->string('location_province');
            $table->string('location_city');
            $table->text('location_detail')->nullable();
            $table->integer('beneficiary_count')->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('rab_total', 15, 2);
            $table->decimal('rab_operational_pct', 5, 2)->default(0);
            $table->string('rab_document')->nullable();
            $table->json('supporting_docs')->nullable();
            $table->string('cover_image')->nullable();
            $table->json('gallery')->nullable();
            $table->enum('status', [
                'DRAFT', 'PROPOSED', 'REVIEWING', 'REVISION_NEEDED',
                'RECOMMENDED', 'PKS_PENDING', 'PUBLISHED',
                'FUNDED', 'IMPLEMENTING', 'COMPLETED', 'REJECTED'
            ])->default('DRAFT');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
