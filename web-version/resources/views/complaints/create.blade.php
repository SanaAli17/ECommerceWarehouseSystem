<x-app-layout>
    <div class="container mt-4">
        <h4>Submit Complaint</h4>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('complaints.store') }}">
            @csrf
            <div class="mb-3">
                <label for="text" class="form-label">Complaint</label>
                <textarea name="text" class="form-control" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-danger">Submit Complaint</button>
        </form>
    </div>
</x-app-layout>