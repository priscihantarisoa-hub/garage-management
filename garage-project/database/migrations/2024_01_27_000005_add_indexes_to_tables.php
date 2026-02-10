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
        Schema::table('clients', function (Blueprint $table) {
            $table->index(['nom', 'prenom'], 'clients_name_index');
            $table->index('telephone', 'clients_phone_index');
            $table->index('email', 'clients_email_index');
            $table->index('voiture_immatriculation', 'clients_immat_index');
        });

        Schema::table('interventions', function (Blueprint $table) {
            $table->index('nom', 'interventions_name_index');
            $table->index('type', 'interventions_type_index');
            $table->index('actif', 'interventions_active_index');
            $table->index(['nom', 'type'], 'interventions_name_type_index');
        });

        Schema::table('reparations', function (Blueprint $table) {
            $table->index('client_id', 'reparations_client_index');
            $table->index('intervention_id', 'reparations_intervention_index');
            $table->index('statut', 'reparations_status_index');
            $table->index('slot', 'reparations_slot_index');
            $table->index(['statut', 'slot'], 'reparations_status_slot_index');
            $table->index('created_at', 'reparations_created_index');
        });

        Schema::table('repair_intervention', function (Blueprint $table) {
            $table->index(['repair_id', 'intervention_id'], 'repair_intervention_main_index');
            $table->index('repair_id', 'repair_intervention_repair_index');
            $table->index('intervention_id', 'repair_intervention_intervention_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropIndex('clients_name_index');
            $table->dropIndex('clients_phone_index');
            $table->dropIndex('clients_email_index');
            $table->dropIndex('clients_immat_index');
        });

        Schema::table('interventions', function (Blueprint $table) {
            $table->dropIndex('interventions_name_index');
            $table->dropIndex('interventions_type_index');
            $table->dropIndex('interventions_active_index');
            $table->dropIndex('interventions_name_type_index');
        });

        Schema::table('reparations', function (Blueprint $table) {
            $table->dropIndex('reparations_client_index');
            $table->dropIndex('reparations_intervention_index');
            $table->dropIndex('reparations_status_index');
            $table->dropIndex('reparations_slot_index');
            $table->dropIndex('reparations_status_slot_index');
            $table->dropIndex('reparations_created_index');
        });

        Schema::table('repair_intervention', function (Blueprint $table) {
            $table->dropIndex('repair_intervention_main_index');
            $table->dropIndex('repair_intervention_repair_index');
            $table->dropIndex('repair_intervention_intervention_index');
        });
    }
};
