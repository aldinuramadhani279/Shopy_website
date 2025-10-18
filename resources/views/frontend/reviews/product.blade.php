@extends('frontend.layouts.app')

@section('title', $product->name . ' - Reviews')

@section('content')
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Reviews</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    @if($product->image)
                        <img src="{{ asset($product->image) }}" class="img-fluid rounded" alt="{{ $product->name }}">
                    @else
                        <div class="bg-secondary" style="height: 200px;"></div>
                    @endif
                    <h5 class="mt-3">{{ $product->name }}</h5>
                    <p class="text-muted">{{ $product->category->name ?? 'Uncategorized' }}</p>
                    <p class="text-primary"><strong>${{ number_format($product->price, 2) }}</strong></p>
                    <a href="{{ route('products.show', $product->slug) }}" class="btn btn-primary">Back to Product</a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <h2>Customer Reviews</h2>
            
            @if($reviews->count() > 0)
                <div class="row">
                    @foreach($reviews as $review)
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title">{{ $review->user->name }}</h5>
                                        <div>
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                @else
                                                    <i class="bi bi-star text-warning"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="card-text">{{ $review->comment }}</p>
                                    <small class="text-muted">Posted on {{ $review->created_at->format('M d, Y') }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="d-flex justify-content-center">
                    {{ $reviews->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <p>No reviews yet for this product.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection