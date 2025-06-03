<x-app-layout>
    <div class="container mt-4">
        <h4>Recent Orders</h4>

        @if($orders->isEmpty())
            <div class="alert alert-info">No orders found.</div>
        @else
            <table class="table table-bordered mt-3">
                <thead class="table-light">
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Total Bill</th>
                        <th>Type</th>
                        <th>Address</th>
                        <th>Distance (KM)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->order_id }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->product->name }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>PKR {{ number_format($order->total_amount, 2) }}</td>
                            <td class="text-capitalize">{{ $order->order_type }}</td>
                            <td>{{ $order->order_type === 'delivery' ? $order->address : '—' }}</td>
                            <td>{{ $order->order_type === 'delivery' ? $order->distance_km . ' km' : '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-app-layout>