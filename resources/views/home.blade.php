@extends('frontend.layouts.app')

@section('title', 'Shopy - Welcome')

@section('styles')
<style>
    .simple-hero {
        background: linear-gradient(to right, #6a11cb 0%, #2575fc 100%);
        color: white;
        padding: 80px 0;
        border-radius: 10px;
        margin-bottom: 40px;
    }
    .simple-hero h1 {
        font-size: 3.5rem;
        font-weight: 700;
    }
    .simple-hero p {
        font-size: 1.25rem;
        margin-bottom: 30px;
    }
</style>
@endsection

@section('content')
<div class="container mt-5">
    <div class="simple-hero text-center">
        <div class="container">
            <h1 class="display-4">Welcome to Shopy!</h1>
            <p class="lead">Your one-stop shop for all your needs. Discover amazing products and great deals.</p>
            <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">Explore Products</a>
        </div>
    </div>

    <!-- Latest Products -->
    <div class="mt-5">
        <h2 class="text-center mb-4">Latest Products</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            @forelse($latestProducts as $product)
            <div class="col">
                @include('frontend.products._product_list_item', ['product' => $product])
            </div>
            @empty
            <div class="col-12">
                <p class="text-center text-muted">No latest products to show.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Best Selling Products -->
    <div class="my-5">
        <h2 class="text-center mb-4">Best Selling Products</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            @forelse($bestSellingProducts as $product)
            <div class="col">
                @include('frontend.products._product_list_item', ['product' => $product])
            </div>
            @empty
            <div class="col-12">
                <p class="text-center text-muted">No best-selling products to show.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection