<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CartController extends Controller
{
    public function index(Request $request)
    {
//        $cart = session()->get("cart", []);
        $cart = json_decode(Cookie::get('cart', '[]'), true);
        $total = $this->calculateTotal($cart);
        return view('user.cart.index', compact('cart', 'total'));
    }

    public function addToCart($productId)
    {
        $product = Product::findOrFail($productId);

        $cart = json_decode(Cookie::get('cart', '[]'), true);

        // Get current quantity in cart (if product exists)
        $currentQuantity = isset($cart[$productId]) ? $cart[$productId]['quantity'] : 0;

        // Check if adding another one exceeds stock availability
        if ($currentQuantity + 1 > $product->stock) {
            return redirect()->back()->with('error', 'Sorry, you exceeded the product stock');
        }

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            $cart[$productId] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
            ];
        }

//        session()->put("cart", $cart);
        Cookie::queue('cart', json_encode($cart), 60 * 24 * 7);
        return redirect()->back()->with('success', 'added to cart successfully!');
    }

    public function updateQuantity(Request $request, $productId) {
        // Retrieve the change value from the request body
        $change = $request->input('change');

        // Get the current cart from the session
//        $cart = session()->get("cart", []);

        $cart = json_decode(Cookie::get('cart', '[]'), true);

        // Check if the product exists in the cart
        if (isset($cart[$productId])) {
            // Update the quantity
            $cart[$productId]['quantity'] += $change;

            // Ensure the quantity doesn't go below 1
            if ($cart[$productId]['quantity'] < 1) {
                $cart[$productId]['quantity'] = 1;
            }

            // Save the updated cart back to the cookie
            Cookie::queue('cart', json_encode($cart), 60 * 24 * 7);
        }

        return response()->json(['success' => true]);
    }

    public function removeFromCart(Request $request, $productId)
    {

        $cart = json_decode(Cookie::get('cart', '[]'), true);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Cookie::queue('cart', json_encode($cart), 60 * 24 * 7);
        }

        return redirect()->back()->with('success', 'Product removed from cart!');
    }

    public function getCartCount(Request $request)
    {

//        $cart = session()->get("cart", []);
        $cart = json_decode(Cookie::get('cart', '[]'), true);
        $count = array_sum(array_column($cart, 'quantity'));

        return response()->json(['count' => $count]);
    }

    private function calculateTotal($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}
