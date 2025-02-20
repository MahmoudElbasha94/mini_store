<x-dashboard title="products">
    <div class="text-center mb-4">
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Create New Product</a>
    </div>
    @if($products->isEmpty())
        <div class="alert alert-info mt-3">
            No products found. Please create a new product.
        </div>
    @else
        <table class="table table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Discount</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->discount }}</td>
                    <td>
                        <a href="{{ route('admin.products.edit', $product->id) }}"
                           class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                              style="display:none;" id="delete-form-{{ $product->id }}">
                            @csrf
                            @method('DELETE')
                        </form>
                        <button type="submit" class="btn btn-sm btn-danger"
                                onclick="confirmDelete({{ $product->id }})">
                            Delete
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $products->links() }} <!-- Pagination -->
    @endif
</x-dashboard>
<?php
