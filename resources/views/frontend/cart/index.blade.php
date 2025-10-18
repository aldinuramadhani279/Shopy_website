@extends('frontend.layouts.app')

@section('title', 'Shopping Cart - Shopy')

@section('content')
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
        </ol>
    </nav>

    <h1>Shopping Cart</h1>

    @if(count($cartItems) > 0)
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Cart Items</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex">
                                            @if($item['image'])
                                                <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" style="width: 80px; height: 80px; object-fit: cover;" class="me-3">
                                            @else
                                                <div class="bg-secondary" style="width: 80px; height: 80px;" class="me-3"></div>
                                            @endif
                                            <div>
                                                <h6>{{ $item['name'] }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${{ number_format($item['price'], 2) }}</td>
                                    <td>
                                        <form action="{{ route('cart.update') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                            <div class="input-group" style="width: 120px;">
                                                <button class="btn btn-outline-secondary btn-sm" type="button" 
                                                        onclick="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] - 1 }})">-</button>
                                                <input type="number" class="form-control form-control-sm text-center" 
                                                       id="quantity-{{ $item['id'] }}" name="quantity" 
                                                       value="{{ $item['quantity'] }}" min="1" readonly>
                                                <button class="btn btn-outline-secondary btn-sm" type="button" 
                                                        onclick="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] + 1 }})">+</button>
                                            </div>
                                        </form>
                                    </td>
                                    <td>${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                    <td>
                                        <form action="{{ route('cart.remove') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                        
                        @if($discount > 0)
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Discount:</span>
                            <span>-${{ number_format($discount, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Coupon:</strong>
                            <strong>{{ session('cart.coupon_code') }}</strong>
                            <form action="{{ route('cart.coupon.remove') }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                            </form>
                        </div>
                        @endif
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong>${{ number_format($total, 2) }}</strong>
                        </div>
                        
                        <div class="mt-4">
                            <h6>Apply Coupon</h6>
                            <form action="{{ route('cart.coupon.apply') }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <input type="text" name="coupon_code" class="form-control" placeholder="Enter coupon code" required>
                                    <button class="btn btn-primary" type="submit">Apply</button>
                                </div>
                            </form>
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <a href="{{ route('checkout.index') }}" class="btn btn-success btn-lg">
                                <i class="bi bi-credit-card"></i> Proceed to Checkout
                            </a>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-left"></i> Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            Your cart is empty. <a href="{{ route('products.index') }}">Continue shopping</a>
        </div>
    @endif
</div>

<form id="update-form" action="{{ route('cart.update') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="product_id" id="update-product-id">
    <input type="hidden" name="quantity" id="update-quantity">
</form>
@endsection

@section('scripts')
<script>
    function updateQuantity(productId, quantity) {
        if (quantity < 1) return;
        
        document.getElementById('update-product-id').value = productId;
        document.getElementById('update-quantity').value = quantity;
        document.getElementById('update-form').submit();
    }
</script>
@endsection