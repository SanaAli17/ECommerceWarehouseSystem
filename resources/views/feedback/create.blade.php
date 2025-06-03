<x-app-layout>
    <div class="container mt-4">
        <h4>Submit Feedback</h4>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('feedback.store') }}">
            @csrf
            <div class="mb-3">
                <label for="order_id" class="form-label">Order ID</label>
                <input type="text" name="order_id" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="feedback" class="form-label">Feedback</label>
                <textarea name="feedback" class="form-control" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Feedback</button>
        </form>
    </div>
</x-app-layout>