<x-layout.app title="{{$product->name . ' - product details'}}">
    <div class="container py-5">
        <!-- Back to Products Link -->
        <a href="{{ route('user.products.index') }}" class="btn btn-outline-secondary mb-4">
            &larr; Back to Products
        </a>

        <div class="row d-flex justify-content-evenly">
            <!-- Product Image -->
            <div class="col-md-6">
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                     class="img-fluid rounded shadow product-image">
            </div>

            <!-- Product Details -->
            <div class="col-md-6">
                <h1 class="mb-3">{{ $product->name }}</h1>
                <p class="text-muted fs-4">{{ number_format($product->price, 2) }} EGP</p>
                <p class="lead">{{ $product->description }}</p>

                <!-- Add to Cart Button -->
                <div class="mt-4">
                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-warning btn-lg">
                            <i class="fas fa-cart-plus"></i> Add to Cart
                        </button>
                    </form>
                </div>

                <!-- Additional Product Information (Optional) -->
                <div class="mt-5">
                    <h4>Product Details</h4>
                    <ul class="list-unstyled">
                        <li><strong>Categories:</strong>
                            @foreach($product->categories as $category)
                                <span class="badge bg-secondary">{{ $category->name }}</span>
                            @endforeach
                        </li>

                        <li>
                            <strong>Stock:</strong>
                            @if($product->stock<=5)
                                <span class="text-danger">limited only {{ $product->stock }}</span>
                            @else
                                <span class="text-success">{{ $product->stock }}</span>
                            @endif
                            available
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-layout.app>
