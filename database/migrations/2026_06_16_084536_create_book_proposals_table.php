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
        Schema::create('book_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // ID Atasan yang akan melakukan approval (merujuk ke tabel users)
            $table->foreignId('superior_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('title');
            $table->string('author');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->date('due_date')->nullable(); // Diisi oleh atasan saat approve

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_proposals');
    }
};
