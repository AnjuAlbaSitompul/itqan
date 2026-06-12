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
        Schema::create('user_kpi_realizations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_kpi_id')
                ->constrained('user_kpis');
            $table->foreignId('user_kpi_detail_id')
                ->constrained('user_kpi_details');

            $table->decimal('realization', 18, 2);

            $table->decimal('achievement_percent', 8, 2)
                ->nullable();

            $table->decimal('nilai', 8, 2)->nullable();

            $table->text('notes')->nullable();

            $table->foreignId('created_by')
                ->constrained('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_kpi_realizations');
    }
};
