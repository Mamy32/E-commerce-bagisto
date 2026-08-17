<?php
require "vendor/autoload.php";
$app = require_once "bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$order = \Webkul\Sales\Models\Order::find(14);
echo "shipping_method: " . $order->shipping_method . "\n";
echo "carrier_title: " . $order->shipments->first()->carrier_title . "\n";
