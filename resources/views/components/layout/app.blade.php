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
    <style>
        #heroCarousel .carousel-item img {
            height: 500px;
            object-fit: cover;
        }
    </style>
</head>
<body>
@if($globalCoupon)
    <div class="bg-warning text-center py-2">
        🎉 Use Coupon <strong>{{ $globalCoupon->code }}</strong> for
        <strong>{{ $globalCoupon->discount }}% OFF</strong>!
        <a href="{{ route('user.products.index') }}" class="fw-bold text-dark">Apply Now</a>
    </div>
@endif
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-white" href="#">Mini Store</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto d-flex align-items-center">
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('home') }}" aria-label="Home">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('user.products.index') }}" aria-label="Shop">
                        <i class="fas fa-store"></i> Shop
                    </a>
                </li>
                @if(Auth::check())
                    <li class="nav-item">
                        <a class="nav-link text-white position-relative" href="{{ route('cart.index') }}" aria-label="Cart">
                            <i class="fas fa-shopping-cart"></i> Cart
                            <span id="cart-count" class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill">
                                {{ collect(json_decode(Cookie::get('cart', '[]'), true))->sum('quantity') }}
                            </span>
                        </a>
                    </li>

                    <li class="nav-item ms-4">
                        <a class="nav-link text-white" href="{{ route('wishlist.index') }}" aria-label="Wishlist">
                            <i class="fas fa-heart"></i> Wishlist
                            <span class="badge bg-danger">
                                {{ DB::table('wishlist')->where('user_id', Auth::id())->count() }}
                            </span>
                        </a>
                    </li>

                    <li class="nav-item ms-4">
                        <a class="nav-link text-white" href="{{ route('profile.edit') }}" aria-label="Profile">
                            <i class="fas fa-user"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item ms-4">
                        <span class="text-white">Welcome, {{ Auth::user()->name }}! 👋</span>
                    </li>
                    <li class="nav-item ms-4">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger">Logout</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item ms-4">
                        <a class="nav-link text-white" href="{{ route('login') }}" aria-label="Login">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                    <li class="nav-item ms-4">
                        <a class="nav-link text-white" href="{{ route('register') }}" aria-label="Register">
                            <i class="fas fa-user-plus"></i> Register
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

@if (session('error'))
    <div class="alert alert-danger" id="flash-message">{{ session('error') }}</div>
@endif

@if (session('success'))
    <div class="alert alert-success" id="flash-message">{{ session('success') }}</div>
@endif

@if (session('warning'))
    <div class="alert alert-warning" id="flash-message">{{ session('warning') }}</div>
@endif

<!-- Main Content -->
<main class="py-4">
    {{ $slot }}
</main>

<!-- Footer -->
{{--<x-footer></x-footer>--}}
<script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(function() {
            let flashMessage = document.getElementById("flash-message");
            if (flashMessage) {
                flashMessage.style.transition = "opacity 0.5s";
                flashMessage.style.opacity = "0";
                setTimeout(() => flashMessage.remove(), 500);
            }
        }, 2000);
    });
</script>
</body>
</html>
