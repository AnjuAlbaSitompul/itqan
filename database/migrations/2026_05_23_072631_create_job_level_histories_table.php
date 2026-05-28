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
        Schema::create('job_level_histories', function (Blueprint $table) {

            $table->id();

            $table->foreignId('job_level_id')
                ->constrained()
                ->cascadeOnDelete();

            // ACTION
            $table->enum('action', [
                'created',
                'updated',
                'deleted',
                'activated',
                'deactivated'
            ]);

            // OLD VALUE
            $table->json('old_data')
                ->nullable();

            // NEW VALUE
            $table->json('new_data')
                ->nullable();

            // WHO
            $table->foreignId('action_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('action_at');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_level_histories');
    }
};
