<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'discount' => 'nullable|numeric|min:0|max:100',
            'categories' => 'required|array', // Ensure at least one category is selected
            'categories.*' => 'exists:categories,id', // Ensure the category IDs are valid
            'default_category' => 'required|exists:categories,id'
        ]);


        // Handle image upload
        $productData = $request->except('categories');
        if ($request->hasFile('image')) {
            // Generate a unique filename
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();

            // Store the image in the storage/app/public/product_images directory
            try {
                $imagePath = $request->image->storeAs('product_images', $imageName, 'public');
                $productData['image'] = 'storage/' . $imagePath; // Save the full path
            } catch (\Exception) {
                return redirect()->back()->with('error', 'Failed to upload image.');
            }
        } else {
            // If no image is uploaded, set image to null or a default value
            $productData['image'] = null;
        }

        // Create the product
        $product = Product::create($productData);

        // Attach categories to the product with the default category marked
        $categories = [];
        foreach ($request->input('categories') as $categoryId) {
            $categories[$categoryId] = ['is_default' => ($categoryId == $request->input('default_category'))];
        }
        $product->categories()->attach($categories);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'discount' => 'nullable|numeric|min:0|max:100',
            'categories' => 'required|array', // Ensure at least one category is selected
            'categories.*' => 'exists:categories,id', // Ensure the category IDs are valid
            'default_category' => 'required|exists:categories,id'

        ]);

        // Handle image upload
        $productData = $request->except('categories');
        if ($request->hasFile('image')) {
            // Generate a unique filename
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();

            // Delete old image if it exists
            if ($product->image) {
                Storage::disk('public')->delete(str_replace('storage/', '', $product->image));
            }

            // Store the new image
            try {
                $imagePath = $request->image->storeAs('product_images', $imageName, 'public');
                $productData['image'] = 'storage/' . $imagePath; // Save the full path
            } catch (\Exception) {
                return redirect()->back()->with('error', 'Failed to upload image.');
            }
        } else {
            // Keep the old image
            $productData['image'] = $product->image;
        }

        // Update the product
        $product->update($productData);

        // Sync categories with the default category marked
        $categories = [];
        foreach ($request->input('categories') as $categoryId) {
            $categories[$categoryId] = ['is_default' => ($categoryId == $request->input('default_category'))];
        }
        $product->categories()->sync($categories);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            // Delete the image if it exists
            if ($product->image && Storage::disk('public')->exists(str_replace('storage/', '', $product->image))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $product->image));
            }

            $product->delete();

            return redirect()->route('admin.products.index')
                ->with('success', 'Product deleted successfully.');
        } catch (\Exception) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Error deleting product.');
        }
    }
}
