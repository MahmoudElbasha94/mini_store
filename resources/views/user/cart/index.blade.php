<x-layout.app>
    <div aria-live="polite" aria-atomic="true" class="position-relative">
        <div class="toast-container top-0 end-0 p-3">
            <!-- Then put toasts within -->
            <div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body text-bg-warning fw-bold" id="toast-message">

                </div>
            </div>
        </div>
    </div>
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

            <div class="mb-4">
                <form action="{{ route('apply.coupon') }}" method="POST" class="d-flex gap-2 mt-3">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="coupon_code" placeholder="Enter Coupon Code" class="form-control flex-grow-1"/>
                        <button type="submit" class="btn btn-primary">Apply Coupon</button>
                    </div>
                </form>

            </div>
            <div class="text-end">
                @if($discount > 0)
                    <h5>Discount: -{{ number_format($discountAmount, 2) }} EGP ({{ $discount }}%)</h5>
                @endif
                <h4>Total: <span class="fw-bold text-primary">{{ number_format($totalAfterDiscount, 2) }}</span> EGP
                </h4>
                <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg mt-4">Proceed to Checkout</a>
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
                    body: JSON.stringify({change: change})
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Reload the page to reflect changes
                            location.reload();
                        } else {
                            showToast(data.message);
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

            // Function to show the toast notification using native Bootstrap JS
            function showToast(message) {
                const toastMessage = document.getElementById('toast-message');
                const toastElement = document.getElementById('toast');

                // Set the message content
                toastMessage.textContent = message;

                // Create a new Toast instance and show it
                const toast = new bootstrap.Toast(toastElement);
                toast.show(); // Show the toast
            }

            // Update the cart count when the page loads
            updateCartCount();
        });
    </script>
</x-layout.app>
