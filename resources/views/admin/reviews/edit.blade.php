@extends('admin.layouts.app')

@section('title', 'Review Details')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Review Details</h1>
</div>

<div class="card">
    <div class="card-header">
        <h5>Review Information</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table">
                    <tr>
                        <th>Customer</th>
                        <td>{{ $review->user->name }}</td>
                    </tr>
                    <tr>
                        <th>Product</th>
                        <td>{{ $review->product->name }}</td>
                    </tr>
                    <tr>
                        <th>Order</th>
                        <td>{{ $review->order->order_number }}</td>
                    </tr>
                    <tr>
                        <th>Rating</th>
                        <td>
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi {{ $i <= $review->rating ? 'bi-star-fill text-warning' : 'bi-star text-secondary' }}"></i>
                            @endfor
                            ({{ $review->rating }} stars)
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge bg-{{ $review->status === 'approved' ? 'success' : ($review->status === 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($review->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td>{{ $review->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Comment</label>
                    <div class="form-control" style="height: auto;">{{ $review->comment }}</div>
                </div>
                @if($review->admin_comment)
                    <div class="mb-3">
                        <label class="form-label">Admin Comment</label>
                        <div class="form-control" style="height: auto;">{{ $review->admin_comment }}</div>
                    </div>
                @endif
            </div>
        </div>
        
        <hr>
        
        <form action="{{ route('reviews.update', $review) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="pending" {{ old('status', $review->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ old('status', $review->status) === 'approved' ? 'selected' : '' }}>Approve</option>
                            <option value="rejected" {{ old('status', $review->status) === 'rejected' ? 'selected' : '' }}>Reject</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="comment" class="form-label">Admin Comment (Optional)</label>
                <textarea name="comment" id="comment" class="form-control @error('comment') is-invalid @enderror" rows="3">{{ old('comment') }}</textarea>
                @error('comment')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
                <button type="submit" class="btn btn-primary">Update Review</button>
            </div>
        </form>
    </div>
</div>
@endsection