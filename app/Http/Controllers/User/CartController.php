<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index(Request $request)
    {
        // جلب السلة من الكوكيز
        $cart = json_decode(Cookie::get('cart', '[]'), true);

        // حساب الإجمالي
        $total = $this->calculateTotal($cart);

        // جلب الخصم من الكوكيز (لو موجود)
        $discount = session('discount', 0);
        $discountAmount = ($total * $discount) / 100;
        $totalAfterDiscount = $total - $discountAmount;

        // إرجاع البيانات للـ View
        return view('user.cart.index', compact('cart', 'total', 'discount', 'discountAmount', 'totalAfterDiscount'));
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

        Cookie::queue('cart', json_encode($cart), 60 * 24 * 7);
        return redirect()->back()->with('success', 'added to cart successfully!');
    }

    public function updateQuantity(Request $request, $productId) {
        // Retrieve the change value from the request body
        $change = $request->input('change');

        // Get the current cart from the cookie
        $cart = json_decode(Cookie::get('cart', '[]'), true);

        // Retrieve the product details
        $product = Product::find($productId);

        // Check if the product exists in the cart
        if (isset($cart[$productId])) {

            // Get the current stock of the product
            $stock = $product->stock;

            // Calculate the new quantity
            $newQuantity = $cart[$productId]['quantity'] + $change;

            // Ensure the new quantity doesn't exceed the available stock
            if ($newQuantity > $stock) {
                return response()->json(['success' => false, 'message' => 'Not enough stock available']);
            }

            // Update the quantity
            $cart[$productId]['quantity'] = $newQuantity;

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

            // If the cart is now empty, remove the discount session
            if (empty($cart)) {
                session()->forget(['discount']);
            }
        }

        return redirect()->back()->with('success', 'Product removed from cart!');
    }

    public function getCartCount(Request $request)
    {

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


    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|exists:coupons,code',
        ]);

        $coupon = Coupon::where('code', $request->coupon_code)->first();

        // Check if a coupon is already applied
        if (session()->has('discount')) {
            return redirect()->back()->with('error', 'A coupon is already applied!😏');
        }


        // Check if the coupon is still valid
        if ($coupon->valid_from && Carbon::now()->lt($coupon->valid_from)) {
            return back()->with('error', 'This coupon is not yet active.');
        }

        if ($coupon->valid_until && Carbon::now()->gt($coupon->valid_until)) {
            return back()->with('error', 'This coupon has expired.');
        }

        // Store coupon in session to apply it to the order later
        Session::put(['discount' => $coupon->discount]);

        return back()->with('success', 'Coupon applied successfully! 🎉');
    }
}
