@extends('admin.layouts.app')

@section('title', 'Products Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Products</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary mb-3">Add New Product</a>

                    <form action="{{ route('admin.products.index') }}" method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">Search</button>
                        </div>
                    </form>

                    <form action="{{ route('admin.products.toggle-active') }}" method="POST">
                        @csrf
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <button type="submit" name="action" value="activate" class="btn btn-success">Activate Selected</button>
                                <button type="submit" name="action" value="deactivate" class="btn btn-warning">Deactivate Selected</button>
                            </div>
                        </div>

                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="50"><input type="checkbox" id="select-all"></th>
                                    <th>Image</th>
                                    <th>Name</th>
                                <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td><input type="checkbox" name="ids[]" value="{{ $product->id }}"></td>
                                        <td>
                                            @if($product->image)
                                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" width="60">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                                        <td>${{ number_format($product->price, 2) }}</td>
                                        <td>{{ $product->stock }}</td>
                                        <td>
                                            <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-info">Edit</a>
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td><input type="checkbox" class="product-checkbox" value="{{ $product->id }}"></td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                        <td>${{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>
                            @if($product->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No products found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <button class="btn btn-sm btn-secondary" id="bulk-activate">Activate</button>
                <button class="btn btn-sm btn-secondary" id="bulk-deactivate">Deactivate</button>
                <button class="btn btn-sm btn-danger" id="bulk-delete">Delete</button>
            </div>
            <div>
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

<form id="bulk-action-form" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="action" id="bulk-action">
    <input type="hidden" name="ids" id="bulk-ids">
</form>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select all checkboxes
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.product-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Bulk actions
        document.getElementById('bulk-activate').addEventListener('click', function() {
            submitBulkAction('activate');
        });

        document.getElementById('bulk-deactivate').addEventListener('click', function() {
            submitBulkAction('deactivate');
        });

        document.getElementById('bulk-delete').addEventListener('click', function() {
            if (confirm('Are you sure you want to delete selected products?')) {
                submitBulkAction('delete');
            }
        });

        function submitBulkAction(action) {
            const selectedIds = Array.from(document.querySelectorAll('.product-checkbox:checked'))
                .map(checkbox => checkbox.value);

            if (selectedIds.length === 0) {
                alert('Please select at least one product.');
                return;
            }

            if (action === 'delete') {
                // Handle delete action separately
                // Handle bulk delete using the existing toggle-active route with action parameter
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.products.toggle-active") }}';
                
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);
                
                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'POST';
                form.appendChild(method);
                
                const action = document.createElement('input');
                action.type = 'hidden';
                action.name = 'action';
                action.value = 'delete';
                form.appendChild(action);
                
                const ids = document.createElement('input');
                ids.type = 'hidden';
                ids.name = 'ids';
                ids.value = JSON.stringify(selectedIds);
                form.appendChild(ids);
                
                document.body.appendChild(form);
                form.submit();
            } else {
                // Handle activate/deactivate actions
                document.getElementById('bulk-action').value = action;
                document.getElementById('bulk-ids').value = JSON.stringify(selectedIds);
                document.getElementById('bulk-action-form').action = '{{ route("admin.products.toggle-active") }}';
                document.getElementById('bulk-action-form').submit();
            }
        }
    });
</script>
@endsection