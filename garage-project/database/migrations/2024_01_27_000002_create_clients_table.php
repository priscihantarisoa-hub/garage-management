<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration pour créer la table clients
 * 
 * Cette migration crée la table 'clients' qui stocke
 * les informations sur les clients du garage et leurs véhicules.
 */
return new class extends Migration
{
    /**
     * Exécute la migration
     * 
     * @return void
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // Nom complet du client
            $table->string('email')->unique(); // Email unique pour identification et communication
            $table->string('telephone')->nullable(); // Numéro de téléphone (optionnel)
            $table->string('voiture_marque'); // Marque du véhicule du client
            $table->string('voiture_modele'); // Modèle du véhicule du client
            $table->string('voiture_immatriculation')->unique(); // Plaque d'immatriculation unique
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
        Schema::dropIfExists('clients');
    }
};
