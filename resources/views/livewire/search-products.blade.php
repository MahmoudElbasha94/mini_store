<div>
    <div class="container">
        <input type="text" class="form-control" placeholder="Search products..." wire:model.lazy="search">
    </div>
    @if($products->isEmpty())
        <div class="alert alert-info">No Products found.</div>
    @else
        <div class="container py-2">
            <h2 class="text-center mt-4">Available Products</h2>
            <div class="row">
                @foreach ($products as $product)
                    <x-product-card
                        productImage="{{ asset($product->image) }}"
                        productName="{{ $product->name }}"
                        productPrice="{{ $product->discount > 0 ? $product->price - ($product->price * ($product->discount / 100)) : $product->price }}"
                        productOriginalPrice="{{ $product->discount > 0 ? $product->price : null }}"
                        productDiscount="{{ $product->discount }}"
                        averageRating="{{ (float)$product->reviews->avg('rating') }}"
                        productLink="{{ route('user.products.show', ['product' => $product->name, 'category' => $product->categories->where('pivot.is_default', true)->first()->slug]) }}"/>
                @endforeach
            </div>
        </div>
    @endif
</div>
