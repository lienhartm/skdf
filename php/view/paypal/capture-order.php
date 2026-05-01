<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

// Charger le .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();

// Récupérer l'orderID envoyé par le front
$data = json_decode(file_get_contents("php://input"), true);
$orderID = $data['orderID'] ?? null;

if (!$orderID) {
    http_response_code(400);
    echo json_encode(['error' => 'orderID manquant']);
    exit;
}

$clientId = $_ENV['PAYPAL_CLIENT_ID'] ?? null;
$secret   = $_ENV['PAYPAL_SECRET'] ?? null;

if (!$clientId || !$secret) {
    http_response_code(500);
    echo json_encode(['error' => 'PayPal credentials manquantes']);
    exit;
}

//
// 1️⃣ Récupérer le token OAuth
//
$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => "https://api-m.sandbox.paypal.com/v1/oauth2/token",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_USERPWD => "$clientId:$secret",
    CURLOPT_POSTFIELDS => "grant_type=client_credentials",
    CURLOPT_HTTPHEADER => [
        "Accept: application/json",
        "Accept-Language: en_US"
    ]
]);

$response = curl_exec($ch);

if (!$response) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur token']);
    exit;
}

$result = json_decode($response, true);
$accessToken = $result['access_token'] ?? null;

if (!$accessToken) {
    http_response_code(500);
    echo json_encode(['error' => 'Token invalide']);
    exit;
}

//
// 2️⃣ Capturer le paiement
//
curl_setopt_array($ch, [
    CURLOPT_URL => "https://api-m.sandbox.paypal.com/v2/checkout/orders/$orderID/capture",
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode([]),
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer $accessToken"
    ]
]);

$response = curl_exec($ch);

if (!$response) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur capture']);
    exit;
}

$capture = json_decode($response, true);

curl_close($ch);

//
// 3️⃣ Réponse au front
//
echo json_encode($capture);
