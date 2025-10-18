<!DOCTYPE html>
<html>
<head>
    <title>Review Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Pending Reviews</h1>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Product</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                <tr>
                    <td>{{ $review->user->name ?? 'N/A' }}</td>
                    <td>{{ $review->product->name ?? 'N/A' }}</td>
                    <td>{{ $review->rating }}/5</td>
                    <td>{{ $review->comment }}</td>
                    <td>
                        <a href="{{ route('reviews.edit', $review) }}" class="btn btn-primary btn-sm">Review</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">No pending reviews found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="mt-3">
            <a href="{{ route('reviews.approved') }}" class="btn btn-success">Approved Reviews</a>
            <a href="{{ route('reviews.rejected') }}" class="btn btn-danger">Rejected Reviews</a>
        </div>
    </div>
</body>
</html>