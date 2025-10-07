<?php

namespace App\Services;

use App\Models\Abonnement;
use App\Models\Promoteur;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class KonnectPaymentService
{
    private $apiKey;
    private $receiverWalletId;
    private $apiBaseUrl;
    private $webhookUrl;

    public function __construct()
    {
        // Configuration - vous pouvez mettre ces valeurs dans le .env
        $this->apiKey = '68ca983d206e1e3c35298562:bRiZFiUYZEVzzFDhcsohyu';
        $this->receiverWalletId = '68ca983d206e1e3c35298568';
        $this->apiBaseUrl = 'https://api.sandbox.konnect.network/api/v2';
        $this->webhookUrl = url('/webhook/konnect'); // URL de votre webhook
    }

    /**
     * Initialiser un paiement
     */
    public function initPayment(Abonnement $abonnement, Promoteur $promoteur)
    {
        Log::info('Initialisation paiement Konnect', [
            'abonnement_id' => $abonnement->id,
            'montant' => $abonnement->montant,
            'promoteur_id' => $promoteur->id
        ]);

        try {
            // Préparer les données du paiement
            $paymentData = [
                'receiverWalletId' => $this->receiverWalletId,
                'token' => 'TND',
                'amount' => $abonnement->montant * 1000, // Convertir en millimes
                'type' => 'immediate',
                'description' => "Abonnement {$abonnement->type_abonnement} - SmartSyndic",
                'acceptedPaymentMethods' => ['bank_card', 'wallet', 'e-DINAR'],
                'lifespan' => 60, // 1 heure
                'checkoutForm' => false,
                'addPaymentFeesToAmount' => false,
                'firstName' => $promoteur->nom ?? 'Promoteur',
                'lastName' => $promoteur->prenom ?? '',
                'phoneNumber' => $promoteur->telephone ?? '22777777',
                'email' => $promoteur->email ?? $promoteur->user->email ?? 'test@example.com',
                'orderId' => 'ABN_' . $abonnement->id . '_' . time(),
                'webhook' => $this->webhookUrl . '?payment_ref={{payment_ref}}',
                'theme' => 'light'
            ];

            Log::info('Données paiement préparées', $paymentData);

            // Appel API
            $response = $this->makeApiCall('/payments/init-payment', 'POST', $paymentData);

            if ($response['success']) {
                // Mettre à jour l'abonnement avec les infos de paiement
                $abonnement->update([
                    'payment_ref' => $response['data']['paymentRef'],
                    'payment_url' => $response['data']['payUrl']
                ]);

                Log::info('Paiement initialisé avec succès', [
                    'payment_ref' => $response['data']['paymentRef'],
                    'pay_url' => $response['data']['payUrl']
                ]);

                return [
                    'success' => true,
                    'paymentRef' => $response['data']['paymentRef'],
                    'payUrl' => $response['data']['payUrl']
                ];
            } else {
                Log::error('Erreur initialisation paiement', $response);
                return [
                    'success' => false,
                    'error' => $response['error'] ?? 'Erreur API Konnect'
                ];
            }

        } catch (\Exception $e) {
            Log::error('Exception initialisation paiement', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => 'Erreur technique lors de l\'initialisation du paiement'
            ];
        }
    }

    /**
     * Vérifier le statut d'un paiement
     */
    public function checkPaymentStatus($paymentRef)
    {
        Log::info('Vérification statut paiement', ['payment_ref' => $paymentRef]);

        try {
            $response = $this->makeApiCall('/payments/' . $paymentRef, 'GET');

            if ($response['success']) {
                $payment = $response['data']['payment'];
                
                Log::info('Statut paiement récupéré', [
                    'payment_ref' => $paymentRef,
                    'status' => $payment['status'],
                    'transactions' => count($payment['transactions'])
                ]);

                return $payment;
            } else {
                Log::error('Erreur récupération statut', $response);
                return null;
            }

        } catch (\Exception $e) {
            Log::error('Exception vérification statut', [
                'payment_ref' => $paymentRef,
                'message' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Traiter une notification webhook
     */
    public function processWebhook($paymentRef)
    {
        Log::info('Traitement webhook', ['payment_ref' => $paymentRef]);

        // Récupérer le statut actuel du paiement
        $paymentStatus = $this->checkPaymentStatus($paymentRef);

        if (!$paymentStatus) {
            Log::error('Impossible de récupérer le statut du paiement', ['payment_ref' => $paymentRef]);
            return false;
        }

        // Trouver l'abonnement correspondant
        $abonnement = Abonnement::where('payment_ref', $paymentRef)->first();

        if (!$abonnement) {
            Log::error('Abonnement non trouvé pour payment_ref', ['payment_ref' => $paymentRef]);
            return false;
        }

        // Traiter selon le statut
        if ($paymentStatus['status'] === 'completed') {
            $abonnement->update([
                'statut' => 'actif',
                'payment_completed_at' => now()
            ]);

            Log::info('Abonnement activé via webhook', [
                'abonnement_id' => $abonnement->id,
                'payment_ref' => $paymentRef
            ]);

            return true;

        } elseif (in_array($paymentStatus['status'], ['failed', 'expired'])) {
            $abonnement->update(['statut' => 'echec']);

            Log::info('Abonnement marqué comme échoué', [
                'abonnement_id' => $abonnement->id,
                'status' => $paymentStatus['status']
            ]);

            return true;
        }

        Log::info('Statut paiement en attente', [
            'payment_ref' => $paymentRef,
            'status' => $paymentStatus['status']
        ]);

        return false;
    }

    /**
     * Faire un appel API
     */
    private function makeApiCall($endpoint, $method = 'GET', $data = null)
    {
        $url = $this->apiBaseUrl . $endpoint;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'x-api-key: ' . $this->apiKey,
            'Content-Type: application/json'
        ]);

        if ($method === 'POST' && $data) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return [
                'success' => false,
                'error' => $error,
                'http_code' => 0
            ];
        }

        $decodedResponse = json_decode($response, true);

        return [
            'success' => ($httpCode >= 200 && $httpCode < 300),
            'http_code' => $httpCode,
            'data' => $decodedResponse,
            'raw' => $response
        ];
    }
}