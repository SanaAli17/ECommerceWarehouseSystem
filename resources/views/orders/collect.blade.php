<x-app-layout>
    <div class="container mt-4">
        <h4>Collect Takeaway Order</h4>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('orders.takeaway.collect.process') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="order_id" class="form-label">Enter Order ID</label>
                <input type="text" name="order_id" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Check Order</button>
        </form>
    </div>
</x-app-layout>