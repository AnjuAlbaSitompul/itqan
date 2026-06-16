<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('book_reading_logs', function (Blueprint $table) {
            $table->id();
            // Relasi ke proposal buku yang sudah di-approve
            $table->foreignId('book_proposal_id')->constrained()->cascadeOnDelete();

            $table->integer('page_from');
            $table->integer('page_to');
            $table->text('summary');
            $table->date('log_date'); // Tanggal log dibaca

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_reading_logs');
    }
};
