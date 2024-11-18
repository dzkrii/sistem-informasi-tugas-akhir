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
        Schema::create('chapter_statuses', function (Blueprint $table) {
            $table->id();
            // thesis_id
            $table->foreignId('thesis_id')->constrained('theses')->onDelete('cascade');
            // chapter_number
            $table->integer('chapter_number');
            // status ('not_started', 'in_review', 'revision_needed', 'accepted')
            $table->string('status')->default('not_started');
            // note
            $table->text('note')->nullable();
            $table->timestamps();
            // soft delete
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapter_statuses');
    }
};
