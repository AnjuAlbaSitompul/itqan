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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Dibuat nullable agar tidak error jika user belum melengkapi profil
            $table->string('nip')->nullable()->unique();
            $table->string('alamat')->nullable();
            $table->string('tamatan')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->string('domisili')->nullable();
            
            // Diubah menjadi string untuk menyesuaikan input text form di view
            $table->string('tipe_bpjs')->nullable(); 
            
            $table->string('golongan')->nullable();
            
            // Avatar dan Background
            $table->string('avatar')->nullable();
            $table->string('background')->nullable(); // Kolom baru untuk foto sampul
            
            // Tracking
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};