<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();

function parseDate($value)
{
    if (!$value) {
        return null;
    }
    $date = DateTime::createFromFormat('Y-m-d', $value);
    return $date ?: null;
}

function calculateNights($arrival, $departure)
{
    if (!$arrival || !$departure) {
        return 0;
    }
    $interval = $arrival->diff($departure);
    return max(0, (int) $interval->days);
}

function getNightlyRate($statut)
{
    $prices = [
        'visiteur' => 25.00,
        'inscrit' => 22.00,
        'membre' => 18.00,
    ];
    return $prices[$statut] ?? $prices['visiteur'];
}

$data = json_decode(file_get_contents('php://input'), true) ?: [];

$statut = $data['statut'] ?? 'visiteur';
$arrivalDate = parseDate($data['arrivalDate'] ?? '');
$departureDate = parseDate($data['departureDate'] ?? '');

$nights = calculateNights($arrivalDate, $departureDate);
$nightlyRate = getNightlyRate($statut);
$amount = $nights > 0 ? number_format($nights * $nightlyRate, 2, '.', '') : '10.00';

$clientId = $_ENV['PAYPAL_CLIENT_ID'] ?? null;
$secret = $_ENV['PAYPAL_SECRET'] ?? null;

if (!$clientId || !$secret) {
    http_response_code(500);
    echo json_encode(['error' => 'PayPal credentials manquantes']);
    exit;
}

$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => 'https://api-m.sandbox.paypal.com/v1/oauth2/token',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_USERPWD => "$clientId:$secret",
    CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
    CURLOPT_HTTPHEADER => [
        'Accept: application/json',
        'Accept-Language: en_US'
    ]
]);

$response = curl_exec($ch);
if (!$response) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur token PayPal']);
    exit;
}

$responseData = json_decode($response, true);
$accessToken = $responseData['access_token'] ?? null;
if (!$accessToken) {
    http_response_code(500);
    echo json_encode(['error' => 'Accès PayPal impossible']);
    exit;
}

curl_setopt_array($ch, [
    CURLOPT_URL => 'https://api-m.sandbox.paypal.com/v2/checkout/orders',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode([
        'intent' => 'CAPTURE',
        'purchase_units' => [[
            'amount' => [
                'currency_code' => 'EUR',
                'value' => $amount
            ]
        ]]
    ]),
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $accessToken
    ]
]);

$order = curl_exec($ch);
if (!$order) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur création commande PayPal']);
    exit;
}

curl_close($ch);

echo $order;
