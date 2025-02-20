<x-dashboard title="categories">
    <div class="text-center mb-4">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Create New Category</a>
    </div>
    @if($categories->isEmpty())
        <div class="alert alert-info">No categories found.</div>
    @else
        <table class="table table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->slug }}</td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $category->id) }}"
                           class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                              style="display:none;" id="delete-form-{{ $category->id }}">
                            @csrf
                            @method('DELETE')
                        </form>
                        <button class="btn btn-sm btn-danger"
                                onclick="confirmDelete({{ $category->id }})">
                            Delete
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $categories->links() }}
    @endif
</x-dashboard>
