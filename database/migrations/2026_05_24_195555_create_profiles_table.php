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
            $table->foreignId('user_id')->constrained();
            $table->string('nip')->unique();
            $table->string('alamat');
            $table->string('tamatan');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir');
            $table->date('tanggal_masuk');
            $table->string('domisili');
            $table->enum('tipe_bpjs', ['Kesehatan', 'Ketenagakerjaan']);
            $table->foreignId('unit_id')->constrained();
            $table->foreignId('jabatan_id')->constrained();
            $table->string('golongan');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
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
