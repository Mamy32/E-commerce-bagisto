<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$apiKey = core()->getConfigData('sales.carriers.biteship.api_key');
$waybillId = 'WYB-1786965420225';
$courier = 'biteship';

$endpoint = 'https://api.biteship.com/v1/trackings/' . $waybillId . '/couriers/' . $courier;

$ch = curl_init($endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: ' . $apiKey
]);
$result = curl_exec($ch);
curl_close($ch);

header('Content-Type: application/json');
echo json_encode([
    'endpoint' => $endpoint,
    'response' => json_decode($result)
]);
