@extends('layouts.app')
@section('title', 'Order ' . $order->order_number)
@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
        <h2 class="fw-bold mb-0">Order {{ $order->order_number }}</h2>
        <span class="badge bg-{{ $order->status_badge }} fs-6">{{ ucfirst($order->status) }}</span>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Order Items</h5>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr>
                            </thead>
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
                                <tr class="fw-bold"><td colspan="3" class="text-end">Total</td><td class="text-primary fs-5">${{ number_format($order->total, 2) }}</td></tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 mb-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Shipping Address</h6>
                    <p class="mb-1 fw-semibold">{{ $order->shipping_name }}</p>
                    <p class="mb-1 text-muted small">{{ $order->shipping_email }}</p>
                    <p class="mb-1 text-muted small">{{ $order->shipping_phone }}</p>
                    <p class="mb-0 text-muted small">{{ $order->shipping_address }}, {{ $order->shipping_city }}</p>
                </div>
            </div>
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Payment Info</h6>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted small">Method</span>
                        <span class="small">{{ $order->payment_method == 'cod' ? 'Cash on Delivery' : 'Bank Transfer' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Status</span>
                        <span class="badge {{ $order->payment_status == 'paid' ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
