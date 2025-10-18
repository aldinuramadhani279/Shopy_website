<div class="card h-100 product-list-item">
    <a href="{{ route('products.show', $product->slug) }}">
        @if($product->image)
            <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}">
        @else
            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 220px;">
                <span class="text-muted">No Image</span>
            </div>
        @endif
    </a>
    <div class="card-body">
        <h5 class="card-title fs-6 mb-1">{{ $product->name }}</h5>
        <p class="card-text text-muted small">{{ $product->category->name ?? 'Uncategorized' }}</p>
        <p class="card-text fw-bold fs-5 text-primary">${{ number_format($product->price, 2) }}</p>
    </div>
    <div class="card-footer bg-white border-0 pt-0">
        <div class="product-actions">
            <form action="{{ route('cart.add') }}" method="POST" class="d-inline w-100">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="btn btn-primary w-100">Add to Cart</button>
            </form>
            <form action="{{ route('wishlist.add') }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" class="btn btn-outline-danger btn-sm mt-2">
                    <i class="bi bi-heart"></i>
                </button>
            </form>
        </div>
    </div>
</div>
