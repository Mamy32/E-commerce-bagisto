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
    public function getRates($destinationAreaId, $destinationPostcode, $weight, $couriers)
    {
        if (empty($this->apiKey) || empty($this->originAreaId)) {
            Log::error('Biteship: API Key or Origin Area ID is not configured.');
            // Continue to fallback
        } elseif (empty($couriers)) {
            Log::error('Biteship: No couriers active.');
            // Continue to fallback
        } else {


        try {
            // Extract origin postal code from originAreaId (e.g., IDNP6...IDZ11210 -> 11210)
            $originPostcode = '11210';
            if (preg_match('/IDZ(\d+)$/', $this->originAreaId, $matches)) {
                $originPostcode = $matches[1];
            }

            // Fetch coordinates
            $originCoords = $this->getCoordinatesFromPostcode($originPostcode);
            $destCoords = $this->getCoordinatesFromPostcode($destinationPostcode);

            $payload = [
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
            ];

            // If coordinates exist, append them to the payload for Instant Couriers
            if ($originCoords && $destCoords) {
                $payload['origin_latitude'] = $originCoords['latitude'];
                $payload['origin_longitude'] = $originCoords['longitude'];
                $payload['destination_latitude'] = $destCoords['latitude'];
                $payload['destination_longitude'] = $destCoords['longitude'];
            }

            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
                'Content-Type'  => 'application/json',
            ])->post($this->baseUrl . '/rates/couriers', $payload);

            if ($response->successful()) {
                return $response->json()['pricing'] ?? [];
            }

            Log::error('Biteship API Error', [
                'status'   => $response->status(),
                'response' => $response->body()
            ]);
            return [];
        } catch (\Exception $e) {
            Log::error('Biteship Connection Error: ' . $e->getMessage());
        }
        } // End of else block

        return [];
    }

    /**
     * Get Coordinates from Postal Code using free OpenStreetMap API
     */
    public function getCoordinatesFromPostcode($postcode)
    {
        if (empty($postcode)) {
            return null;
        }

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Bagisto-Biteship-Integration/1.0'
            ])->get('https://nominatim.openstreetmap.org/search', [
                'postalcode' => $postcode,
                'country' => 'Indonesia',
                'format' => 'json'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data) && isset($data[0]['lat']) && isset($data[0]['lon'])) {
                    return [
                        'latitude' => $data[0]['lat'],
                        'longitude' => $data[0]['lon']
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::error('Nominatim Geocoding Error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Get Area ID from Postal Code
     */
    public function getAreaId($postalCode)
    {
        if (empty($this->apiKey)) {
            return 'IDNP11';
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

        // Return a dummy Area ID so the checkout doesn't get blocked
        return 'IDNP11';
    }

    /**
     * Create a Shipping Order in Biteship
     *
     * @param \Webkul\Sales\Contracts\Order $order
     * @param string $courierCompany
     * @param string $courierType
     * @return array|false
     */
    public function createShippingOrder($order, $courierCompany, $courierType)
    {
        if (empty($this->apiKey) || empty($this->originAreaId)) {
            Log::error('Biteship: Cannot create order, API Key or Origin Area ID is missing.');
            return false;
        }

        $items = [];
        $totalWeight = 0;

        foreach ($order->items as $item) {
            $itemWeight = $item->weight ?: 1000;
            $totalWeight += $itemWeight * $item->qty_ordered;
            
            $items[] = [
                'name' => $item->name,
                'description' => $item->name,
                'sku' => $item->sku ?? 'SKU-001',
                'value' => (int) $item->price,
                'quantity' => (int) $item->qty_ordered,
                'weight' => (int) $itemWeight
            ];
        }

        if ($totalWeight <= 0) {
            $totalWeight = 1000;
        }

        // Get shipping address
        $shippingAddress = $order->shipping_address;
        $destinationAreaId = $this->getAreaId($shippingAddress->postcode);

        $payload = [
            'origin_area_id' => $this->originAreaId,
            'destination_area_id' => $destinationAreaId,
            'courier_company' => $courierCompany,
            'courier_type' => $courierType,
            'courier_insurance' => 0,
            'delivery_type' => 'now',
            'order_note' => 'Order #' . $order->increment_id,
            'items' => $items,
            'origin_contact_name' => core()->getConfigData('sales.shipping.origin.store_name') ?: (core()->getConfigData('general.general.email_settings.sender_name') ?: 'Store Admin'),
            'origin_contact_phone' => core()->getConfigData('sales.shipping.origin.contact') ?: '081234567890', // Fallback as bagisto might not have this globally
            'origin_address' => core()->getConfigData('sales.shipping.origin.address') ?: 'Store Warehouse Address',
            'destination_contact_name' => $shippingAddress->first_name . ' ' . $shippingAddress->last_name,
            'destination_contact_phone' => $shippingAddress->phone ?: '081234567890',
            'destination_contact_email' => $order->customer_email,
            'destination_address' => $shippingAddress->address1 . ($shippingAddress->address2 ? ', ' . $shippingAddress->address2 : '') . ', ' . $shippingAddress->city . ', ' . $shippingAddress->state . ' ' . $shippingAddress->postcode,
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
                'Content-Type'  => 'application/json',
            ])->post($this->baseUrl . '/orders', $payload);

            if ($response->successful()) {
                Log::info('Biteship Order Created Successfully', $response->json());
                return $response->json();
            }

            Log::error('Biteship Create Order Error', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

        } catch (\Exception $e) {
            Log::error('Biteship Create Order Connection Error: ' . $e->getMessage());
        }

        return false;
    }

    /**
     * Get Tracking Status from Biteship
     *
     * @param string $waybillId
     * @return array|false
     */
    public function getTrackingStatus($waybillId)
    {
        if (empty($this->apiKey)) {
            Log::error('Biteship: Cannot fetch tracking, API Key is missing.');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])->get($this->baseUrl . '/trackings/' . $waybillId);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Biteship Tracking API Error', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
        } catch (\Exception $e) {
            Log::error('Biteship Tracking Connection Error: ' . $e->getMessage());
        }

        return false;
    }
}
