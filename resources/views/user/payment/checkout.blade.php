<x-layout.app>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h2 class="mb-4 text-center">Complete your order</h2>

                <div class="card shadow">
                    <div class="card-body">
                        <form action="#" method="POST">
                            @csrf

                            <!-- رقم الهاتف -->
                            <x-form-input name="phone" label="Phone Number" type="text"/>

                            <!-- عنوان الشحن -->
                            <x-form-input name="shipping_address" label="Shipping Address" type="text"/>

                            <!-- عنوان الفواتير -->
                            <x-form-input name="billing_address" label="Billing Address" type="text"/>

                            <!-- طريقة الدفع -->
                            <div class="mb-3">
                                <x-form-input label="" name="visa" type="hidden" readonly />
                            </div>

                            <button type="submit" class="btn btn-primary w-100">complete order</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.getElementById('same_as_shipping').addEventListener('change', function () {
            if (this.checked) {
                document.getElementById('billing_address').value = document.getElementById('shipping_address').value;
            } else {
                document.getElementById('billing_address').value = '';
            }
        });
    </script>
</x-layout.app>
