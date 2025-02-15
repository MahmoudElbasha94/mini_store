<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Mini Store' }}</title>
    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="{{ asset('assets/css/product.css') }}" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/favicon/ministore.ico') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        #heroCarousel .carousel-item img {
            height: 500px;
            object-fit: cover;
        }
    </style>
</head>
<body>
<!-- Header -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Mini Store</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto d-flex align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}"><i class="fas fa-home"></i>Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.products.index') }}"><i class="fas fa-store"></i>Shop</a>
                </li>
                @if(Auth::check())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cart.index') }}"><i class="fas fa-shopping-cart"></i>
                            Cart
                            <span id="cart-count" class="badge bg-danger">
                                {{ session('cart') ? array_sum(array_column(session('cart'), 'quantity')) : 0 }}
                            </span>
                        </a>
                    </li>

                    <li class="nav-item ms-5">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger">Logout</button>
                        </form>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-user"></i>Profile</a>
                    </li>
                    <li class="nav-item ms-5">
                        Welcome, {{ Auth::user()->name }}! 👋
                    </li>
                @else
                    <li class="nav-item ms-4">
                        <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}"><i class="fas fa-user-plus"></i> Register</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>


<!-- slider Section -->
{{--<x-carousel.slider></x-carousel.slider>--}}

<!-- Categories -->
{{--<x-containers.categories>--}}
{{--    @foreach($categories as $category)--}}
{{--        <div class="col-md-3"><div class="p-3 border rounded mb-2">{{ $category->name }}</div></div>--}}
{{--    @endforeach--}}
{{--</x-containers.categories>--}}

<!-- Featured Products -->
{{--<x-cards.product-card></x-cards.product-card>--}}

<!-- Main Content -->
<main class="py-4">
    {{ $slot }}
</main>

<!-- Footer -->
<x-footer></x-footer>
<script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
