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
        Schema::create('theses', function (Blueprint $table) {
            $table->id();
            // student_id
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            // lecturer_id
            $table->foreignId('lecturer_id')->constrained('lecturers')->onDelete('cascade');
            // status (default -> 'progress')
            $table->string('status')->default('progress');
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
        Schema::dropIfExists('theses');
    }
};
