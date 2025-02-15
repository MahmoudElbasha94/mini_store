<x-dashboard title="products">
    <h1>Update Product</h1>
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <x-edit-form-input type="text" name="name" label="Name" value="{{ $product->name }}"/>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" name="description" id="description">{{ $product->description }}</textarea>
        </div>
        @error('description')
        <div class="text-danger">{{ $message }}</div>
        @enderror
        <x-edit-form-input type="number" name="price" label="price" value="{{ $product->price }}"/>
        <x-edit-form-input type="number" name="stock" label="stock" value="{{ $product->stock }}"/>
        <x-form-input type="file" name="image" label="image"/>
        @if($product->image)
            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-fluid mt-2" style="max-width: 200px;">
        @endif
        <x-edit-form-input type="number" name="discount" label="discount (%)" value="{{ $product->discount }}"/>
        <div class="form-group mb-3">
            <label for="categories">Categories</label>
            <select name="categories[]" id="categories" class="form-control" multiple>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ in_array($category->id, $product->categories->pluck('id')->toArray()) ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group mb-3">
            <label for="default_category">Default Category</label>
            <select name="default_category" id="default_category" class="form-control" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->categories->where('pivot.is_default', true)->first()->id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</x-dashboard>
