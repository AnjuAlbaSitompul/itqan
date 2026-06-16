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
        Schema::create('prayer_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('prayer_type', ['Tahajjud', 'Subuh']);

            // Waktu aktual di server saat request disubmit
            $table->timestamp('reported_at')->useCurrent();

            // (Opsional) Menyimpan waktu device untuk keperluan audit log/analisa anomali
            $table->timestamp('device_time')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prayer_reports');
    }
};
