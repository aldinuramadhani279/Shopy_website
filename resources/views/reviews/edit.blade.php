<!DOCTYPE html>
<html>
<head>
    <title>Review Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Review Details</h1>
        
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Product: {{ $review->product->name ?? 'N/A' }}</h5>
                <h6 class="card-subtitle mb-2 text-muted">User: {{ $review->user->name ?? 'N/A' }}</h6>
                <p class="card-text">
                    <strong>Rating:</strong> {{ $review->rating }}/5<br>
                    <strong>Comment:</strong> {{ $review->comment }}<br>
                    <strong>Date:</strong> {{ $review->created_at->format('M d, Y H:i') }}
                </p>
            </div>
        </div>
        
        <form action="{{ route('reviews.update', $review) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label">Status</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="approved" value="approved" {{ old('status', $review->status) == 'approved' ? 'checked' : '' }}>
                        <label class="form-check-label" for="approved">Approve</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="rejected" value="rejected" {{ old('status', $review->status) == 'rejected' ? 'checked' : '' }}>
                        <label class="form-check-label" for="rejected">Reject</label>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Update Status</button>
            <a href="{{ route('reviews.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>