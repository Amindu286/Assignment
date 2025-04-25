<div class="container mt-4">
    <h2>Add New Category</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('categories.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Category Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Add Category</button>
    </form>
    @if($categories->isNotEmpty())
    <h3>Existing Categories</h3>
    <ul class="list-group">
        @foreach($categories as $category)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            {{ $category->name }}

            {{-- Delete Button --}}
            <form
                action="{{ route('categories.destroy', $category) }}"
                method="POST"
                onsubmit="return confirm('Are you sure you want to delete this category?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">
                    Delete
                </button>
            </form>
        </li>
        @endforeach
    </ul>
    @else
    <p class="text-muted">No categories yet.</p>
    @endif
</div>