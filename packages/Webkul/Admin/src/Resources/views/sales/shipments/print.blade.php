<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shipping Label - {{ $shipment->order->increment_id }}</title>
    <style>
        @page { size: 100mm 150mm; margin: 0; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; margin: 0; padding: 20px; color: #000; background: #525659; display: flex; justify-content: center; }
        
        .label-container { 
            width: 100mm; 
            min-height: 150mm; 
            background: #fff; 
            border: 3px solid #000; 
            box-sizing: border-box; 
            display: flex;
            flex-direction: column;
        }

        .row {
            border-bottom: 2px solid #000;
            padding: 8px 12px;
        }
        .row.no-border { border-bottom: none; }
        
        /* Row 1: Header Logos */
        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
        }
        .courier-logo {
            color: #d32f2f;
            font-weight: 900;
            font-style: italic;
            font-size: 24px;
            letter-spacing: -1px;
        }
        .biteship-logo-container {
            text-align: center;
        }
        .biteship-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            font-weight: bold;
            letter-spacing: -0.5px;
        }
        .biteship-icon {
            color: #673ab7;
            margin-right: 5px;
            font-size: 28px;
        }
        .biteship-subtext {
            font-size: 10px;
            color: #000;
            margin-top: 2px;
        }

        /* Row 2: Main Barcode */
        .main-barcode-row {
            text-align: center;
            padding: 15px 10px 10px;
        }
        .main-barcode-row svg {
            width: 100%;
            height: 80px;
        }
        .resi-text {
            font-size: 14px;
            font-weight: bold;
            margin-top: 5px;
        }

        /* Row 3: Shipping Details */
        .shipping-details-row {
            text-align: center;
            font-size: 12px;
            line-height: 1.5;
            padding: 8px 10px;
        }

        /* Row 4: Reference & Weight */
        .ref-weight-row {
            display: flex;
            padding: 0;
        }
        .ref-col {
            flex: 0 0 70%;
            padding: 8px 12px;
            border-right: 2px solid #000;
        }
        .weight-col {
            flex: 0 0 30%;
            padding: 8px 12px;
            font-size: 11px;
            line-height: 2;
        }
        .ref-title { font-size: 10px; }
        .ref-barcode svg { width: 100%; height: 40px; margin-top: 5px; }
        .ref-text { font-size: 10px; margin-top: 2px; }

        /* Row 5: Addresses */
        .address-row {
            display: flex;
            padding: 0;
            min-height: 120px;
        }
        .address-col {
            flex: 0 0 50%;
            padding: 8px 12px;
            font-size: 10px;
            line-height: 1.3;
        }
        .address-col.left {
            border-right: 2px solid #000;
        }
        .address-title {
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        /* Row 6 & 7: Items and Notes */
        .info-row {
            display: flex;
            font-size: 10px;
            line-height: 1.4;
        }
        .info-label {
            flex: 0 0 80px;
        }
        .info-value {
            flex: 1;
        }

        /* Row 8: Footer */
        .footer-row {
            text-align: center;
            font-size: 10px;
            padding: 10px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        @media print {
            body { background: #fff; padding: 0; display: block; }
            .label-container { border: 3px solid #000; width: 100%; height: 100%; }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
</head>
<body>
    @php
        $order = $shipment->order;
        $shippingAddress = $order->shipping_address;
        
        // Extract courier name from shipping_method (e.g. biteship_jne_reg -> JNE)
        $methodParts = explode('_', $order->shipping_method);
        $courierName = isset($methodParts[1]) ? strtoupper($methodParts[1]) : 'COURIER';
        if ($courierName === 'SHIPPING' || $courierName === 'BITESHIP') {
            // Fallback if the method format is unexpected, try parsing the title
            $titleParts = explode(' ', $shipment->carrier_title);
            $courierName = isset($titleParts[1]) ? strtoupper(str_replace('-', '', $titleParts[1])) : 'J&T';
        }

        $logo = core()->getCurrentChannel()->logo_url;
    @endphp

    <div class="label-container">
        
        <!-- Row 1: Header -->
        <div class="row header-row">
            <div class="courier-logo">{{ $courierName }}</div>
            <div class="biteship-logo-container">
                @if ($logo)
                    <img src="{{ $logo }}" alt="{{ core()->getCurrentChannel()->name }}" style="max-height: 40px;">
                @else
                    <div class="biteship-logo" style="color:#bfa15f;">
                        {{ core()->getConfigData('general.general.email_settings.sender_name') ?: 'JFC Fashion' }}
                    </div>
                @endif
            </div>
            <div style="width: 40px;"></div> <!-- Spacer for balance -->
        </div>

        <!-- Row 2: Main Barcode -->
        <div class="row main-barcode-row">
            <svg id="main-barcode"></svg>
            <div class="resi-text">Nomor Resi - {{ $shipment->track_number ?: 'TBA' }}</div>
        </div>

        <!-- Row 3: Shipping Details -->
        <div class="row shipping-details-row">
            <div>Ongkos Kirim: Rp. {{ number_format($order->shipping_amount, 0, ',', '.') }}</div>
            <div>Jenis Layanan - Reguler. Kode Rute - {{ strtoupper(substr(md5($shippingAddress->postcode), 0, 9)) }}</div>
        </div>

        <!-- Row 4: Reference & Weight -->
        <div class="row ref-weight-row">
            <div class="ref-col">
                <div class="ref-title">Reference Number</div>
                <div class="ref-barcode"><svg id="ref-barcode"></svg></div>
            </div>
            <div class="weight-col">
                <div>Quantity: {{ $shipment->total_qty }} Pcs</div>
                <div>Weight: {{ $order->items->sum('weight') ?: 1 }} Kg</div>
            </div>
        </div>

        <!-- Row 5: Addresses -->
        <div class="row address-row">
            <div class="address-col left">
                <div class="address-title">Alamat Penerima:</div>
                <div style="font-weight: bold; font-size: 11px;">{{ $shippingAddress->first_name }} {{ $shippingAddress->last_name }}</div>
                <div>{{ $shippingAddress->phone }}</div>
                <div>{{ $shippingAddress->address1 }}@if($shippingAddress->address2), {{ $shippingAddress->address2 }}@endif</div>
                <div>{{ $shippingAddress->city }}, {{ $shippingAddress->state }}, {{ $shippingAddress->postcode }}</div>
            </div>
            <div class="address-col right">
                <div class="address-title">Alamat Pengirim:</div>
                <div style="font-weight: bold; font-size: 11px;">{{ core()->getConfigData('general.general.email_settings.sender_name') ?: 'Store Admin' }}</div>
                <div>{{ core()->getConfigData('general.general.email_settings.sender_phone') ?: '081234567890' }}</div>
                <div>{{ core()->getConfigData('sales.shipping.origin.address1') ?: 'Store Warehouse Address' }}</div>
            </div>
        </div>

        <!-- Row 6: Items -->
        <div class="row info-row">
            <div class="info-label">Jenis Barang :</div>
            <div class="info-value">
                @foreach($shipment->items as $item)
                    [{{ $item->sku }}] {{ $item->qty }}x {{ $item->name }}<br>
                @endforeach
            </div>
        </div>

        <!-- Row 7: Notes -->
        <div class="row info-row">
            <div class="info-label">Catatan :</div>
            <div class="info-value">Order #{{ $order->increment_id }}</div>
        </div>

        <!-- Row 8: Footer -->
        <div class="row footer-row no-border">
            <div>Pengiriman melalui platform Biteship</div>
            <div>biteship.com</div>
        </div>

    </div>

    <script>
        // Generate main barcode (Waybill)
        var waybill = "{{ $shipment->track_number ?: 'WYB-000000000' }}";
        JsBarcode("#main-barcode", waybill, {
            format: "CODE128",
            lineColor: "#000",
            width: 3,
            height: 100,
            displayValue: false,
            margin: 0
        });

        // Generate reference barcode (Order Hash/ID)
        var reference = "{{ md5($order->increment_id) }}".substring(0, 24);
        JsBarcode("#ref-barcode", reference, {
            format: "CODE128",
            lineColor: "#000",
            width: 2,
            height: 40,
            displayValue: true,
            fontSize: 10,
            textMargin: 2,
            margin: 0
        });

        // Wait for barcodes to render, then open print dialog
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
