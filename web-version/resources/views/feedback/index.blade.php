<x-app-layout>
    <div class="container mt-4">
        <h4>All Feedbacks</h4>

        @if($feedbacks->isEmpty())
            <div class="alert alert-info">No feedback found.</div>
        @else
            <table class="table table-bordered mt-3">
                <thead class="table-light">
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Product</th>
                        <th>Feedback</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($feedbacks as $fb)
                        <tr>
                            <td>{{ $fb->order->order_id }}</td>
                            <td>{{ $fb->order->customer_name }}</td>
                            <td>{{ $fb->order->product->name ?? 'N/A' }}</td>
                            <td>{{ $fb->feedback }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-app-layout>