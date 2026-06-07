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
        Schema::create('kpi_periods', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            // periode pengajuan
            $table->timestamp('registration_start');
            $table->timestamp('registration_end');

            // periode pengerjaan KPI
            $table->date('period_start');
            $table->date('period_end');

            $table->enum('status', [
                'draft',
                'open',
                'closed'
            ])->default('draft');

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
        Schema::dropIfExists('kpi_periods');
    }
};
