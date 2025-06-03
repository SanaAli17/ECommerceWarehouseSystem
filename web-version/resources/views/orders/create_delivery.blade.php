<x-app-layout>
    <div class="container mt-4">
        <h4>Place Delivery Order</h4>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('orders.storeDelivery') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="customer_name" class="form-label">Customer Name</label>
                <input type="text" name="customer_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="product_id" class="form-label">Select Product</label>
                <select name="product_id" class="form-select" required>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} - PKR {{ $product->price }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control" required min="1" value="1">
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea name="address" class="form-control" required></textarea>
            </div>

            <div class="mb-3">
                <label for="distance_km" class="form-label">Distance (in KM)</label>
                <input type="number" name="distance_km" class="form-control" required min="1">
            </div>

            <button type="submit" class="btn btn-primary">Place Order</button>
        </form>
    </div>
</x-app-layout>