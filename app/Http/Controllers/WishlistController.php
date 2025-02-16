<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // عرض قائمة الأمنيات
    public function index()
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->with('product')->get();
//        dd($wishlist);
        return view('user.wishlist.index', compact('wishlist'));
    }

    // إضافة منتج إلى الـ Wishlist
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        Wishlist::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
        ]);

        return back()->with('success', 'Product added to wishlist.');
    }

    // حذف منتج من الـ Wishlist
    public function destroy($id)
    {
        Wishlist::where('user_id', Auth::id())->where('id', $id)->delete();
        return back()->with('success', 'Product removed from wishlist.');
    }
}
