<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shipping Label - {{ $shipment->order->increment_id }}</title>
    <style>
        @page { size: 100mm 150mm; margin: 0; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; margin: 0; padding: 0; color: #000; background: #fff; width: 100mm; height: 150mm; box-sizing: border-box; border: 2px solid #000; overflow: hidden; }
        
        /* Header */
        .header { display: flex; justify-content: space-between; align-items: center; padding: 8px 12px; border-bottom: 2px dashed #000; }
        .header .logo { font-size: 16px; font-weight: bold; color: #ee4d2d; }
        .header .service { font-size: 24px; font-weight: bold; }
        .header .courier { font-size: 18px; font-weight: bold; font-style: italic; color: #d32f2f; }
        
        /* Resi */
        .resi-box-container { padding: 8px; border-bottom: 2px dashed #000; }
        .resi-box { border: 2px solid #000; padding: 6px; text-align: center; font-size: 18px; font-weight: bold; }
        
        /* Main Barcode */
        .barcode-container { text-align: center; padding: 12px 4px; border-bottom: 2px dashed #000; }
        .barcode-container svg { width: 95%; height: 90px; }
        
        /* Addresses */
        .addresses { display: flex; padding: 8px 12px; border-bottom: 2px solid #000; font-size: 11px; line-height: 1.4; }
        .address-left { flex: 1; padding-right: 12px; }
        .address-right { flex: 1; padding-left: 12px; }
        .badge-home { border: 1px solid #000; padding: 2px 6px; font-weight: bold; font-size: 10px; display: inline-block; margin: 4px 0; }
        
        /* Boxes */
        .box-row { display: flex; border-bottom: 2px solid #000; padding: 4px 12px;}
        .box-col { flex: 1; border: 1px solid #000; padding: 4px; text-align: center; font-size: 12px; font-weight: bold; margin: 0 4px; }
        
        .cashless-row { display: flex; border-bottom: 2px solid #000; padding: 6px 12px; font-size: 12px; align-items: center; }
        .cashless-badge { font-weight: bold; border-right: 2px solid #000; padding-right: 12px; margin-right: 12px; }
        .cashless-text { font-style: italic; }
        
        /* Details & Ref Barcode */
        .details-container { display: flex; padding: 8px 12px; border-bottom: 2px solid #000; align-items: center; }
        .details-left { flex: 1; font-size: 11px; line-height: 1.6; }
        .details-right { flex: 0 0 40%; text-align: right; }
        .details-right svg { width: 100%; height: 50px; }
        
        /* Items Table */
        .items-table { width: 100%; font-size: 10px; border-collapse: collapse; margin-top: 4px; }
        .items-table th { border-bottom: 1px solid #000; text-align: left; padding: 4px 8px; font-weight: bold; }
        .items-table td { padding: 4px 8px; vertical-align: top; }
        
        @media print {
            body { border: none; }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
</head>
<body>
    @php
        $order = $shipment->order;
        $shippingAddress = $order->shipping_address;
        
        // Try to get carrier nicely
        $desc = strtoupper(str_replace('biteship_', '', $shipment->carrier_title));
        if(strpos($desc, 'JNE') !== false) $desc = 'JNE';
        elseif(strpos($desc, 'SICEPAT') !== false) $desc = 'SICEPAT';
        elseif(strpos($desc, 'GOSEND') !== false || strpos($desc, 'GOJEK') !== false) $desc = 'GOSEND';
        elseif(strpos($desc, 'GRAB') !== false) $desc = 'GRAB';
        elseif(strpos($desc, 'PAXEL') !== false) $desc = 'PAXEL';
        elseif(strpos($desc, 'NINJA') !== false) $desc = 'NINJA XPRESS';
        elseif(strpos($desc, 'ANTERAJA') !== false) $desc = 'ANTERAJA';
        elseif(strpos($desc, 'LION') !== false) $desc = 'LION PARCEL';
        elseif(strpos($desc, 'J&T') !== false) $desc = 'J&T';

        $weight = $order->items->sum('weight') ?: 1;
        $weightStr = $weight < 1 ? ($weight * 1000) . ' gr' : $weight . ' Kg';
    @endphp

    <!-- Header -->
    <div class="header">
        <div class="logo">🛍️ JFC Fashion</div>
        <div class="service">REG</div>
        <div class="courier">{{ $desc }}</div>
    </div>

    <!-- Resi -->
    <div class="resi-box-container">
        <div class="resi-box">
            No. Resi: {{ $shipment->track_number ?: 'TBA' }}
        </div>
    </div>

    <!-- Main Barcode -->
    <div class="barcode-container">
        <svg id="main-barcode"></svg>
    </div>

    <!-- Addresses -->
    <div class="addresses">
        <div class="address-left">
            <strong>Penerima: {{ $shippingAddress->first_name }} {{ $shippingAddress->last_name }}</strong><br>
            <div class="badge-home">HOME</div> {{ $shippingAddress->phone }}<br>
            {{ $shippingAddress->address1 }}@if($shippingAddress->address2), {{ $shippingAddress->address2 }}@endif, 
            {{ $shippingAddress->city }}, {{ $shippingAddress->state }}, {{ $shippingAddress->postcode }}, {{ $shippingAddress->country }}
        </div>
        <div class="address-right">
            <strong>Pengirim: {{ core()->getConfigData('sales.shipping.origin.store_name') ?: (core()->getConfigData('general.general.email_settings.sender_name') ?: 'Store Admin') }}</strong><br>
            {{ core()->getConfigData('sales.shipping.origin.contact') ?: '081234567890' }}<br>
            {{ core()->getConfigData('sales.shipping.origin.address') ?: 'Store Warehouse Address' }}, 
            {{ core()->getConfigData('sales.shipping.origin.city') ?: 'Jakarta' }},
            {{ core()->getConfigData('sales.shipping.origin.state') ?: 'DKI' }},
            {{ core()->getConfigData('sales.shipping.origin.zipcode') ?: '11210' }}
        </div>
    </div>

    <!-- Boxes -->
    <div class="box-row">
        <div class="box-col">{{ strtoupper($shippingAddress->city) }}</div>
        <div class="box-col">{{ strtoupper(core()->getConfigData('sales.shipping.origin.city') ?: 'JAKARTA') }}</div>
    </div>

    <!-- Cashless -->
    <div class="cashless-row">
        <div class="cashless-badge">CASHLESS</div>
        <div class="cashless-text">Penjual tidak perlu bayar ongkir ke Kurir</div>
    </div>

    <!-- Details -->
    <div class="details-container">
        <div class="details-left">
            <strong>Berat:</strong> {{ $weightStr }} &nbsp;&nbsp;&nbsp;&nbsp; <strong>COD:</strong> Rp0<br>
            <strong>Batas Kirim:</strong> {{ $order->created_at->addDays(2)->format('d-m-Y') }}<br>
            <strong>No. Pesanan:</strong> {{ $order->increment_id }}
        </div>
        <div class="details-right">
            <svg id="ref-barcode"></svg>
        </div>
    </div>

    <!-- Items -->
    <table class="items-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Produk</th>
                <th>SKU</th>
                <th>Qty</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shipment->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->sku }}</td>
                <td>{{ $item->qty }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="4" style="padding-top: 8px;">Pesan: Order #{{ $order->increment_id }}</td>
            </tr>
        </tbody>
    </table>

    <script>
        var waybill = "{{ $shipment->track_number ?: 'WYB-000000000' }}";
        JsBarcode("#main-barcode", waybill, {
            format: "CODE128",
            lineColor: "#000",
            width: 3,
            height: 90,
            displayValue: false,
            margin: 0
        });

        var reference = "{{ $order->increment_id }}";
        JsBarcode("#ref-barcode", reference, {
            format: "CODE128",
            lineColor: "#000",
            width: 2,
            height: 40,
            displayValue: false,
            margin: 0
        });

        window.onload = function() {
            setTimeout(function() { window.print(); }, 500);
        };
    </script>
</body>
</html>
