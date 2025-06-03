<x-app-layout>
    <h4>Place Takeaway Order</h4>

    <form action="{{ route('orders.storeTakeaway') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Customer Name</label>
            <input type="text" name="customer_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Product</label>
            <select name="product_id" class="form-control" required>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">
                        {{ $product->name }} (PKR {{ number_format($product->price, 2) }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Quantity</label>
            <input type="number" name="quantity" min="1" class="form-control" required>
        </div>

        <button class="btn btn-primary">Place Order</button>
    </form>
</x-app-layout>