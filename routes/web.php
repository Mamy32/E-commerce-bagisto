<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;



Route::get('/force-pay/{id}', function ($id) {
    $order = \Webkul\Sales\Models\Order::find($id);
    if (!$order) return 'Order not found';
    
    $result = [];
    
    // 1. Create Invoice
    if ($order->canInvoice()) {
        $invoiceRepository = app(\Webkul\Sales\Repositories\InvoiceRepository::class);
        $invoiceData = ['order_id' => $order->id, 'invoice' => ['items' => []]];
        foreach ($order->items as $item) {
            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
        }
        $invoice = $invoiceRepository->create($invoiceData);
        $result[] = '✅ Invoice created successfully.';
    } else {
        $result[] = 'ℹ️ Order is already invoiced.';
    }

    // 2. Create Shipment via Biteship
    if ($order->canShip()) {
        try {
            $biteshipService = app(\Fashion\Biteship\Services\BiteshipService::class);
            $shipmentRepository = app(\Webkul\Sales\Repositories\ShipmentRepository::class);
            
            $parts = explode('_', $order->shipping_method);
            if (count($parts) >= 3) {
                $courierCompany = $parts[1];
                $courierType = $parts[2];
                
                $apiOrder = $biteshipService->createShippingOrder($order, $courierCompany, $courierType);
                
                if ($apiOrder && isset($apiOrder['id'])) {
                    $waybill = $apiOrder['courier']['waybill_id'] ?? $apiOrder['id'];
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
                    $shipmentRepository->create($shipmentData);
                    $result[] = '🚀 Biteship Shipment created automatically! Waybill: ' . $waybill;
                } else {
                    $result[] = '❌ Biteship API failed: ' . json_encode($apiOrder);
                }
            } else {
                $result[] = '❌ Invalid shipping method format: ' . $order->shipping_method;
            }
        } catch (\Exception $e) {
            $result[] = '❌ Biteship API Error: ' . $e->getMessage();
        }
    } else {
        $result[] = 'ℹ️ Order is already shipped or cannot be shipped.';
    }
    
    return implode('<br><br>', $result);
});

Route::get('/admin/locale/switch/{code}', function ($code) {
    if (in_array($code, core()->getAllLocales()->pluck('code')->toArray())) {
        session()->put('admin_locale', $code);
    }
    return redirect()->back();
})->name('admin.locale.switch');

Route::get('/fix-css-all', function () {
    $rows = DB::table('theme_customization_translations')->where('theme_customization_id', 3)->get();
    
    foreach ($rows as $row) {
        $options = json_decode($row->options, true);
        if (isset($options['css'])) {
            $css = $options['css'];
            
            // Replace the max-width: 520px block
            $oldCss = "@media (max-width: 520px) {\n    .top-collection-grid { padding-left: 15px; padding-right: 15px; }\n    .top-collection-card h3 { font-size: 12px; }\n}";
            $oldCss2 = "@media (max-width:520px) { .top-collection-grid{padding-left: 15px;padding-right: 15px;} .top-collection-card h3 {font-size:18px; bottom: 10px;}}";
            
            $newCss = "@media (max-width: 520px) {\n    .top-collection-grid { padding-left: 15px; padding-right: 15px; row-gap: 24px; }\n    .top-collection-card { width: 100%; }\n    .top-collection-card h3 { font-size: 14px; }\n}";
            
            if (strpos($css, $oldCss) !== false) {
                $options['css'] = str_replace($oldCss, $newCss, $css);
            } elseif (strpos($css, $oldCss2) !== false) {
                $options['css'] = str_replace($oldCss2, $newCss, $css);
            } else {
                // If it already has the fix or something else, append it just in case? 
                // No, better to just str_replace. If they manually pasted from EN, it might already have newCss.
            }
            
            // Just to be absolutely safe, let's inject width: 100% directly if it's missing
            if (strpos($options['css'], '.top-collection-card { width: 100%; }') === false) {
                $options['css'] .= " \n@media (max-width: 520px) { .top-collection-grid { row-gap: 24px; } .top-collection-card { width: 100%; } }";
            }
            
            DB::table('theme_customization_translations')->where('id', $row->id)->update(['options' => json_encode($options)]);
        }
    }
    
    // Clear the cache so it applies immediately
    Cache::flush();
    
    return "CSS updated for ALL languages! Cache cleared. Please check your website on mobile now.";
});

Route::get('/run-theme-translation', function () {
    \Illuminate\Support\Facades\Artisan::call('theme:translate-id');
    \Illuminate\Support\Facades\Cache::flush();
    return "Theme Customizations translated to Indonesian! Cache has been cleared. You can now check the homepage in Indonesian.";
});

Route::get('/fix-email-settings', function () {
    // Fix the MAIL_FROM_NAME in .env
    $envPath = base_path('.env');
    if (file_exists($envPath)) {
        $envContent = file_get_contents($envPath);
        $envContent = preg_replace('/^MAIL_FROM_NAME=.*$/m', 'MAIL_FROM_NAME="Fjc Fashion"', $envContent);
        // If not exists, append it
        if (strpos($envContent, 'MAIL_FROM_NAME=') === false) {
            $envContent .= "\nMAIL_FROM_NAME=\"Fjc Fashion\"";
        }
        file_put_contents($envPath, $envContent);
    }
    
    // Clear caches to apply translations and env changes
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');

});

Route::get('/api/biteship/track/{waybill}/{courier?}', function ($waybill, $courier = null) {
    $service = app(\Fashion\Biteship\Services\BiteshipService::class);
    
    return response()->json($service->getTrackingStatus($waybill, $courier));
});
