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
        Schema::create('user_kpi_approvals', function (Blueprint $table) {
            $table->id();

            $table->unsignedTinyInteger('level');
            $table->foreignId('kpi_period_id')
                ->constrained('kpi_periods');
            $table->foreignId('approver_id')
                ->constrained('users');
            $table->foreignId('created_by')
                ->constrained('users');


            $table->enum('status', [
                'pending',
                'approved',
                'rejected'
            ]);

            $table->text('notes')->nullable();

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
        Schema::dropIfExists('user_kpi_approvals');
    }
};
