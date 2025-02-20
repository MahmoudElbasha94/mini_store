<x-dashboard title="products">
    <h1>Create Category</h1>
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <x-form-input type="text" name="name" label="Name"/>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" name="description" id="description">{{ old('description') }}</textarea>
        </div>
        @error('description')
        <div class="text-danger">{{ $message }}</div>
        @enderror
        <x-form-input type="number" name="price" label="price"/>
        <x-form-input type="number" name="stock" label="stock"/>
        <x-form-input type="file" name="image" label="image"/>
        <x-form-input type="number" name="discount" label="discount (%)"/>
        <div class="form-group mb-3">
            <label for="categories">Categories</label>
            <select name="categories[]" id="categories" class="form-control" multiple>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->slug }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group mb-3">
            <label for="default_category">Default Category</label>
            <select name="default_category" id="default_category" class="form-control" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</x-dashboard>
