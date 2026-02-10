<?php

namespace App\Providers;

use App\Services\FirebaseSyncService;
use App\Services\NotificationService;
use App\Models\Reparation;
use App\Models\Intervention;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;

class FirebaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(FirebaseSyncService::class, function ($app) {
            return new FirebaseSyncService();
        });

        $this->app->singleton(NotificationService::class, function ($app) {
            return new NotificationService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Configuration du logging dédié pour Firebase
        Log::extend('firebase', function ($app, $config) {
            return new \Monolog\Logger('firebase');
        });

        // Écouteurs d'événements pour la synchronisation automatique
        $this->registerModelListeners();
    }

    /**
     * Enregistre les écouteurs d'événements pour les modèles
     */
    private function registerModelListeners(): void
    {
        // Écouteurs pour les réparations
        Reparation::created(function ($reparation) {
            try {
                app(FirebaseSyncService::class)->syncRepair($reparation);
                app(NotificationService::class)->notifyNewReparation($reparation);
                Log::info('Réparation créée et synchronisée', ['id' => $reparation->id]);
            } catch (\Exception $e) {
                Log::error('Erreur synchronisation création réparation', [
                    'error' => $e->getMessage(),
                    'reparation_id' => $reparation->id
                ]);
            }
        });

        Reparation::updated(function ($reparation) {
            try {
                $original = $reparation->getOriginal();
                
                // Synchronisation Firebase
                app(FirebaseSyncService::class)->syncRepair($reparation);
                
                // Notification de changement de statut
                if (isset($original['statut']) && $original['statut'] !== $reparation->statut) {
                    app(NotificationService::class)->notifyStatusChange($reparation, $original['statut']);
                }
                
                // Notification de paiement
                if (isset($original['statut']) && $original['statut'] !== 'paye' && $reparation->statut === 'paye') {
                    app(NotificationService::class)->notifyPayment($reparation);
                }
                
                Log::info('Réparation mise à jour et synchronisée', ['id' => $reparation->id]);
            } catch (\Exception $e) {
                Log::error('Erreur synchronisation mise à jour réparation', [
                    'error' => $e->getMessage(),
                    'reparation_id' => $reparation->id
                ]);
            }
        });

        Reparation::deleted(function ($reparation) {
            try {
                app(FirebaseSyncService::class)->deleteRepair($reparation->id);
                Log::info('Réparation supprimée et synchronisée', ['id' => $reparation->id]);
            } catch (\Exception $e) {
                Log::error('Erreur synchronisation suppression réparation', [
                    'error' => $e->getMessage(),
                    'reparation_id' => $reparation->id
                ]);
            }
        });

        // Écouteurs pour les interventions
        Intervention::created(function ($intervention) {
            try {
                app(FirebaseSyncService::class)->syncIntervention($intervention);
                Log::info('Intervention créée et synchronisée', ['id' => $intervention->id]);
            } catch (\Exception $e) {
                Log::error('Erreur synchronisation création intervention', [
                    'error' => $e->getMessage(),
                    'intervention_id' => $intervention->id
                ]);
            }
        });

        Intervention::updated(function ($intervention) {
            try {
                app(FirebaseSyncService::class)->syncIntervention($intervention);
                app(NotificationService::class)->notifyInterventionUpdate($intervention);
                Log::info('Intervention mise à jour et synchronisée', ['id' => $intervention->id]);
            } catch (\Exception $e) {
                Log::error('Erreur synchronisation mise à jour intervention', [
                    'error' => $e->getMessage(),
                    'intervention_id' => $intervention->id
                ]);
            }
        });

        Intervention::deleted(function ($intervention) {
            try {
                app(FirebaseSyncService::class)->deleteIntervention($intervention->id);
                Log::info('Intervention supprimée et synchronisée', ['id' => $intervention->id]);
            } catch (\Exception $e) {
                Log::error('Erreur synchronisation suppression intervention', [
                    'error' => $e->getMessage(),
                    'intervention_id' => $intervention->id
                ]);
            }
        });
    }
}
