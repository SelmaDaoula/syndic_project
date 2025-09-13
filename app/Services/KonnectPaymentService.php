<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KonnectPaymentService
{
    private $apiKey;
    private $receiverWalletId;
    private $baseUrl;
    private $webhookUrl;

    public function __construct()
    {
        $this->apiKey = config('services.konnect.api_key');
        $this->receiverWalletId = config('services.konnect.receiver_wallet_id');
        $this->baseUrl = config('services.konnect.base_url');
        $this->webhookUrl = config('services.konnect.webhook_url');
    }

    /**
     * Initialiser un paiement Konnect
     */
    public function initPayment($abonnement, $promoteur)
    {
        $payload = [
            'receiverWalletId' => $this->receiverWalletId,
            'token' => 'TND',
            'amount' => $abonnement->montant * 1000, // Convertir en millimes
            'type' => 'immediate',
            'description' => "Abonnement {$abonnement->type_abonnement} - SyndicPro",
            'acceptedPaymentMethods' => ['wallet', 'bank_card', 'e-DINAR'],
            'lifespan' => 60, // 60 minutes
            'checkoutForm' => false,
            'addPaymentFeesToAmount' => false,
            'firstName' => $promoteur->prenom ?? $promoteur->nom,
            'lastName' => $promoteur->nom,
            'phoneNumber' => $promoteur->telephone ?? '22777777',
            'email' => $promoteur->email,
            'orderId' => "ABONNEMENT-{$abonnement->id}",
            'webhook' => $this->webhookUrl,
            'theme' => 'light'
        ];

        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post($this->baseUrl . '/payments/init-payment', $payload);

            if ($response->successful()) {
                $data = $response->json();
                
                // Sauvegarder la référence de paiement
                $abonnement->update([
                    'payment_ref' => $data['paymentRef'],
                    'payment_url' => $data['payUrl']
                ]);

                Log::info('Paiement Konnect initialisé', [
                    'abonnement_id' => $abonnement->id,
                    'payment_ref' => $data['paymentRef']
                ]);

                return [
                    'success' => true,
                    'payUrl' => $data['payUrl'],
                    'paymentRef' => $data['paymentRef']
                ];
            }

            Log::error('Erreur initialisation paiement Konnect', [
                'response' => $response->json(),
                'status' => $response->status()
            ]);

            return [
                'success' => false,
                'error' => 'Erreur lors de l\'initialisation du paiement'
            ];

        } catch (\Exception $e) {
            Log::error('Exception paiement Konnect', [
                'message' => $e->getMessage(),
                'abonnement_id' => $abonnement->id
            ]);

            return [
                'success' => false,
                'error' => 'Erreur technique lors du paiement'
            ];
        }
    }

    /**
     * Vérifier le statut d'un paiement
     */
    public function checkPaymentStatus($paymentRef)
    {
        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey
            ])->get($this->baseUrl . "/payments/{$paymentRef}");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Erreur vérification statut paiement', [
                'payment_ref' => $paymentRef,
                'status' => $response->status()
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('Exception vérification paiement', [
                'message' => $e->getMessage(),
                'payment_ref' => $paymentRef
            ]);

            return null;
        }
    }
}
