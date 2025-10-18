<div class="card h-100 product-card">
    <a href="{{ route('products.show', $product->slug) }}">
        @if($product->image)
            <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
        @else
            <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                <span class="text-white">No Image</span>
            </div>
        @endif
    </a>
    <div class="card-body d-flex flex-column">
        <h5 class="card-title"><a href="{{ route('products.show', $product->slug) }}" class="text-dark text-decoration-none">{{ $product->name }}</a></h5>
        <p class="card-text flex-grow-1">{{ Str::limit($product->description, 100) }}</p>
        <div class="d-flex justify-content-between align-items-center mt-auto">
            <strong class="text-primary fs-5">${{ number_format($product->price, 2) }}</strong>
            <div>
                <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-primary btn-sm">Add to Cart</button>
                </form>
                <form action="{{ route('wishlist.add') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-heart"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
