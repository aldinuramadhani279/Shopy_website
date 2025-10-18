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
</div>
@endsection