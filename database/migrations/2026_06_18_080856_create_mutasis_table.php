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
        Schema::create('mutasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('from_id')->constrained('organizational_units');
            $table->foreignId('to_id')->constrained('organizational_units');
            $table->foreignId('jabatan_id')->nullable()->constrained();
            $table->boolean('is_head')->default(false);
            $table->string('golongan')->nullable();
            $table->string('reason')->nullable();
            $table->foreignId('role_id')->nullable()->constrained('roles');
            $table->date('effective_date')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->enum('superior_approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->foreignId('superior_approved_by')->nullable()->constrained('users');
            $table->foreignId('requested_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasis');
    }
};
