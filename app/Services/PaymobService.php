<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PaymobService
{
    private $authToken;

    public function __construct()
    {
        $this->authToken = $this->getAuthToken();
    }

    // Step 1: Get Authentication Token
    public function getAuthToken()
    {
        $response = Http::post(config('services.paymob.auth_url'), [
            'api_key' => config('services.paymob.api_key')
        ]);

        return $response->json('token');
    }

    // Step 2: Create Order
    public function createOrder($order)
    {
        $response = Http::post(config('services.paymob.order_url'), [
            'auth_token' => $this->authToken,
            'delivery_needed' => false,
            'amount_cents' => $order->total * 100, // Convert to cents
            'currency' => 'EGP',
            'merchant_order_id' => $order->id,
            'items' => $order->items->map(function ($item) {
                return [
                    "name" => $item->product->name,
                    "amount_cents" => $item->price * 100,
                    "quantity" => $item->quantity
                ];
            })->toArray()
        ]);

        return $response->json('id');
    }

    // Step 3: Get Payment Key
    public function getPaymentKey($orderId, $amount, $billingData)
    {
        $response = Http::post(config('services.paymob.payment_url'), [
            'auth_token' => $this->authToken,
            'amount_cents' => $amount * 100,
            'expiration' => 3600, // 1 hour
            'order_id' => $orderId,
            'billing_data' => $billingData,
            'currency' => 'EGP',
            'integration_id' => config('services.paymob.integration_id')
        ]);

        return $response->json('token');
    }
}
