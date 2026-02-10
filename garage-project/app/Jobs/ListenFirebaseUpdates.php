<?php

namespace App\Jobs;

use App\Models\Reparation;
use App\Models\Intervention;
use App\Services\FirebaseSyncService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Job pour écouter les mises à jour Firebase
 * 
 * Ce job s'exécute en arrière-plan pour surveiller les changements
 * dans Firebase et synchroniser les données avec Laravel
 */
class ListenFirebaseUpdates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private FirebaseSyncService $firebaseService;
    private int $retryCount = 0;
    private const MAX_RETRIES = 3;

    /**
     * Crée une nouvelle instance du job
     */
    public function __construct()
    {
        $this->firebaseService = new FirebaseSyncService();
    }

    /**
     * Exécute le job
     */
    public function handle(): void
    {
        try {
            $this->listenForRepairsUpdates();
            $this->listenForInterventionsUpdates();

        } catch (\Exception $e) {
            $this->handleError($e);
        }
    }

    /**
     * Écoute les mises à jour des réparations
     */
    private function listenForRepairsUpdates(): void
    {
        $reference = $this->firebaseService->getDatabase()
            ->getReference('repairs');

        // Simuler l'écoute des changements (en production, utiliser WebSocket)
        $snapshot = $reference->getSnapshot();
        $repairs = $snapshot->getValue() ?? [];

        foreach ($repairs as $firebaseId => $data) {
            $this->syncRepairFromFirebase($firebaseId, $data);
        }
    }

    /**
     * Écoute les mises à jour des interventions
     */
    private function listenForInterventionsUpdates(): void
    {
        $reference = $this->firebaseService->getDatabase()
            ->getReference('interventions');

        $snapshot = $reference->getSnapshot();
        $interventions = $snapshot->getValue() ?? [];

        foreach ($interventions as $firebaseId => $data) {
            $this->syncInterventionFromFirebase($firebaseId, $data);
        }
    }

    /**
     * Synchronise une réparation depuis Firebase vers Laravel
     */
    private function syncRepairFromFirebase(string $firebaseId, array $data): void
    {
        try {
            $repair = Reparation::find($data['id']);

            if (!$repair) {
                // Créer une nouvelle réparation si elle n'existe pas
                $repair = new Reparation();
                $repair->id = $data['id'];
            }

            // Mettre à jour les champs
            $repair->client_id = $data['client_id'];
            $repair->statut = $data['statut'];
            $repair->slot = $data['slot'];
            $repair->montant_total = $data['montant_total'];

            if (isset($data['debut_reparation'])) {
                $repair->debut_reparation = \Carbon\Carbon::parse($data['debut_reparation']);
            }

            if (isset($data['fin_reparation'])) {
                $repair->fin_reparation = \Carbon\Carbon::parse($data['fin_reparation']);
            }

            $repair->save();

            // Synchroniser les interventions associées
            if (isset($data['interventions'])) {
                $this->syncRepairInterventions($repair, $data['interventions']);
            }

            Log::info('Réparation synchronisée depuis Firebase', [
                'repair_id' => $repair->id,
                'firebase_id' => $firebaseId
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur synchronisation réparation depuis Firebase', [
                'firebase_id' => $firebaseId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Synchronise une intervention depuis Firebase vers Laravel
     */
    private function syncInterventionFromFirebase(string $firebaseId, array $data): void
    {
        try {
            $intervention = Intervention::find($data['id']);

            if (!$intervention) {
                $intervention = new Intervention();
                $intervention->id = $data['id'];
            }

            $intervention->nom = $data['nom'];
            $intervention->description = $data['description'];
            $intervention->prix = $data['prix'];
            $intervention->duree = $data['duree'];
            $intervention->type = $data['type'];
            $intervention->actif = $data['actif'];

            $intervention->save();

            Log::info('Intervention synchronisée depuis Firebase', [
                'intervention_id' => $intervention->id,
                'firebase_id' => $firebaseId
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur synchronisation intervention depuis Firebase', [
                'firebase_id' => $firebaseId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Synchronise les interventions d'une réparation
     */
    private function syncRepairInterventions(Reparation $repair, array $interventions): void
    {
        foreach ($interventions as $interventionData) {
            $intervention = Intervention::find($interventionData['id']);
            if ($intervention) {
                $repair->interventions()->attach($intervention->id, [
                    'status' => $interventionData['pivot_status'] ?? 'pending'
                ]);
            }
        }
    }

    /**
     * Gère les erreurs avec système de retry
     */
    private function handleError(\Exception $e): void
    {
        $this->retryCount++;

        if ($this->retryCount <= self::MAX_RETRIES) {
            Log::warning("Retry Firebase sync ({$this->retryCount}/" . self::MAX_RETRIES . ")", [
                'error' => $e->getMessage()
            ]);

            // Relancer le job après un délai
            $this->release(60 * $this->retryCount); // 1min, 2min, 3min
        } else {
            Log::error('Firebase sync failed after ' . self::MAX_RETRIES . ' retries', [
                'error' => $e->getMessage()
            ]);

            // Notifier l'administrateur
            $this->notifyAdminOfFailure($e);
        }
    }

    /**
     * Notifie l'administrateur en cas d'échec
     */
    private function notifyAdminOfFailure(\Exception $e): void
    {
        // Envoi d'email ou notification système
        Log::critical('Firebase sync failure - Admin notification required', [
            'error' => $e->getMessage(),
            'retry_count' => $this->retryCount
        ]);

        // TODO: Implémenter la notification réelle (email, Slack, etc.)
    }
}
