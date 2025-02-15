<x-layout.app>
    <div class="container py-5" id="cart-section">
        <h1 class="mb-4">Your Shopping Cart</h1>
        @if (count($cart) > 0)
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($cart as $id => $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>${{ number_format($item['price'], 2) }}</td>
                            <td class="d-flex justify-content-evenly">
                                <button type="button" class="btn btn-sm btn-outline-secondary decrease-quantity"
                                        data-product-id="{{ $id }}">-
                                </button>
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1"
                                       class="form-control form-control-sm" style="width: 50px;" readonly>
                                <button type="button" class="btn btn-sm btn-outline-secondary increase-quantity"
                                        data-product-id="{{ $id }}">+
                                </button>
                            </td>
                            <td>${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                            <td>
                                <form action="{{ route('cart.remove', $id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-end">
                <h4>Total: {{ number_format($total, 2) }} EGP</h4>
                <a href="#" class="btn btn-primary btn-lg mt-4">Proceed to Checkout</a>
            </div>
        @else
            <div class="alert alert-info">
                Your cart is empty. <a href="{{ route('user.products.index') }}" class="alert-link">Continue
                    shopping</a>.
            </div>
        @endif
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Function to update quantity
            function updateQuantity(productId, change) {
                fetch(`/cart/update/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ change: change })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Reload the page to reflect changes
                            location.reload();
                        }
                    });
            }

            // Function to update the cart count
            function updateCartCount() {
                fetch("{{ route('cart.count') }}")
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById("cart-count").textContent = data.count;
                    });
            }

            // Attach event listeners to all quantity buttons
            document.querySelectorAll('.increase-quantity, .decrease-quantity').forEach(button => {
                button.addEventListener('click', function () {
                    const productId = this.getAttribute('data-product-id');
                    const change = this.classList.contains('increase-quantity') ? 1 : -1;
                    updateQuantity(productId, change);
                });
            });

            // Update the cart count when the page loads
            updateCartCount();
        });
    </script>
</x-layout.app>
