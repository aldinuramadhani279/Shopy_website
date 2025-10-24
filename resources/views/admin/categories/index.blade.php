@extends('admin.layouts.app')

@section('title', 'Categories Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Categories</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" id="activate-selected">Activate Selected</button>
            <button type="button" class="btn btn-sm btn-outline-secondary" id="deactivate-selected">Deactivate Selected</button>
        </div>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary mb-3">Add New Category</a>

                    <form action="{{ route('admin.categories.toggle-active') }}" method="POST">
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
                                    <th>Slug</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr>
                                        <td><input type="checkbox" name="ids[]" value="{{ $category->id }}"></td>
                                        <td>
                                            @if($category->image)
                                                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" width="60">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </td>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->slug }}</td>
                                        <td>
                                            <span class="badge bg-{{ $category->is_active ? 'success' : 'secondary' }}">
                                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-info">Edit</a>
                                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
    </div>
</div>

<form id="bulk-action-form" method="POST" action="{{ route('admin.categories.toggle-active') }}">
    @csrf
    <input type="hidden" name="action" id="action-input" value="">
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="select-all">
                            </th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr>
                            <td>
                                <input type="checkbox" class="category-select" name="ids[]" value="{{ $category->id }}">
                            </td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td>
                                @if($category->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
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
                            <td colspan="5" class="text-center">No categories found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('select-all');
        const categoryCheckboxes = document.querySelectorAll('.category-select');
        const activateBtn = document.getElementById('activate-selected');
        const deactivateBtn = document.getElementById('deactivate-selected');
        const actionInput = document.getElementById('action-input');
        const bulkActionForm = document.getElementById('bulk-action-form');

        // Select/deselect all checkboxes
        selectAllCheckbox.addEventListener('change', function() {
            categoryCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Update select all checkbox state based on individual checkboxes
        categoryCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allChecked = Array.from(categoryCheckboxes).every(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
            });
        });

        // Activate selected handler
        activateBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (Array.from(categoryCheckboxes).some(cb => cb.checked)) {
                actionInput.value = 'activate';
                if (confirm('Are you sure you want to activate selected categories?')) {
                    bulkActionForm.submit();
                }
            } else {
                alert('Please select at least one category.');
            }
        });

        // Deactivate selected handler
        deactivateBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (Array.from(categoryCheckboxes).some(cb => cb.checked)) {
                actionInput.value = 'deactivate';
                if (confirm('Are you sure you want to deactivate selected categories?')) {
                    bulkActionForm.submit();
                }
            } else {
                alert('Please select at least one category.');
            }
        });
    });
</script>
@endsection