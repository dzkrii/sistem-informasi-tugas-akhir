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
        Schema::table('chapter_statuses', function (Blueprint $table) {
            // Hapus kolom yang tidak dibutuhkan
            $table->dropColumn(['chapter_number', 'status', 'note']);

            // Tambahkan kolom baru untuk bab dan note
            $table->string('bab1')->nullable()->default('not_started');
            $table->text('note1')->nullable();
            $table->string('bab2')->nullable()->default('not_started');
            $table->text('note2')->nullable();
            $table->string('bab3')->nullable()->default('not_started');
            $table->text('note3')->nullable();
            $table->string('bab4')->nullable()->default('not_started');
            $table->text('note4')->nullable();
            $table->string('bab5')->nullable()->default('not_started');
            $table->text('note5')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chapter_statuses', function (Blueprint $table) {
            // Tambahkan kembali kolom yang dihapus
            $table->integer('chapter_number');
            $table->string('status');
            $table->text('note')->nullable();

            // Hapus kolom baru yang ditambahkan
            $table->dropColumn([
                'bab1',
                'note1',
                'bab2',
                'note2',
                'bab3',
                'note3',
                'bab4',
                'note4',
                'bab5',
                'note5'
            ]);
        });
    }
};
