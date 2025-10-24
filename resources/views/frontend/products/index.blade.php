@extends('frontend.layouts.app')

@section('title', 'Products - Shopy')

@section('styles')
<style>
    .product-list-item {
        border: 1px solid #e9ecef;
        transition: box-shadow 0.3s ease;
    }
    .product-list-item:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }
    .product-list-item .card-img-top {
        height: 220px;
        object-fit: cover;
    }
    .product-list-item .product-actions {
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .product-list-item:hover .product-actions {
        opacity: 1;
    }
    .filter-sidebar .form-control, .filter-sidebar .form-select {
        font-size: 0.9rem;
    }
    .page-header {
        background-color: #f8f9fa;
        padding: 2.5rem;
        border-radius: 0.5rem;
        margin-bottom: 2.5rem;
    }
</style>
@endsection

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Sidebar with filters -->
        <div class="col-lg-3">
            <div class="filter-sidebar">
                <h4 class="mb-4">Filters</h4>
                <form method="GET" action="{{ route('products.index') }}" id="filter-form">
                    <div class="accordion" id="filterAccordion">
                        <!-- Search -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingSearch">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSearch" aria-expanded="true" aria-controls="collapseSearch">
                                    Search Products
                                </button>
                            </h2>
                            <div id="collapseSearch" class="accordion-collapse collapse show" aria-labelledby="headingSearch">
                                <div class="accordion-body">
                                    <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Product name...">
                                </div>
                            </div>
                        </div>

                        <!-- Categories -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingCategory">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCategory" aria-expanded="true" aria-controls="collapseCategory">
                                    Category
                                </button>
                            </h2>
                            <div id="collapseCategory" class="accordion-collapse collapse show" aria-labelledby="headingCategory">
                                <div class="accordion-body">
                                    <select class="form-select" id="category" name="category">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingPrice">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePrice" aria-expanded="true" aria-controls="collapsePrice">
                                    Price Range
                                </button>
                            </h2>
                            <div id="collapsePrice" class="accordion-collapse collapse show" aria-labelledby="headingPrice">
                                <div class="accordion-body">
                                    <div class="row g-2">
                                        <div class="col">
                                            <input type="number" class="form-control" name="min_price" placeholder="Min" value="{{ request('min_price') }}" min="0">
                                        </div>
                                        <div class="col">
                                            <input type="number" class="form-control" name="max_price" placeholder="Max" value="{{ request('max_price') }}" min="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Apply Filters
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-eraser"></i> Clear All
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products -->
        <div class="col-lg-9">
            <div class="page-header text-center">
                <h1 class="display-6">Our Products</h1>
                <p class="lead text-muted">Browse our collection of high-quality items.</p>
            </div>

            <!-- Categories Section -->
            <div class="container my-5">
                <h2 class="text-center mb-4">Shop by Category</h2>
                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-4 text-center">
                    @foreach($categories as $category)
                        <div class="col">
                            <a href="{{ route('products.index', ['category' => $category->id]) }}" class="text-decoration-none text-dark">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body d-flex flex-column justify-content-center">
                                        @if($category->image)
                                            <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="img-fluid rounded-circle mb-2 mx-auto" style="width: 80px; height: 80px; object-fit: cover;">
                                        @else
                                            <div class="img-fluid rounded-circle mb-2 mx-auto bg-secondary d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                                <i class="bi bi-tag text-white fs-3"></i>
                                            </div>
                                        @endif
                                        <h6 class="card-title mt-2">{{ $category->name }}</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- End Categories Section -->

            <div class="d-flex justify-content-between align-items-center mb-4">
                <p class="text-muted mb-0">Showing {{ $products->firstItem() }}-{{ $products->lastItem() }} of {{ $products->total() }} results</p>
                <div>
                    <form method="GET" action="{{ route('products.index') }}" class="d-inline">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="category" value="{{ request('category') }}">
                        <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                        <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                        
                        <select class="form-select form-select-sm" name="sort" onchange="this.form.submit()">
                            <option value="">Sort By: Featured</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z to A</option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @forelse($products as $product)
                <div class="col">
                    @include('frontend.products._product_list_item', ['product' => $product])
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info text-center py-5">
                        <i class="bi bi-inbox fs-1"></i>
                        <h4 class="mt-3">No Products Found</h4>
                        <p>Your search or filter criteria did not match any products.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary mt-2">Reset All Filters</a>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-submit form when category changes in the main filter form
    document.getElementById('category').addEventListener('change', function() {
        document.getElementById('filter-form').submit();
    });
</script>
@endsection