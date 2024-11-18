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
        Schema::create('title_submissions', function (Blueprint $table) {
            $table->id();
            // thesis_id
            $table->foreignId('thesis_id')->constrained('theses')->onDelete('cascade');
            // title
            $table->string('title');
            // description
            $table->text('description');
            // status ('pending', 'accepted', 'rejected')
            $table->string('status')->default('pending');
            // note
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('title_submissions');
    }
};
