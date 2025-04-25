<div class="container mt-4">
    <h2>Add New Product</h2>
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price (USD)</label>
            <input type="number" id="price" name="price" class="form-control" step="0.01" value="{{ old('price') }}" required>
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select id="category_id" name="category_id" class="form-select" required>
                <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>-- Select Category --</option>
                @foreach($categories as $category)
                <option
                    value="{{ $category->id }}"
                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Product Image (optional)</label>
            <input type="file" id="image" name="image" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary mb-4">Add Product</button>
    </form>


    

    @if($products->isNotEmpty())
    <h2>Existing Products</h2>
    <input type="text" id="productSearch" class="form-control px-2 mb-3" placeholder="Search by name or category">

    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Category</th>
                <th>Description</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $prod)
            <tr>
                <td>{{ $prod->name }}</td>
                <td>${{ number_format($prod->price, 2) }}</td>
                <td>{{ $prod->category->name }}</td>
                <td>{{ Str::limit($prod->description, 50) }}</td>
                <td>
                    @if($prod->image)
                    <img src="{{ asset('storage/' . $prod->image) }}" alt="" style="height:40px;object-fit:cover;">
                    @else
                    &mdash;
                    @endif
                </td>
                <td>
                    <div class="d-flex gap-1">
                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $prod->id }}">
                            Edit
                        </button>
                        <form action="{{ route('products.destroy', $prod) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>

            <div class="modal fade" id="editProductModal{{ $prod->id }}" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('products.update', $prod) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="name" class="form-label">Product Name</label>
                                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $prod->name) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="price" class="form-label">Price (USD)</label>
                                    <input type="number" id="price" name="price" class="form-control" step="0.01" value="{{ old('price', $prod->price) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category</label>
                                    <select id="category_id" name="category_id" class="form-select" required>
                                        <option value="" disabled>-- Select Category --</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $prod->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea id="description" name="description" class="form-control" rows="3">{{ old('description', $prod->description) }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="image" class="form-label">Product Image (optional)</label>
                                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                                    @if($prod->image)
                                    <p>Current image: <img src="{{ asset('storage/' . $prod->image) }}" style="height:40px;object-fit:cover;"></p>
                                    @endif
                                </div>

                                <button type="submit" class="btn btn-primary">Update Product</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-3">
        {{ $products->appends(request()->query())->links() }}
    </div>
    @else
    <p class="text-muted">No products have been added yet.</p>
    @endif
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('#productSearch').on('keyup', function() {
            var query = $(this).val().toLowerCase(); 
            $('#productsTable tbody tr').each(function() {
                var productName = $(this).find('.product-name').text().toLowerCase();
                var productCategory = $(this).find('.product-category').text().toLowerCase();
                if (productName.indexOf(query) > -1 || productCategory.indexOf(query) > -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
</script>