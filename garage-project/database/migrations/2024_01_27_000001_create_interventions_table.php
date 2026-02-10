<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration pour créer la table interventions
 * 
 * Cette migration crée la table 'interventions' qui stocke
 * les différents types d'interventions proposées par le garage.
 */
return new class extends Migration {
    /**
     * Exécute la migration
     * 
     * @return void
     */
    public function up(): void
    {
        Schema::create('interventions', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // Nom de l'intervention (Frein, Vidange, etc.)
            $table->text('description')->nullable(); // Description détaillée de l'intervention
            $table->decimal('prix', 8, 2); // Prix de l'intervention (8 chiffres au total, 2 décimales)
            $table->integer('duree'); // Durée estimée en secondes
            $table->string('type'); // Type parmi les 8 catégories prédéfinies
            $table->boolean('actif')->default(true); // Indique si l'intervention est actuellement disponible
            $table->timestamps(); // Colonnes created_at et updated_at
        });
    }

    /**
     * Annule la migration
     * 
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('interventions');
    }
};
