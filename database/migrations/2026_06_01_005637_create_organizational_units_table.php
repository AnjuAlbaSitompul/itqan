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
        Schema::create('organizational_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('organizational_units')
                ->nullOnDelete();
            $table->string('name')->unique();
            $table->enum('type', ['division', 'department', 'unit', 'sub_unit']);
            $table->boolean('is_active')->default(true);
            $table->foreignId('outlet_id')
                ->nullable()
                ->constrained('outlets')
                ->nullOnDelete();
            $table->integer('man_power')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizational_units');
    }
};
