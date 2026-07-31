<?php

namespace Fashion\Biteship\Listeners;

use Fashion\Biteship\Services\BiteshipService;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\ShipmentRepository;
use Illuminate\Support\Facades\Log;

class InvoiceSavedListener
{
    protected $biteshipService;
    protected $orderRepository;
    protected $shipmentRepository;

    public function __construct(
        BiteshipService $biteshipService,
        OrderRepository $orderRepository,
        ShipmentRepository $shipmentRepository
    ) {
        $this->biteshipService = $biteshipService;
        $this->orderRepository = $orderRepository;
        $this->shipmentRepository = $shipmentRepository;
    }

    public function handle($invoice)
    {
        $order = $this->orderRepository->find($invoice->order_id);

        if (! $order) {
            return;
        }

        // Check if shipping method is biteship
        if (strpos($order->shipping_method, 'biteship_') !== 0) {
            return;
        }

        // If a shipment already exists, do not recreate
        if ($order->shipments->count() > 0) {
            return;
        }

        // Parse courier info: biteship_sicepat_best
        $parts = explode('_', $order->shipping_method);
        if (count($parts) < 3) {
            return;
        }
        $courierCompany = $parts[1];
        $courierType = $parts[2];

        try {
            $apiOrder = $this->biteshipService->createShippingOrder($order, $courierCompany, $courierType);

            if ($apiOrder && isset($apiOrder['id'])) {
                // Biteship uses waybill_id or sometimes it returns tracking number inside waybill
                $waybill = $apiOrder['courier']['waybill_id'] ?? $apiOrder['id'];
                
                // Prepare shipment data
                $shipmentData = [
                    'order_id' => $order->id,
                    'shipment' => [
                        'carrier_title' => $order->shipping_title,
                        'track_number' => $waybill,
                        'source' => 1,
                        'items' => []
                    ]
                ];

                foreach ($order->items as $item) {
                    if ($item->qty_to_ship > 0 && $item->type != 'virtual') {
                        $shipmentData['shipment']['items'][$item->id] = [1 => $item->qty_to_ship];
                    }
                }

                if (!empty($shipmentData['shipment']['items'])) {
                    $shipment = $this->shipmentRepository->create($shipmentData);
                    Log::info('Biteship: Shipment created automatically for order ' . $order->id . ' with waybill ' . $waybill);
                } else {
                    Log::warning('Biteship: No items to ship for order ' . $order->id);
                }
            }
        } catch (\Exception $e) {
            Log::error('Biteship Listener Error: ' . $e->getMessage());
        }
    }
}
