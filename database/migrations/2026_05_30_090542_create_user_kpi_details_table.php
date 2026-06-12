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
        Schema::create('user_kpi_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kpi_approval_id')
                ->constrained('user_kpi_approvals');
            $table->foreignId('kpi_master_id')
                ->constrained('kpi_masters');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_kpi_details');
    }
};
