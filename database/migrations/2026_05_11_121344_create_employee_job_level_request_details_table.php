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
        Schema::create('employee_job_level_request_details', function (Blueprint $table) {

            $table->id();

            $table->foreignId('request_id')
                ->constrained('employee_job_level_requests')
                ->cascadeOnDelete();

            $table->foreignId('job_level_id')
                ->constrained()
                ->cascadeOnDelete();

            // score / result optional
            $table->integer('score')
                ->nullable();

            $table->text('notes')
                ->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_job_level_request_details');
    }
};
