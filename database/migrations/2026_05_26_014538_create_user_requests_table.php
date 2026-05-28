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
        Schema::create('user_requests', function (Blueprint $table) {

            $table->id();

            // USER
            $table->string('name');
            $table->string('username')->unique();
            $table->string('password');

            // PROFILE
            $table->string('nip')->nullable();
            $table->text('alamat')->nullable();
            $table->string('tamatan')->nullable();

            $table->enum('jenis_kelamin', ['L', 'P'])
            ;

            $table->date('tanggal_lahir')
            ;

            $table->string('domisili')
            ;

            // STATUS
            $table->enum('status', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending');

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users');

            $table->timestamp('approved_at')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_requests');
    }
};
