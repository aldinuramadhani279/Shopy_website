@extends('frontend.layouts.app')

@section('title', 'Categories - Shopy')

@section('styles')
<style>
    .category-card {
        position: relative;
        border-radius: 0.5rem;
        overflow: hidden;
        display: block;
        text-decoration: none;
        border: none;
        height: 250px;
        background-size: cover;
        background-position: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
    .category-card .card-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s ease;
    }
    .category-card:hover .card-overlay {
        background: rgba(0, 0, 0, 0.3);
    }
    .category-card .card-title {
        color: white;
        font-size: 1.5rem;
        font-weight: bold;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.7);
    }
</style>
@endsection

@section('content')
<div class="container my-5">
    <div class="page-header text-center mb-5">
        <h1 class="display-6">Browse Our Categories</h1>
        <p class="lead text-muted">Select a category to start exploring our products.</p>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
        @forelse($categories as $category)
        <div class="col">
            <a href="{{ route('products.index', ['category' => $category->id]) }}" 
               class="category-card shadow-sm" 
               style="background-image: url('{{ $category->image ? asset($category->image) : asset('images/products/default-product.jpg') }}');">
                <div class="card-overlay">
                    <h5 class="card-title">{{ $category->name }}</h5>
                </div>
            </a>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center py-5">
                <i class="bi bi-inbox fs-1"></i>
                <h4 class="mt-3">No Categories Found</h4>
                <p>There are currently no categories to display.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
