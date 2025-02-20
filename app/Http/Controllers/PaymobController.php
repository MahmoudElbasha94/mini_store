<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\PaymobService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymobController extends Controller
{
    public function initiatePayment($orderId)
    {
        $order = Order::findOrFail($orderId);

        // Step 1: Get Paymob Authentication Token
        $authResponse = Http::post('https://accept.paymob.com/api/auth/tokens', [
            'api_key' => env('PAYMOB_API_KEY'),
        ]);
        $authToken = $authResponse->json()['token'];

        // Step 2: Register Order on Paymob
        $orderResponse = Http::post('https://accept.paymob.com/api/ecommerce/orders', [
            'auth_token' => $authToken,
            'delivery_needed' => 'false',
            'amount_cents' => $order->total * 100, // Convert to cents
            'currency' => 'EGP',
            'merchant_order_id' => $order->id,
        ]);
        $paymobOrderId = $orderResponse->json()['id'];

        // Step 3: Get Payment Key
        $paymentKeyResponse = Http::post('https://accept.paymob.com/api/acceptance/payment_keys', [
            'auth_token' => $authToken,
            'amount_cents' => $order->total * 100,
            'currency' => 'EGP',
            'order_id' => $paymobOrderId,
            'billing_data' => [
                "first_name" => "Test",
                "last_name" => "User",
                "email" => "test@example.com",
                "phone_number" => "0123456789",
                "apartment" => "803",
                "floor" => "42",
                "street" => "Test Street",
                "building" => "23",
                "city" => "Cairo",
                "country" => "EG",
            ],
            'integration_id' => env('PAYMOB_INTEGRATION_ID'),
            'return_url' => route('payment.callback')
        ]);
        $paymentKey = $paymentKeyResponse->json()['token'];

        // Step 4: Redirect to Paymob Payment Page
        return redirect("https://accept.paymob.com/api/acceptance/iframes/".env('PAYMOB_IFRAME_ID')."?payment_token=".$paymentKey);
    }


    public function paymentCallback(Request $request)
    {
        $transactionId = $request->input('id');
        $orderId = $request->input('order');

        // Find the order
        $order = Order::findOrFail($orderId);

        // Check if payment was successful
        if ($request->input('success') == "true") {
            $order->update(['payment_status' => 'paid', 'status' => 'shipped']);
            Payment::create([
                'order_id' => $order->id,
                'transaction_id' => $transactionId,
                'amount' => $order->total,
                'status' => 'completed',
            ]);

            // ✅ تحديث المخزون لكل منتج في الطلب
            foreach ($order->items as $item) {
                $product = $item->product;
                if ($product->stock >= $item->quantity) {
                    $product->decrement('stock', $item->quantity);
                }
            }

            return redirect()->route('cart.index')->with('success', 'Payment successful!');
        } else {
            $order->update(['payment_status' => 'failed']);
            return redirect()->route('cart.index')->with('error', 'Payment failed. Try again.');
        }
    }
}
