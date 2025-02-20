<div class="col-md-4 mb-4">
    <div class="card shadow-sm border-0 rounded-3 overflow-hidden h-100">
        <div class="position-relative">
            <img src="{{ $productImage }}" class="card-img-top fixed-image" alt="Product">
            @if($productDiscount > 0)
                <span class="badge bg-danger position-absolute top-0 start-0 m-2 px-3 py-1">
                    {{ $productDiscount }}% OFF
                </span>
            @endif
        </div>
        <div class="card-body d-flex flex-column justify-content-between text-center">
            <h5 class="card-title text-truncate" title="{{ $productName }}">{{ $productName }}</h5>

            @if($productDiscount > 0)
                <p class="mb-1">
                    <span class="text-muted"><s>${{ number_format($productOriginalPrice, 2) }}</s></span>
                    <span class="text-danger fw-bold ms-2">${{ number_format($productPrice, 2) }}</span>
                </p>
            @else
                <p class="text-dark fw-bold mb-1">${{ number_format($productPrice, 2) }}</p>
            @endif

            <!-- Star Rating -->
            <div class="my-2">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= floor($averageRating))
                        <i class="fas fa-star text-warning"></i>
                    @elseif ($i == ceil($averageRating) && $averageRating - floor($averageRating) >= 0.5)
                        <i class="fas fa-star-half-alt text-warning"></i>
                    @else
                        <i class="fas fa-star text-muted"></i>
                    @endif
                @endfor
            </div>

            <a href="{{ $productLink }}" class="btn btn-primary w-100 mt-3">
                <i class="fas fa-shopping-cart"></i> View Product
            </a>
        </div>
    </div>
</div>
