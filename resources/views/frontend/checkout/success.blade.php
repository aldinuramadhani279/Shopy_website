@extends('frontend.layouts.app')

@section('title', 'Order Success - Shopy')

@section('content')
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Cart</a></li>
            <li class="breadcrumb-item"><a href="{{ route('checkout.index') }}">Checkout</a></li>
            <li class="breadcrumb-item active" aria-current="page">Order Success</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                    </div>
                    
                    <h1 class="mb-3">Order Placed Successfully!</h1>
                    
                    <p class="lead">Thank you for your order. Your order number is <strong>{{ $order->order_number }}</strong>.</p>
                    
                    <div class="alert alert-info">
                        @if($order->payment_method === 'cod')
                            <p>Your order is being processed and will be shipped soon.</p>
                        @else
                            <p>Please complete your payment to process your order.</p>
                        @endif
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="{{ route('account.orders') }}" class="btn btn-primary btn-lg me-md-2">
                            <i class="bi bi-list"></i> View Orders
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-lg">
                            <i class="bi bi-arrow-left"></i> Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection