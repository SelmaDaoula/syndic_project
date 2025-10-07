<?php
/**
 * Test complet de l'API Konnect - Version finale
 * Usage: 
 *   php konnect_test.php create    -> Créer un paiement
 *   php konnect_test.php check [payment_ref] -> Vérifier un paiement
 */

// Configuration API Konnect (Sandbox)
define('API_KEY', '68ca983d206e1e3c35298562:bRiZFiUYZEVzzFDhcsohyu');
define('RECEIVER_WALLET_ID', '68ca983d206e1e3c35298568');
define('API_BASE_URL', 'https://api.sandbox.konnect.network/api/v2');
define('WEBHOOK_URL', 'https://votre-domaine.com/webhook'); // Changez selon vos besoins

/**
 * Fonction pour appels API
 */
function callKonnectAPI($endpoint, $method = 'GET', $data = null) {
    $url = API_BASE_URL . $endpoint;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'x-api-key: ' . API_KEY,
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
        return ['success' => false, 'error' => $error];
    }
    
    $decodedResponse = json_decode($response, true);
    
    return [
        'success' => ($httpCode >= 200 && $httpCode < 300),
        'http_code' => $httpCode,
        'data' => $decodedResponse,
        'raw' => $response
    ];
}

/**
 * Créer un paiement de test
 */
function createTestPayment() {
    echo "=== CRÉATION D'UN PAIEMENT TEST ===\n";
    echo "Date: " . date('Y-m-d H:i:s') . "\n\n";
    
    $paymentData = [
        'receiverWalletId' => RECEIVER_WALLET_ID,
        'token' => 'TND',
        'amount' => 500, // 0.5 TND
        'type' => 'immediate',
        'description' => 'Test API Konnect - ' . date('H:i:s'),
        'acceptedPaymentMethods' => ['bank_card'],
        'lifespan' => 60,
        'checkoutForm' => false,
        'addPaymentFeesToAmount' => false,
        'firstName' => 'Test',
        'lastName' => 'User',
        'phoneNumber' => '22777777',
        'email' => 'test@example.com',
        'orderId' => 'TEST_' . time(),
        'webhook' => WEBHOOK_URL,
        'theme' => 'light'
    ];
    
    echo "Paramètres du paiement:\n";
    echo "- Montant: 0.5 TND\n";
    echo "- Méthode: Carte bancaire uniquement\n";
    echo "- Durée: 1 heure\n\n";
    
    $result = callKonnectAPI('/payments/init-payment', 'POST', $paymentData);
    
    if ($result['success']) {
        $paymentRef = $result['data']['paymentRef'];
        $payUrl = $result['data']['payUrl'];
        
        echo "✅ PAIEMENT CRÉÉ AVEC SUCCÈS\n";
        echo "============================\n";
        echo "Payment Reference: " . $paymentRef . "\n";
        echo "URL de paiement: " . $payUrl . "\n\n";
        
        echo "🎯 INSTRUCTIONS POUR LE TEST:\n";
        echo "=============================\n";
        echo "1. Ouvrez cette URL dans votre navigateur:\n";
        echo "   " . $payUrl . "\n\n";
        
        echo "2. Utilisez ces cartes de test (copiez-collez):\n";
        echo "   CARTE 1 (Visa):\n";
        echo "   Numéro: 4509211111111119\n";
        echo "   Expiration: 12/26\n";
        echo "   CVC: 748\n\n";
        
        echo "   CARTE 2 (MasterCard):\n";
        echo "   Numéro: 5440212711111110\n";
        echo "   Expiration: 12/26\n";
        echo "   CVC: 665\n\n";
        
        echo "3. Après le test, vérifiez le statut:\n";
        echo "   php konnect_test.php check " . $paymentRef . "\n\n";
        
        echo "💡 CONSEILS:\n";
        echo "- Utilisez un onglet privé\n";
        echo "- Si une carte échoue, essayez l'autre\n";
        echo "- Vérifiez le dashboard: https://dashboard.sandbox.konnect.network\n";
        
    } else {
        echo "❌ ERREUR lors de la création\n";
        echo "Code HTTP: " . $result['http_code'] . "\n";
        echo "Erreur: " . ($result['error'] ?? 'Erreur API') . "\n";
        if (isset($result['raw'])) {
            echo "Réponse: " . $result['raw'] . "\n";
        }
    }
}

/**
 * Vérifier le statut d'un paiement
 */
function checkPaymentStatus($paymentRef) {
    echo "=== VÉRIFICATION DU PAIEMENT ===\n";
    echo "Payment Ref: " . $paymentRef . "\n";
    echo "Heure: " . date('Y-m-d H:i:s') . "\n\n";
    
    $result = callKonnectAPI('/payments/' . $paymentRef);
    
    if ($result['success']) {
        $payment = $result['data']['payment'];
        
        echo "✅ INFORMATIONS RÉCUPÉRÉES\n";
        echo "==========================\n";
        echo "Statut: " . $payment['status'] . "\n";
        echo "Montant: " . $payment['amount'] . " " . $payment['token'] . "\n";
        echo "Order ID: " . $payment['orderId'] . "\n";
        echo "Créé le: " . $payment['createdAt'] . "\n";
        echo "Transactions: " . count($payment['transactions']) . "\n\n";
        
        if (!empty($payment['transactions'])) {
            echo "🔄 DÉTAIL DES TRANSACTIONS:\n";
            echo "===========================\n";
            foreach ($payment['transactions'] as $index => $transaction) {
                $num = $index + 1;
                $status = $transaction['status'];
                $createdAt = isset($transaction['createdAt']) ? $transaction['createdAt'] : 'N/A';
                echo "  Transaction {$num}: {$status} ({$createdAt})\n";
            }
            echo "\n";
        }
        
        echo "🎯 RÉSULTAT FINAL:\n";
        echo "==================\n";
        if ($payment['status'] === 'completed') {
            echo "🎉 PAIEMENT RÉUSSI!\n";
            echo "Le paiement a été traité avec succès.\n";
        } elseif ($payment['status'] === 'pending') {
            if (empty($payment['transactions'])) {
                echo "⏳ PAIEMENT EN ATTENTE\n";
                echo "Aucune tentative de paiement n'a encore été faite.\n";
            } else {
                echo "⏳ PAIEMENT EN COURS\n";
                echo "Des tentatives ont été faites. Vérifiez le dashboard pour plus de détails.\n";
            }
        } else {
            echo "❌ STATUT: " . strtoupper($payment['status']) . "\n";
        }
        
    } else {
        echo "❌ ERREUR lors de la vérification\n";
        echo "Code HTTP: " . $result['http_code'] . "\n";
        if (isset($result['raw'])) {
            echo "Réponse: " . $result['raw'] . "\n";
        }
    }
}

// ====================================
// TRAITEMENT PRINCIPAL
// ====================================

$action = $argv[1] ?? 'help';

switch ($action) {
    case 'create':
        createTestPayment();
        break;
        
    case 'check':
        $paymentRef = $argv[2] ?? null;
        if (!$paymentRef) {
            echo "❌ Erreur: Payment reference manquant\n";
            echo "Usage: php konnect_test.php check [payment_ref]\n";
            exit(1);
        }
        checkPaymentStatus($paymentRef);
        break;
        
    default:
        echo "=== TEST API KONNECT - AIDE ===\n";
        echo "Usage:\n";
        echo "  php konnect_test.php create           -> Créer un paiement test\n";
        echo "  php konnect_test.php check [ref]      -> Vérifier un paiement\n\n";
        echo "Exemples:\n";
        echo "  php konnect_test.php create\n";
        echo "  php konnect_test.php check 68cb42a7be866d2d2805e6c0\n\n";
        echo "Configuration actuelle:\n";
        echo "- Environnement: Sandbox\n";
        echo "- Wallet ID: " . RECEIVER_WALLET_ID . "\n";
        echo "- API URL: " . API_BASE_URL . "\n";
        break;
}

echo "\n=== FIN ===\n";
?>