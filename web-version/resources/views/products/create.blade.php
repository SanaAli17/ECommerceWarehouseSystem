<x-app-layout>
    <h4>Add Product</h4>

    <form action="{{ route('products.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Price (Pkr)</label>
            <input type="number" name="price" class="form-control" step="0.01" required>
        </div>

        <button class="btn btn-primary">Save</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>
    </form>
</x-app-layout>