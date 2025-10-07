<?php
/**
 * Script de test pour vérifier que l'intégration Konnect fonctionne
 * Placez ce fichier dans le répertoire racine de votre projet Laravel
 * Exécutez: php test_integration.php
 */

// Simuler Laravel
$_ENV['APP_ENV'] = 'local';

// Configuration Konnect (même que dans le service)
$apiKey = '68ca983d206e1e3c35298562:bRiZFiUYZEVzzFDhcsohyu';
$receiverWalletId = '68ca983d206e1e3c35298568';
$apiBaseUrl = 'https://api.sandbox.konnect.network/api/v2';

echo "=== TEST INTÉGRATION KONNECT ===\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n\n";

/**
 * Fonction pour appels API (même logique que le service)
 */
function makeApiCall($endpoint, $method = 'GET', $data = null) {
    global $apiKey, $apiBaseUrl;
    
    $url = $apiBaseUrl . $endpoint;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'x-api-key: ' . $apiKey,
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

// Test 1: Créer un paiement test
echo "1. TEST CRÉATION PAIEMENT\n";
echo "=========================\n";

$paymentData = [
    'receiverWalletId' => $receiverWalletId,
    'token' => 'TND',
    'amount' => 5000, // 5 TND en millimes
    'type' => 'immediate',
    'description' => 'Test Abonnement SmartSyndic',
    'acceptedPaymentMethods' => ['bank_card', 'wallet'],
    'lifespan' => 60,
    'checkoutForm' => false,
    'firstName' => 'Test',
    'lastName' => 'Promoteur',
    'email' => 'test@smartsyndic.com',
    'orderId' => 'TEST_INTEGRATION_' . time()
];

$result = makeApiCall('/payments/init-payment', 'POST', $paymentData);

if ($result['success']) {
    $paymentRef = $result['data']['paymentRef'];
    $payUrl = $result['data']['payUrl'];
    
    echo "✅ SUCCÈS - Paiement créé\n";
    echo "Payment Ref: " . $paymentRef . "\n";
    echo "URL Paiement: " . $payUrl . "\n\n";
    
    // Test 2: Vérifier le statut
    echo "2. TEST VÉRIFICATION STATUT\n";
    echo "============================\n";
    
    $statusResult = makeApiCall('/payments/' . $paymentRef);
    
    if ($statusResult['success']) {
        $payment = $statusResult['data']['payment'];
        echo "✅ SUCCÈS - Statut récupéré\n";
        echo "Statut: " . $payment['status'] . "\n";
        echo "Montant: " . $payment['amount'] . " " . $payment['token'] . "\n";
        echo "Order ID: " . $payment['orderId'] . "\n";
        echo "Transactions: " . count($payment['transactions']) . "\n\n";
    } else {
        echo "❌ ERREUR - Impossible de récupérer le statut\n";
        echo "Code: " . $statusResult['http_code'] . "\n";
        echo "Réponse: " . $statusResult['raw'] . "\n\n";
    }
    
    // Instructions de test manuel
    echo "3. TEST MANUEL\n";
    echo "==============\n";
    echo "Pour tester le paiement complet:\n";
    echo "1. Ouvrez cette URL: " . $payUrl . "\n";
    echo "2. Utilisez la carte: 4509211111111119 - 12/26 - 748\n";
    echo "3. Vérifiez ensuite le statut avec:\n";
    echo "   php test_integration.php check " . $paymentRef . "\n\n";
    
} else {
    echo "❌ ERREUR - Création paiement échouée\n";
    echo "Code: " . $result['http_code'] . "\n";
    echo "Erreur: " . ($result['error'] ?? 'Inconnue') . "\n";
    echo "Réponse: " . $result['raw'] . "\n\n";
}

// Test spécifique si payment_ref fourni en argument
if (isset($argv[1]) && $argv[1] === 'check' && isset($argv[2])) {
    $paymentRefToCheck = $argv[2];
    
    echo "VÉRIFICATION SPÉCIFIQUE\n";
    echo "=======================\n";
    echo "Payment Ref: " . $paymentRefToCheck . "\n";
    
    $checkResult = makeApiCall('/payments/' . $paymentRefToCheck);
    
    if ($checkResult['success']) {
        $payment = $checkResult['data']['payment'];
        echo "Statut: " . $payment['status'] . "\n";
        echo "Montant: " . $payment['amount'] . " " . $payment['token'] . "\n";
        echo "Transactions: " . count($payment['transactions']) . "\n";
        
        if ($payment['status'] === 'completed') {
            echo "🎉 PAIEMENT RÉUSSI!\n";
        } else {
            echo "⏳ Paiement pas encore complété\n";
        }
    } else {
        echo "❌ Erreur vérification\n";
    }
}

echo "=== FIN DU TEST ===\n";
echo "Si tous les tests passent, votre intégration est prête!\n";
?>