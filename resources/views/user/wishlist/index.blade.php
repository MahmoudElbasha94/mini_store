<x-layout.app>
    <div class="container py-5">
        <h1 class="mb-4">Your Wishlist</h1>

        @if (session('success'))
            <div class="alert alert-success" id="flash-message">{{ session('success') }}</div>
        @endif

        @if ($wishlist->isEmpty())
            <div class="alert alert-info">
                Your wishlist is empty. <a href="{{ route('user.products.index') }}" class="alert-link">Browse products</a>.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>image</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($wishlist as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>${{ number_format($item->product->price, 2) }}</td>
                            <td>
                                <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" width="100" height="100">
                            </td>
                            <td>
                                <form action="{{ route('wishlist.remove', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-layout.app>
