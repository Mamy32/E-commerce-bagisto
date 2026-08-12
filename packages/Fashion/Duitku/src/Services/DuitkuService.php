<?php

namespace Fashion\Duitku\Services;

use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\InvoiceRepository;
use Duitku\Config;
use Duitku\Pop;
use Exception;
use Illuminate\Support\Facades\Log;

class DuitkuService
{
    protected $apiKey;
    protected $merchantCode;
    protected $sandboxMode;
    protected $duitkuConfig;
    protected $orderRepository;
    protected $invoiceRepository;

    public function __construct(
        OrderRepository $orderRepository,
        InvoiceRepository $invoiceRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->invoiceRepository = $invoiceRepository;
        
        $this->apiKey = core()->getConfigData('sales.payment_methods.duitku.api_key') ?? '';
        $this->merchantCode = core()->getConfigData('sales.payment_methods.duitku.merchant_code') ?? '';
        $this->sandboxMode = (bool) core()->getConfigData('sales.payment_methods.duitku.sandbox');

        $this->duitkuConfig = new Config(
            $this->apiKey,
            $this->merchantCode,
            $this->sandboxMode,
            true, // use logging
            false // CORS off by default in backend
        );
    }

    public function getPaymentMethods($amount)
    {
        try {
            $response = Pop::getPaymentMethod((int) round($amount), $this->duitkuConfig);
            return json_decode($response, true)['paymentFee'] ?? [];
        } catch (Exception $e) {
            Log::error('Duitku Get Payment Methods Error: ' . $e->getMessage());
            return [];
        }
    }

    public function createInvoice($order, $selectedMethod = null, $isRetry = false)
    {
        $merchantOrderId = $order->increment_id ?? $order->id;
        if ($isRetry) {
            $merchantOrderId .= '-' . time();
        }

        $params = [
            'paymentAmount' => (int) round($order->grand_total),
            'merchantOrderId' => $merchantOrderId,
            'productDetails' => 'Order #' . ($order->increment_id ?? $order->id),
            'email' => $order->customer_email,
            'callbackUrl' => route('duitku.webhook'),
            'returnUrl' => route('duitku.success', ['order_id' => $order->id]),
        ];

        if ($selectedMethod) {
            $params['paymentMethod'] = $selectedMethod;
        }

        Log::info('Duitku Request', $params);

        try {
            $response = Pop::createInvoice($params, $this->duitkuConfig);
            Log::info('Duitku Response: ' . $response);

            $responseData = json_decode($response, true);
            
            if (isset($responseData['paymentUrl'])) {
                return $responseData['paymentUrl'];
            }
            
            throw new Exception('Payment URL not found in Duitku response');
        } catch (Exception $e) {
            Log::error('Duitku Create Invoice Error: ' . $e->getMessage());
            throw new \RuntimeException('Duitku: ' . $e->getMessage(), 0, $e);
        }
    }

    public function verifySignature($merchantCode, $amount, $merchantOrderId, $signature)
    {
        $localSignature = md5($merchantCode . $amount . $merchantOrderId . $this->apiKey);
        return $signature === $localSignature;
    }

    public function processWebhook(array $data)
    {
        $merchantCode = $data['merchantCode'] ?? '';
        $amount = $data['amount'] ?? '';
        $merchantOrderId = $data['merchantOrderId'] ?? '';
        $signature = $data['signature'] ?? '';

        if (!$this->verifySignature($merchantCode, $amount, $merchantOrderId, $signature)) {
            Log::error('Duitku signature mismatch', [
                'received' => $signature,
            ]);
            throw new Exception('Duitku: Invalid signature.');
        }

        $realMerchantOrderId = explode('-', $merchantOrderId)[0];

        $order = $this->orderRepository->findOneWhere(['increment_id' => $realMerchantOrderId]);
        if (!$order) {
            $order = $this->orderRepository->find($realMerchantOrderId);
        }

        if (!$order) {
            Log::warning('Duitku webhook: order not found', ['order_id' => $merchantOrderId]);
            return false;
        }

        $resultCode = $data['resultCode'] ?? '';

        if ($resultCode !== '00') {
            Log::info('Duitku webhook: non-completed status', $data);
            if ($resultCode === '01') {
                event('sales.order.payment.failed', $order);
            } elseif ($resultCode === '02') {
                event('sales.order.payment.pending', $order);
            }
            return false;
        }

        if ($order->status === 'processing' || $order->status === 'completed' || $order->invoices->count() > 0) {
            return true; // Already processed
        }

        // Generate Invoice in Bagisto to mark as paid
        if ($order->canInvoice()) {
            $invoiceData = [
                'order_id' => $order->id,
            ];

            foreach ($order->items as $item) {
                $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
            }

            $invoice = $this->invoiceRepository->create(array_merge($invoiceData, ['state' => 'paid']));
            
            Log::info('Duitku webhook: successfully invoiced order', ['order_id' => $order->id]);
        }

        return true;
    }
}
