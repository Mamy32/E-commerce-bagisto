<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shipping Label - {{ $shipment->order->increment_id }}</title>
    <style>
        @page { size: 100mm 150mm; margin: 0; } /* Standard shipping label size */
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; margin: 0; padding: 20px; color: #000; background: #f5f5f5; }
        .label-container { width: 100%; max-width: 100mm; min-height: 140mm; margin: 0 auto; background: #fff; border: 2px solid #000; padding: 20px; box-sizing: border-box; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 15px; }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; }
        .address-block { margin-bottom: 20px; }
        .address-block h3 { margin: 0 0 5px 0; font-size: 14px; text-transform: uppercase; color: #555; }
        .address-block p { margin: 0; font-size: 14px; line-height: 1.4; }
        .details-grid { display: grid; grid-template-columns: 1fr 1fr; border-top: 2px solid #000; border-bottom: 2px solid #000; padding: 10px 0; margin-bottom: 20px; }
        .details-grid div { font-size: 14px; }
        .details-grid strong { display: block; font-size: 12px; color: #555; text-transform: uppercase; margin-bottom: 2px; }
        .barcode-section { text-align: center; margin-top: 20px; }
        .barcode-section svg { max-width: 100%; height: auto; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; border-top: 1px dashed #ccc; padding-top: 10px; }
        
        @media print {
            body { background: #fff; padding: 0; }
            .label-container { border: none; max-width: none; min-height: auto; }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
</head>
<body>
    @php
        $order = $shipment->order;
        $shippingAddress = $order->shipping_address;
    @endphp

    <div class="label-container">
        <div class="header">
            <h1>{{ core()->getConfigData('general.general.email_settings.sender_name') ?: 'JFC Fashion' }}</h1>
            <p style="margin: 5px 0 0; font-size: 12px;">Shipping Label</p>
        </div>

        <div class="address-block">
            <h3>Ship To:</h3>
            <p>
                <strong>{{ $shippingAddress->first_name }} {{ $shippingAddress->last_name }}</strong><br>
                {{ $shippingAddress->address1 }}<br>
                @if($shippingAddress->address2) {{ $shippingAddress->address2 }}<br> @endif
                {{ $shippingAddress->city }}, {{ $shippingAddress->state }} {{ $shippingAddress->postcode }}<br>
                {{ core()->country_name($shippingAddress->country) }}<br>
                Phone: {{ $shippingAddress->phone }}
            </p>
        </div>

        <div class="address-block">
            <h3>Return Address:</h3>
            <p>
                {{ core()->getConfigData('general.general.email_settings.sender_name') ?: 'JFC Fashion' }}<br>
                {{ core()->getConfigData('sales.shipping.origin.address1') ?: 'Store Warehouse Address' }}
            </p>
        </div>

        <div class="details-grid">
            <div>
                <strong>Order ID</strong>
                #{{ $order->increment_id }}
            </div>
            <div>
                <strong>Courier</strong>
                {{ $shipment->carrier_title ?? 'Standard Shipping' }}
            </div>
        </div>

        @if($shipment->track_number)
            <div class="barcode-section">
                <strong style="display:block; font-size: 12px; color: #555; text-transform: uppercase; margin-bottom: 5px;">Tracking Number</strong>
                <svg id="barcode"></svg>
            </div>
            
            <script>
                JsBarcode("#barcode", "{{ $shipment->track_number }}", {
                    format: "CODE128",
                    lineColor: "#000",
                    width: 2,
                    height: 80,
                    displayValue: true,
                    fontSize: 16
                });
            </script>
        @else
            <div class="barcode-section">
                <p style="font-size: 14px; color: #666;">No tracking number assigned.</p>
            </div>
        @endif
        
        <div class="footer">
            Date: {{ \Carbon\Carbon::now()->format('d M Y H:i') }} | Qty: {{ $shipment->total_qty }}
        </div>
    </div>

    <script>
        // Wait for barcode to render, then open print dialog
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
