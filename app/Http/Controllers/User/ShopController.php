<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function index() {
        $products = Product::paginate(10);
        return view('user.products.index', compact('products'));
    }

    public function show($productName, $categorySlug) {
        // Find the category by slug
        $category = Category::where('slug', $categorySlug)->firstOrFail();

        // Find the product by name and category ID
        $product = Product::where('name', $productName)
            ->whereHas('categories', function ($query) use ($category) {
                $query->where('categories.id', $category->id);
            })
            ->firstOrFail();

        //average rating
        $averageRating = $product->reviews->avg('rating');

        $existingReview = null;
        if (Auth::check()) {
            $existingReview = $product->reviews()->where('user_id', Auth::id())->first();
        }
        return view('user.products.show', compact('product', 'existingReview', 'averageRating'));
    }
}
