@extends('admin.layouts.app')

@section('title', 'Coupons Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Coupons</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('coupons.create') }}" class="btn btn-primary">
            <i class="bi bi-plus"></i> Add New Coupon
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Minimum Amount</th>
                        <th>Usage</th>
                        <th>Validity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons as $coupon)
                    <tr>
                        <td>{{ $coupon->code }}</td>
                        <td>{{ ucfirst($coupon->type) }}</td>
                        <td>
                            @if($coupon->type === 'percentage')
                                {{ $coupon->value }}%
                            @else
                                ${{ number_format($coupon->value, 2) }}
                            @endif
                        </td>
                        <td>${{ $coupon->minimum_amount ? number_format($coupon->minimum_amount, 2) : '0.00' }}</td>
                        <td>
                            @if($coupon->usage_limit)
                                {{ $coupon->used_count }} / {{ $coupon->usage_limit }}
                            @else
                                {{ $coupon->used_count }} / Unlimited
                            @endif
                        </td>
                        <td>
                            {{ $coupon->start_date->format('M d, Y') }} - {{ $coupon->end_date->format('M d, Y') }}
                        </td>
                        <td>
                            @if($coupon->is_active && $coupon->start_date <= now() && $coupon->end_date >= now())
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('coupons.edit', $coupon) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('coupons.destroy', $coupon) }}" method="POST" class="d-inline">
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
                        <td colspan="8" class="text-center">No coupons found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $coupons->links() }}
        </div>
    </div>
</div>
@endsection