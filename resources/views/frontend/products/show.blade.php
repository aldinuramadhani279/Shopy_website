@extends('frontend.layouts.app')

@section('title', $product->name . ' - Shopy')

@section('content')
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Images -->
        <div class="col-md-6">
            <div class="card mb-4">
                <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}" id="main-image">
                @if($product->gallery && count(json_decode($product->gallery)) > 0)
                <div class="card-body">
                    <div class="d-flex flex-wrap">
                        <img src="{{ asset($product->image) }}" class="img-thumbnail me-2 mb-2 gallery-image" style="width: 100px; height: 100px; object-fit: cover; cursor: pointer;" alt="{{ $product->name }}">
                        @foreach(json_decode($product->gallery) as $image)
                            <img src="{{ asset($image) }}" class="img-thumbnail me-2 mb-2 gallery-image" style="width: 100px; height: 100px; object-fit: cover; cursor: pointer;" alt="{{ $product->name }}">
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <h1>{{ $product->name }}</h1>
            <p class="lead text-muted">
                @if($product->category)
                In <a href="{{ route('products.index', ['category' => $product->category->id]) }}">{{ $product->category->name }}</a>
                @else
                Uncategorized
                @endif
            </p>
            
            <div class="mb-3">
                <h3 class="text-primary fw-bold">${{ number_format($product->price, 2) }}</h3>
            </div>

            <div class="mb-3">
                <p>{{ Str::limit($product->description, 150) }}</p>
            </div>

            <div class="card bg-light p-3 mb-4">
                <div class="d-flex justify-content-between">
                    <span><strong>Stock:</strong> <span class="badge bg-success">{{ $product->stock }} items available</span></span>
                    <span><strong>Weight:</strong> {{ $product->weight }} grams</span>
                </div>
            </div>

            <!-- Add to Cart & Buy Now Forms -->
            <div class="d-flex align-items-center">
                <form action="{{ route('cart.add') }}" method="POST" class="d-flex me-2" id="add-to-cart-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="input-group" style="width: 150px;">
                        <button class="btn btn-outline-secondary" type="button" id="decrease-qty">-</button>
                        <input type="number" class="form-control text-center" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}">
                        <button class="btn btn-outline-secondary" type="button" id="increase-qty">+</button>
                    </div>
                    <button type="submit" class="btn btn-primary ms-2">
                        <i class="bi bi-cart-plus"></i> Add to Cart
                    </button>
                </form>

                <form action="{{ route('cart.buy_now') }}" method="POST" id="buy-now-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" id="buy-now-quantity" value="1">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-lightning-fill"></i> Buy Now
                    </button>
                </form>
            </div>

            <form action="{{ route('wishlist.add') }}" method="POST" class="mt-3">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-heart"></i> Add to Wishlist
                </button>
            </form>
        </div>
    </div>

    <!-- Product Info Tabs -->
    <div class="row mt-5">
        <div class="col-12">
            <ul class="nav nav-tabs" id="productTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Full Description</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs" type="button" role="tab" aria-controls="specs" aria-selected="false">Specifications</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Customer Reviews ({{ $product->reviews->where('status', 'approved')->count() }})</button>
                </li>
            </ul>
            <div class="tab-content card p-4" id="productTabContent">
                <!-- Description Tab -->
                <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                    <p>{{ $product->description }}</p>
                </div>
                <!-- Specifications Tab -->
                <div class="tab-pane fade" id="specs" role="tabpanel" aria-labelledby="specs-tab">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th scope="row" style="width: 200px;">Weight</th>
                                <td>{{ $product->weight }} grams</td>
                            </tr>
                            <tr>
                                <th scope="row">Stock Availability</th>
                                <td>{{ $product->stock }} items</td>
                            </tr>
                            <tr>
                                <th scope="row">Category</th>
                                <td>{{ $product->category->name ?? 'Uncategorized' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Reviews Tab -->
                <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                    @if($product->reviews->where('status', 'approved')->count() > 0)
                        <div class="row">
                            @foreach($product->reviews->where('status', 'approved') as $review)
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="card-title">{{ $review->user->name }}</h5>
                                            <div>
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} text-warning"></i>
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="card-text">{{ $review->comment }}</p>
                                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p>No reviews yet. Be the first to review this product!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <div class="row mt-5">
        <div class="col-12">
            <h3>Related Products</h3>
            <div class="row">
                @forelse($relatedProducts as $relatedProduct)
                <div class="col-md-3 mb-4">
                    @include('frontend.products._product_card', ['product' => $relatedProduct])
                </div>
                @empty
                <div class="col-12">
                    <p>No related products found.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const decreaseBtn = document.getElementById('decrease-qty');
        const increaseBtn = document.getElementById('increase-qty');
        const quantityInput = document.getElementById('quantity');
        const buyNowQuantityInput = document.getElementById('buy-now-quantity');
        const maxQuantity = {{ $product->stock }};

        function updateBuyNowQuantity() {
            buyNowQuantityInput.value = quantityInput.value;
        }

        decreaseBtn.addEventListener('click', function() {
            let value = parseInt(quantityInput.value);
            if (value > 1) {
                quantityInput.value = value - 1;
                updateBuyNowQuantity();
            }
        });

        increaseBtn.addEventListener('click', function() {
            let value = parseInt(quantityInput.value);
            if (value < maxQuantity) {
                quantityInput.value = value + 1;
                updateBuyNowQuantity();
            }
        });

        quantityInput.addEventListener('change', function() {
            updateBuyNowQuantity();
        });

        // Gallery image click
        const galleryImages = document.querySelectorAll('.gallery-image');
        const mainImage = document.getElementById('main-image');

        galleryImages.forEach(function(image) {
            image.addEventListener('click', function() {
                mainImage.src = this.src;
            });
        });
    });
</script>
@endsection