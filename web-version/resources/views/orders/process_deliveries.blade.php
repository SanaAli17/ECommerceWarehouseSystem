<x-app-layout>
    <div class="container mt-4">
        <h4>Process Deliveries by Distance</h4>

        @if($deliveryOrders->isEmpty())
            <div class="alert alert-info">No delivery orders found.</div>
        @else
            <table class="table table-bordered mt-3">
                <thead class="table-light">
                    <tr>
                        <th>Customer Name</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Total (excluding delivery charges)</th>
                        <th>Delivery Charges</th>
                        <th>Address</th>
                        <th>Distance (KM)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($deliveryOrders as $order)
                        <tr>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->product->name }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>PKR {{ number_format($order->total_amount - 240, 2) }}</td>
                            <td>PKR 240</td>
                            <td>{{ $order->address }}</td>
                            <td>{{ $order->distance_km }} km</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-app-layout>