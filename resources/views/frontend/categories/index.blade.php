@extends('frontend.layouts.app')

@section('title', 'Categories - Shopy')

@section('content')
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Categories</li>
        </ol>
    </nav>

    <h1 class="mb-4">Shop by Category</h1>

    <div class="row">
        @forelse($categories as $category)
        <div class="col-md-3 mb-4">
            <div class="card category-card h-100">
                @if($category->image)
                    <img src="{{ asset($category->image) }}" class="card-img-top" alt="{{ $category->name }}" style="height: 150px; object-fit: cover;">
                @else
                    <div class="bg-secondary" style="height: 150px;"></div>
                @endif
                <div class="card-body text-center">
                    <h5 class="card-title">{{ $category->name }}</h5>
                    <a href="{{ route('products.index', ['category' => $category->id]) }}" class="btn btn-primary">View Products</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">No categories available at the moment.</div>
        </div>
        @endforelse
    </div>
</div>
@endsection

@section('styles')
<style>
    .category-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px rgba(0,0,0,0.15);
    }
</style>
@endsection