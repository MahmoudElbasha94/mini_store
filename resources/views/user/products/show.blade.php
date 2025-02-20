<x-layout.app title="{{$product->name . ' - Product Details'}}">
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="flash-message">
            {{ session('error') }}
        </div>
    @endif

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

                <!-- Average Rating -->
                <p class="text-warning fs-5">
                    ⭐ {{ number_format($averageRating, 1) }} / 5
                    <small class="text-muted">({{ $product->reviews->count() }} Reviews)</small>
                </p>

                @if($product->discount > 0)
                    <p class="mb-1">
                        <span class="text-muted"><s>${{ number_format($product->price, 2) }}</s></span>
                        <span class="text-danger fw-bold ms-2">${{ number_format($product->price - $product->price * $product->discount / 100, 2) }}</span>
                    </p>
                @else
                    <p class="text-dark fw-bold mb-1">${{ number_format($product->price, 2) }}</p>
                @endif
{{--                <p class="text-muted fs-4">{{ number_format($product->price, 2) }} EGP</p>--}}
                <p class="lead">{{ $product->description }}</p>

                <!-- Add to Cart Button -->
                <div class="mt-4 d-flex justify-content-evenly">
                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-warning btn-lg">
                            <i class="fas fa-cart-plus"></i> Add to Cart
                        </button>
                    </form>
                    <form action="{{ route('wishlist.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="btn btn-outline-danger btn-lg">
                            <i class="fas fa-heart"></i> Add to Wishlist
                        </button>
                    </form>
                </div>

                <!-- Additional Product Information -->
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

        <!-- Reviews Section -->
        <div class="mt-5">
            <h3>Customer Reviews</h3>

            @foreach ($product->reviews as $review)
                <div class="border p-3 my-2">
                    <strong>{{ $review->user->name }}</strong>
                    <span class="text-warning">⭐ {{ $review->rating }}/5</span>
                    <p class="mb-0">{{ $review->comment }}</p>
                    <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                </div>
            @endforeach

            <!-- Add Review Form -->
            @auth
                @if ($existingReview)
                    <div class="alert alert-info">
                        <h4>Your Review</h4>
                        <p><strong>Rating:</strong> ⭐ {{ $existingReview->rating }}/5</p>
                        <p><strong>Comment:</strong> {{ $existingReview->comment ?? 'No comment' }}</p>
                    </div>
                @else
                    <h4 class="mt-4">Leave a Review</h4>
                    <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating</label>
                            <select name="rating" class="form-control @error('rating') is-invalid @enderror" required>
                                <option value="5">⭐ ⭐ ⭐ ⭐ ⭐ (5)</option>
                                <option value="4">⭐ ⭐ ⭐ ⭐ (4)</option>
                                <option value="3">⭐ ⭐ ⭐ (3)</option>
                                <option value="2">⭐ ⭐ (2)</option>
                                <option value="1">⭐ (1)</option>
                            </select>
                            @error('rating')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="comment" class="form-label">Your Review</label>
                            <textarea name="comment" class="form-control @error('comment') is-invalid @enderror" rows="3"></textarea>
                            @error('comment')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </form>
                @endif
            @endauth
        </div>
    </div>
</x-layout.app>
