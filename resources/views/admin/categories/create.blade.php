<x-dashboard title="categories">
    <h1>Create Category</h1>
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <x-form-input type="text" name="name" label="Name"/>
        <x-form-input type="text" name="slug" label="Slug"/>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</x-dashboard>
