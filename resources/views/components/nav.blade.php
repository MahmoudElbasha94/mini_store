<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Mini Store</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto d-flex align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-home"></i>Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.products.index') }}"><i class="fas fa-store"></i>Shop</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-shopping-cart"></i>Cart</a>
                </li>
                @if(Auth::check())
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
