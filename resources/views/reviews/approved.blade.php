<!DOCTYPE html>
<html>
<head>
    <title>Review Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Approved Reviews</h1>
        
        <div class="mb-3">
            <a href="{{ route('reviews.index') }}" class="btn btn-primary">Pending Reviews</a>
            <a href="{{ route('reviews.rejected') }}" class="btn btn-danger">Rejected Reviews</a>
        </div>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Product</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                <tr>
                    <td>{{ $review->user->name ?? 'N/A' }}</td>
                    <td>{{ $review->product->name ?? 'N/A' }}</td>
                    <td>{{ $review->rating }}/5</td>
                    <td>{{ $review->comment }}</td>
                    <td>{{ $review->created_at->format('M d, Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">No approved reviews found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>