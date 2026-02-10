<?php

namespace App\Services;

use App\Models\Reparation;
use App\Models\Intervention;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    private $messaging;

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

        $this->messaging = $firebase->createMessaging();
    }

    /**
     * Envoie une notification pour une nouvelle réparation
     */
    public function notifyNewReparation(Reparation $reparation): bool
    {
        try {
            $title = "Nouvelle réparation créée";
            $body = "Client: {$reparation->client->nom} - {$reparation->client->voiture_marque} {$reparation->client->voiture_modele}";
            
            $data = [
                'type' => 'new_reparation',
                'reparation_id' => $reparation->id,
                'client_name' => $reparation->client->nom,
                'vehicle' => $reparation->client->voiture_marque . ' ' . $reparation->client->voiture_modele,
                'status' => $reparation->statut,
                'created_at' => $reparation->created_at->toISOString()
            ];

            return $this->sendNotification($title, $body, $data);
        } catch (\Exception $e) {
            Log::error('Erreur notification nouvelle réparation', [
                'error' => $e->getMessage(),
                'reparation_id' => $reparation->id
            ]);
            return false;
        }
    }

    /**
     * Envoie une notification pour un changement de statut
     */
    public function notifyStatusChange(Reparation $reparation, string $oldStatus): bool
    {
        try {
            $title = "Statut réparation mis à jour";
            $body = "Réparation #{$reparation->id}: {$oldStatus} → {$reparation->statut}";
            
            $data = [
                'type' => 'status_change',
                'reparation_id' => $reparation->id,
                'old_status' => $oldStatus,
                'new_status' => $reparation->statut,
                'client_name' => $reparation->client->nom,
                'updated_at' => $reparation->updated_at->toISOString()
            ];

            return $this->sendNotification($title, $body, $data);
        } catch (\Exception $e) {
            Log::error('Erreur notification changement statut', [
                'error' => $e->getMessage(),
                'reparation_id' => $reparation->id,
                'old_status' => $oldStatus,
                'new_status' => $reparation->statut
            ]);
            return false;
        }
    }

    /**
     * Envoie une notification pour une intervention mise à jour
     */
    public function notifyInterventionUpdate(Intervention $intervention): bool
    {
        try {
            $title = "Intervention mise à jour";
            $body = "{$intervention->nom} - Prix: {$intervention->prix} Ar";
            
            $data = [
                'type' => 'intervention_update',
                'intervention_id' => $intervention->id,
                'name' => $intervention->nom,
                'price' => $intervention->prix,
                'type' => $intervention->type,
                'updated_at' => $intervention->updated_at->toISOString()
            ];

            return $this->sendNotification($title, $body, $data);
        } catch (\Exception $e) {
            Log::error('Erreur notification intervention', [
                'error' => $e->getMessage(),
                'intervention_id' => $intervention->id
            ]);
            return false;
        }
    }

    /**
     * Envoie une notification de paiement
     */
    public function notifyPayment(Reparation $reparation): bool
    {
        try {
            $title = "Paiement enregistré";
            $body = "Réparation #{$reparation->id} - Montant: {$reparation->montant_total} Ar";
            
            $data = [
                'type' => 'payment',
                'reparation_id' => $reparation->id,
                'amount' => $reparation->montant_total,
                'client_name' => $reparation->client->nom,
                'paid_at' => now()->toISOString()
            ];

            return $this->sendNotification($title, $body, $data);
        } catch (\Exception $e) {
            Log::error('Erreur notification paiement', [
                'error' => $e->getMessage(),
                'reparation_id' => $reparation->id
            ]);
            return false;
        }
    }

    /**
     * Envoie une notification système
     */
    public function notifySystem(string $title, string $message, array $data = []): bool
    {
        try {
            $data = array_merge([
                'type' => 'system',
                'timestamp' => now()->toISOString()
            ], $data);

            return $this->sendNotification($title, $message, $data);
        } catch (\Exception $e) {
            Log::error('Erreur notification système', [
                'error' => $e->getMessage(),
                'title' => $title
            ]);
            return false;
        }
    }

    /**
     * Méthode principale d'envoi de notification
     */
    private function sendNotification(string $title, string $body, array $data): bool
    {
        try {
            // Notification pour le topic général du garage
            $notification = Notification::create($title, $body);
            
            $message = CloudMessage::withTarget('garage_notifications')
                ->withNotification($notification)
                ->withData($data);

            $this->messaging->send($message);
            
            Log::info('Notification envoyée avec succès', [
                'title' => $title,
                'type' => $data['type'] ?? 'unknown'
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur envoi notification', [
                'error' => $e->getMessage(),
                'title' => $title
            ]);
            return false;
        }
    }

    /**
     * Vérifie la connexion au service Firebase Messaging
     */
    public function testConnection(): bool
    {
        try {
            $testNotification = Notification::create('Test', 'Connexion Firebase testée');
            $testMessage = CloudMessage::withTarget('test_topic')
                ->withNotification($testNotification);

            $this->messaging->send($testMessage);
            
            Log::info('Test connexion Firebase Messaging réussi');
            return true;
        } catch (\Exception $e) {
            Log::error('Test connexion Firebase Messaging échoué', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
