<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;


class CheckoutController extends Controller
{
    public function index()
    {
        // Retrieve cart data from cookies
        $cart = json_decode(Cookie::get('cart'), true) ?? [];

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Calculate total price
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Create an order
        $order = Order::create([
            'user_id' => auth()->id(),
            'total' => $total,
            'status' => 'pending',
            'shipping_address' => 'Default Address', // You can update this later
            'billing_address' => 'Default Billing Address',
            'payment_method' => 'paymob',
            'payment_status' => 'pending',
        ]);

        // Save order items
        foreach ($cart as $id => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // Clear the cart by setting an empty cookie
        Cookie::queue(Cookie::forget('cart'));

        // Redirect to payment initiation
        return redirect()->route('payment.initiate', ['orderId' => $order->id]);
    }

    public function showOrderInfoForm() {
        return view('user.payment.checkout');
    }
}
