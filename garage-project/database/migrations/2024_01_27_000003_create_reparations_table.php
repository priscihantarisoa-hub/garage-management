<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration pour créer la table reparations
 * 
 * Cette migration crée la table 'reparations' qui stocke
 * les informations sur les réparations effectuées par le garage.
 * Cette table fait le lien entre les clients et les interventions.
 */
return new class extends Migration {
    /**
     * Exécute la migration
     * 
     * @return void
     */
    public function up(): void
    {
        Schema::create('reparations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade'); // Clé étrangère vers la table clients
            $table->foreignId('intervention_id')->constrained()->onDelete('cascade'); // Clé étrangère vers la table interventions
            $table->enum('statut', ['en_attente', 'en_cours', 'termine', 'paye'])->default('en_attente'); // Statut de la réparation
            $table->integer('slot')->nullable(); // Emplacement de réparation (1 ou 2 pour les 2 emplacements disponibles)
            $table->timestamp('debut_reparation')->nullable(); // Heure de début de la réparation
            $table->timestamp('fin_reparation')->nullable(); // Heure de fin de la réparation
            $table->decimal('montant_total', 8, 2); // Montant total de la réparation (8 chiffres au total, 2 décimales)
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
        Schema::dropIfExists('reparations');
    }
};
