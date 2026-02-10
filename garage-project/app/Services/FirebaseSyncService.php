<?php

namespace App\Services;

use App\Models\Reparation;
use App\Models\Intervention;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Google\Cloud\Firestore\FirestoreClient;

/**
 * Service de synchronisation avec Firebase Firestore
 * 
 * Gère la synchronisation vers Cloud Firestore
 * pour les réparations et interventions en temps réel
 */
class FirebaseSyncService
{
    private FirestoreClient $firestore;
    private string $repairsCollection = 'repairs';
    private string $interventionsCollection = 'interventions';

    public function __construct()
    {
        $serviceAccount = [
            'type' => 'service_account',
            'project_id' => env('FIREBASE_PROJECT_ID'),
            'private_key_id' => env('FIREBASE_PRIVATE_KEY_ID'),
            'private_key' => env('FIREBASE_PRIVATE_KEY'),
            'client_email' => env('FIREBASE_CLIENT_EMAIL'),
            'client_id' => env('FIREBASE_CLIENT_ID'),
            'auth_uri' => env('FIREBASE_AUTH_URI'),
            'token_uri' => env('FIREBASE_TOKEN_URI'),
            'auth_provider_x509_cert_url' => env('FIREBASE_AUTH_PROVIDER_X509_CERT_URL'),
            'client_x509_cert_url' => env('FIREBASE_CLIENT_X509_CERT_URL'),
        ];

        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount);

        $this->firestore = $firebase->createFirestore()->database();
    }

    /**
     * Synchronise une réparation vers Firestore
     * 
     * @param Reparation $repair
     * @return bool
     */
    public function syncRepair(Reparation $repair): bool
    {
        try {
            $data = [
                'id' => $repair->id,
                'client_id' => $repair->client_id,
                'client_nom' => $repair->client->nom ?? '',
                'client_prenom' => $repair->client->prenom ?? '',
                'statut' => $repair->statut,
                'slot' => $repair->slot,
                'montant_total' => (float) $repair->montant_total,
                'debut_reparation' => $repair->debut_reparation?->toISOString(),
                'fin_reparation' => $repair->fin_reparation?->toISOString(),
                'created_at' => $repair->created_at->toISOString(),
                'updated_at' => $repair->updated_at->toISOString(),
                'interventions' => $repair->interventions->map(function ($intervention) {
                    return [
                        'id' => $intervention->id,
                        'nom' => $intervention->nom,
                        'prix' => (float) $intervention->prix,
                        'pivot_status' => $intervention->pivot->status ?? 'pending'
                    ];
                })->toArray()
            ];

            $this->firestore->collection($this->repairsCollection)
                ->document((string) $repair->id)
                ->set($data);

            Log::info('Réparation synchronisée vers Firestore', ['repair_id' => $repair->id]);
            return true;

        } catch (\Exception $e) {
            Log::error('Erreur synchronisation réparation Firestore', [
                'repair_id' => $repair->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Synchronise une intervention vers Firestore
     * 
     * @param Intervention $intervention
     * @return bool
     */
    public function syncIntervention(Intervention $intervention): bool
    {
        try {
            $data = [
                'id' => $intervention->id,
                'nom' => $intervention->nom,
                'description' => $intervention->description,
                'prix' => (float) $intervention->prix,
                'duree' => $intervention->duree,
                'type' => $intervention->type,
                'actif' => $intervention->actif,
                'created_at' => $intervention->created_at->toISOString(),
                'updated_at' => $intervention->updated_at->toISOString()
            ];

            $this->firestore->collection($this->interventionsCollection)
                ->document((string) $intervention->id)
                ->set($data);

            Log::info('Intervention synchronisée vers Firestore', ['intervention_id' => $intervention->id]);
            return true;

        } catch (\Exception $e) {
            Log::error('Erreur synchronisation intervention Firestore', [
                'intervention_id' => $intervention->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Synchronise la suppression d'une réparation
     * 
     * @param int $repairId
     * @return bool
     */
    public function deleteRepair(int $repairId): bool
    {
        try {
            $this->firestore->collection($this->repairsCollection)
                ->document((string) $repairId)
                ->delete();
            Log::info('Réparation supprimée de Firestore', ['repair_id' => $repairId]);
            return true;
        } catch (\Exception $e) {
            Log::error('Erreur suppression réparation Firestore', [
                'repair_id' => $repairId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Synchronise la suppression d'une intervention
     * 
     * @param int $interventionId
     * @return bool
     */
    public function deleteIntervention(int $interventionId): bool
    {
        try {
            $this->firestore->collection($this->interventionsCollection)
                ->document((string) $interventionId)
                ->delete();
            Log::info('Intervention supprimée de Firestore', ['intervention_id' => $interventionId]);
            return true;
        } catch (\Exception $e) {
            Log::error('Erreur suppression intervention Firestore', [
                'intervention_id' => $interventionId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Teste la connexion à Firestore
     * 
     * @return bool
     */
    public function testConnection(): bool
    {
        try {
            $testRef = $this->firestore->collection('test_connection')->document('status');
            $testRef->set(['timestamp' => now()->toISOString(), 'connected' => true]);

            Log::info('Firestore test successful');
            return true;

        } catch (\Exception $e) {
            Log::error('Firestore test failed', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
