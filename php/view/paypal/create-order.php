<?php

require 'vendor/autoload.php';

$clientId = $_ENV['PAYPAL_CLIENT_ID'];
$secret = $_ENV['PAYPAL_SECRET'];

$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => "https://api-m.sandbox.paypal.com/v1/oauth2/token",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_USERPWD => "$clientId:$secret",
    CURLOPT_POSTFIELDS => "grant_type=client_credentials"
]);

$response = json_decode(curl_exec($ch), true);
$accessToken = $response['access_token'];

curl_setopt_array($ch, [
    CURLOPT_URL => "https://api-m.sandbox.paypal.com/v2/checkout/orders",
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer $accessToken"
    ],
    CURLOPT_POSTFIELDS => json_encode([
        "intent" => "CAPTURE",
        "purchase_units" => [[
            "amount" => [
                "currency_code" => "EUR",
                "value" => "10.00"
            ]
        ]]
    ])
]);

$order = json_decode(curl_exec($ch), true);

echo json_encode($order);
