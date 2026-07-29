<?php

namespace Fashion\Duitku\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\OrderRepository;
use Fashion\Duitku\Services\DuitkuService;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $orderRepository;
    protected $duitkuService;

    public function __construct(
        OrderRepository $orderRepository,
        DuitkuService $duitkuService
    ) {
        $this->orderRepository = $orderRepository;
        $this->duitkuService = $duitkuService;
    }

    /**
     * Redirects to the Duitku payment page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect()
    {
        $cart = Cart::getCart();

        if (! $cart) {
            return redirect()->route('shop.checkout.cart.index');
        }

        try {
            // Find the pending order associated with the current cart
            $order = $this->orderRepository->findOneWhere([
                'cart_id' => $cart->id,
                'status'  => 'pending'
            ]);

            if (! $order) {
                throw new \Exception('No pending order found for this checkout.');
            }

            $paymentUrl = $this->duitkuService->createInvoice($order);

            return redirect($paymentUrl);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->route('shop.checkout.cart.index');
        }
    }

    /**
     * Handle the Duitku webhook callback.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function webhook(Request $request)
    {
        try {
            Log::info('Duitku Webhook Received', $request->all());
            
            $processed = $this->duitkuService->processWebhook($request->all());

            if ($processed) {
                return response()->json(['message' => 'OK'], 200);
            } else {
                return response()->json(['message' => 'Noted'], 200);
            }
        } catch (\Exception $e) {
            Log::error('Duitku Webhook Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }

    /**
     * Redirect to success page after payment.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function success()
    {
        // Bagisto handles clearing the cart internally via its own success route
        return redirect()->route('shop.checkout.onepage.success');
    }
}
