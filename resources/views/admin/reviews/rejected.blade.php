@extends('admin.layouts.app')

@section('title', 'Reviews - Rejected')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Rejected Reviews</h1>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <div class="btn-group" role="group">
            <a href="{{ route('reviews.index') }}" class="btn btn-outline-primary {{ request()->routeIs('reviews.index') ? 'active' : '' }}">Pending</a>
            <a href="{{ route('reviews.approved') }}" class="btn btn-outline-success {{ request()->routeIs('reviews.approved') ? 'active' : '' }}">Approved</a>
            <a href="{{ route('reviews.rejected') }}" class="btn btn-outline-danger {{ request()->routeIs('reviews.rejected') ? 'active' : '' }}">Rejected</a>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Product</th>
                <th>Rating</th>
                <th>Comment</th>
                <th>Order</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reviews as $review)
                <tr>
                    <td>{{ $review->user->name }}</td>
                    <td>{{ $review->product->name }}</td>
                    <td>
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi {{ $i <= $review->rating ? 'bi-star-fill text-warning' : 'bi-star text-secondary' }}"></i>
                        @endfor
                        ({{ $review->rating }})
                    </td>
                    <td>{{ Str::limit($review->comment, 50) }}</td>
                    <td>{{ $review->order->order_number }}</td>
                    <td>{{ $review->created_at->format('M d, Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No rejected reviews</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    {{ $reviews->links() }}
</div>
@endsection