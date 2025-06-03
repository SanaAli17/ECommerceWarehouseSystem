<x-app-layout>
    <div class="mb-3 d-flex justify-content-between">
        <h4>All Products</h4>
        <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">Add Product</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Price (Pkr)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ number_format($product->price, 2) }}</td>
                <td>
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Delete product?')" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="3">No products found.</td></tr>
        @endforelse
        </tbody>
    </table>
</x-app-layout>