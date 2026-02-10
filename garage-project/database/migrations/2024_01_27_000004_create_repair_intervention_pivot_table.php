<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('repair_intervention', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_id')->constrained('reparations')->onDelete('cascade');
            $table->foreignId('intervention_id')->constrained('interventions')->onDelete('cascade');
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->timestamps();

            // Empêcher les doublons
            $table->unique(['repair_id', 'intervention_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_intervention');
    }
};
