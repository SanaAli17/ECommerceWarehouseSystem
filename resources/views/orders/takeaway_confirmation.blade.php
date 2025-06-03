<x-app-layout>
    <div class="container mt-4">
        <h4>TakeAway Order Placed Successfully!</h4>

        <div class="alert alert-success">
            Your order ID is <strong>{{ $order->order_id }}</strong><br>
            Total Bill: <strong>PKR {{ number_format($order->total_amount, 2) }}</strong>
        </div>

        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</x-app-layout>