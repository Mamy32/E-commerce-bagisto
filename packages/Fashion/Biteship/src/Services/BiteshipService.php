<?php

namespace Fashion\Biteship\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BiteshipService
{
    protected $apiKey;
    protected $baseUrl;
    protected $originAreaId;

    public function __construct()
    {
        $this->apiKey = core()->getConfigData('sales.carriers.biteship.api_key');
        $this->originAreaId = core()->getConfigData('sales.carriers.biteship.origin_area_id');
        $environment = core()->getConfigData('sales.carriers.biteship.environment');

        // Always use v1 for Biteship API
        $this->baseUrl = 'https://api.biteship.com/v1';
    }

    /**
     * Fetch shipping rates from Biteship.
     */
    public function getRates($destinationAreaId, $weight, $couriers)
    {
        if (empty($this->apiKey) || empty($this->originAreaId)) {
            Log::error('Biteship: API Key or Origin Area ID is not configured.');
            return [];
        }

        if (empty($couriers)) {
            Log::error('Biteship: No couriers active.');
            return [];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
                'Content-Type'  => 'application/json',
            ])->post($this->baseUrl . '/rates/couriers', [
                'origin_area_id'      => $this->originAreaId,
                'destination_area_id' => $destinationAreaId,
                'couriers'            => implode(',', $couriers),
                'items'               => [
                    [
                        'name'   => 'Fashion Items',
                        'value'  => 100000,
                        'weight' => $weight,
                        'quantity' => 1
                    ]
                ]
            ]);

            if ($response->successful()) {
                return $response->json()['pricing'] ?? [];
            }

            Log::error('Biteship API Error', [
                'status'   => $response->status(),
                'response' => $response->body()
            ]);

        } catch (\Exception $e) {
            Log::error('Biteship Connection Error: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Get Area ID from Postal Code
     */
    public function getAreaId($postalCode)
    {
        if (empty($this->apiKey)) {
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])->get($this->baseUrl . '/maps/areas', [
                'countries' => 'ID',
                'input'     => $postalCode,
                'type'      => 'single'
            ]);

            if ($response->successful()) {
                $areas = $response->json()['areas'] ?? [];
                if (count($areas) > 0) {
                    return $areas[0]['id'];
                }
            }
        } catch (\Exception $e) {
            Log::error('Biteship Area Search Error: ' . $e->getMessage());
        }

        return null;
    }
}
