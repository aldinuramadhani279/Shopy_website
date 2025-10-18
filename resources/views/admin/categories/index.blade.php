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
        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            <i class="bi bi-plus"></i> Add New Category
        </a>
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