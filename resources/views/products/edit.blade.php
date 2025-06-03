<x-app-layout>
    <h4>Edit Product</h4>

    <form action="{{ route('products.update', $product) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ $product->name }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Price (Pkr)</label>
            <input type="number" name="price" value="{{ $product->price }}" class="form-control" step="0.01" required>
        </div>

        <button class="btn btn-success">Update</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>
    </form>
</x-app-layout>