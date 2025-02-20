<x-dashboard title="categories">
    <h1>Edit Category</h1>
    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')
        <x-edit-form-input type="text" name="name" label="Name" value="{{ $category->name }}"/>
        <x-edit-form-input type="text" name="slug" label="Slug" value="{{ $category->slug }}"/>
        <button class="btn btn-primary" type="submit">Update</button>
    </form>
</x-dashboard>
