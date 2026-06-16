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
        Schema::create('jogging_tracks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('distance_km', 8, 2)->default(0); // Jarak dalam KM
            $table->integer('duration_seconds')->default(0);  // Total waktu lari
            $table->timestamp('start_time')->nullable();      // Waktu mulai
            $table->timestamp('end_time')->nullable();        // Waktu selesai
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jogging_tracks');
    }
};
