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
        Schema::create('prayer_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('prayer_name')->unique(); // 'Tahajjud' atau 'Subuh'
            $table->time('start_time'); // Waktu mulai (Begin)
            $table->time('end_time');   // Waktu selesai (End)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prayer_schedules');
    }
};
