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
        Schema::create('user_kpis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('period_id')
                ->constrained('kpi_periods');

            $table->foreignId('master_kpi_id')
                ->constrained('kpi_masters');

            $table->foreignId('user_id')
                ->constrained('users');

            $table->decimal('weight', 5, 2);

            $table->decimal('target', 18, 2)
                ->nullable();

            $table->decimal('achievement_percent', 8, 2)
                ->nullable();

            $table->decimal('score', 8, 2)
                ->nullable();

            $table->enum('status', [
                'draft',
                'pending',
                'approved',
                'rejected',
                'completed'
            ])->default('draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_kpis');
    }
};
