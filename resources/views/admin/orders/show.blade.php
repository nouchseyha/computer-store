@extends('layouts.admin')
@section('title', 'Order ' . $order->order_number)
@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i>Back
    </a>
    <h5 class="fw-bold mb-0">Order {{ $order->order_number }}</h5>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <!-- Items -->
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Order Items</h6>
                <table class="table mb-0">
                    <thead class="table-light"><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>${{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td class="fw-semibold">${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr><td colspan="3" class="text-end text-muted">Subtotal</td><td>${{ number_format($order->subtotal, 2) }}</td></tr>
                        <tr><td colspan="3" class="text-end text-muted">Shipping</td><td>${{ number_format($order->shipping, 2) }}</td></tr>
                        <tr class="fw-bold"><td colspan="3" class="text-end">Total</td><td class="text-primary">${{ number_format($order->total, 2) }}</td></tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <!-- Update Status -->
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Update Order</h6>
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Order Status</label>
                            <select name="status" class="form-select">
                                @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                                    <option value="{{ $s }}" {{ $order->status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Payment Status</label>
                            <select name="payment_status" class="form-select">
                                <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary">Update Order</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-body">
                <h6 class="fw-bold mb-2">Customer</h6>
                <p class="mb-1 fw-semibold">{{ $order->user->name ?? 'N/A' }}</p>
                <p class="mb-0 text-muted small">{{ $order->user->email ?? '' }}</p>
            </div>
        </div>
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body">
                <h6 class="fw-bold mb-2">Shipping Address</h6>
                <p class="mb-1 fw-semibold">{{ $order->shipping_name }}</p>
                <p class="mb-1 text-muted small">{{ $order->shipping_phone }}</p>
                <p class="mb-0 text-muted small">{{ $order->shipping_address }}, {{ $order->shipping_city }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
