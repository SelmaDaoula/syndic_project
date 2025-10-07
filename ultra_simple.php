<?php
// Test avec paramètres absolument minimums
define('API_KEY', '68ca983d206e1e3c35298562:bRiZFiUYZEVzzFDhcsohyu');
define('RECEIVER_WALLET_ID', '68ca983d206e1e3c35298568');

$data = [
    'receiverWalletId' => RECEIVER_WALLET_ID,
    'amount' => 100,
    'token' => 'TND'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.sandbox.konnect.network/api/v2/payments/init-payment');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'x-api-key: ' . API_KEY,
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Code: " . $httpCode . "\n";
echo "Réponse: " . $response . "\n";

if ($httpCode === 200) {
    $result = json_decode($response, true);
    echo "\nURL générée: " . $result['payUrl'] . "\n";
    echo "Testez cette URL dans le navigateur.\n";
}
?>