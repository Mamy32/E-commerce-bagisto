<?php

namespace Fashion\Biteship\Carriers;

use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Shipping\Carriers\AbstractShipping;
use Fashion\Biteship\Services\BiteshipService;

class Biteship extends AbstractShipping
{
    /**
     * Shipping method carrier code.
     *
     * @var string
     */
    protected $code = 'biteship';

    /**
     * @var BiteshipService
     */
    protected $biteshipService;

    public function __construct()
    {
        $this->biteshipService = app(BiteshipService::class);
    }

    /**
     * Calculate rates for Biteship.
     *
     * @return CartShippingRate[]|false
     */
    public function calculate()
    {
        if (! $this->isAvailable()) {
            return false;
        }

        $cart = Cart::getCart();
        
        if (! $cart) {
            return false;
        }

        $shippingAddress = $cart->shipping_address;
        if (! $shippingAddress || empty($shippingAddress->postcode)) {
            return false;
        }

        // 1. Get Destination Area ID
        $destinationAreaId = $this->biteshipService->getAreaId($shippingAddress->postcode);
        
        if (! $destinationAreaId) {
            return false;
        }

        // 2. Calculate Total Weight
        $totalWeight = $this->calculateTotalWeight($cart);
        
        // Ensure minimum weight of 100g if 0
        if ($totalWeight <= 0) {
            $totalWeight = (float) $this->getConfigData('default_weight') ?: 1000;
        }

        // 3. Get Active Couriers
        $activeCouriers = $this->getConfigData('active_couriers');
        if (is_string($activeCouriers)) {
            $activeCouriers = explode(',', $activeCouriers);
        }

        if (empty($activeCouriers)) {
            return false;
        }

        // 4. Fetch Rates from API
        $apiRates = $this->biteshipService->getRates($destinationAreaId, $totalWeight, $activeCouriers);

        if (empty($apiRates)) {
            return false;
        }

        // 5. Convert API Rates to Bagisto Rates
        $rates = [];

        foreach ($apiRates as $rate) {
            $cartShippingRate = new CartShippingRate;

            $cartShippingRate->carrier = $this->code;
            $cartShippingRate->carrier_title = $this->getConfigData('title') ?: 'Biteship';
            $cartShippingRate->method = $this->code . '_' . $rate['courier_code'] . '_' . $rate['courier_service_code'];
            $cartShippingRate->method_title = strtoupper($rate['courier_name']) . ' - ' . $rate['courier_service_name'];
            $cartShippingRate->method_description = 'Estimated Delivery: ' . $rate['duration'];
            
            $cartShippingRate->price = core()->convertPrice($rate['price']);
            $cartShippingRate->base_price = $rate['price'];

            $rates[] = $cartShippingRate;
        }

        return $rates ?: false;
    }

    /**
     * Calculate total weight of the cart items.
     */
    protected function calculateTotalWeight($cart)
    {
        $weight = 0;

        foreach ($cart->items as $item) {
            if ($item->getTypeInstance()->isStockable()) {
                $itemWeight = $item->weight ?: $this->getConfigData('default_weight') ?: 1000;
                $weight += $itemWeight * $item->quantity;
            }
        }

        return $weight;
    }
}
