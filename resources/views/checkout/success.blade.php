@extends('layouts.app')
@section('title', 'Order Confirmed')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-3 text-center p-5">
                <div class="mb-4">
                    <div class="rounded-circle bg-success d-inline-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px;">
                        <i class="fas fa-check fa-2x text-white"></i>
                    </div>
                    <h2 class="fw-bold text-success">Order Confirmed!</h2>
                    <p class="text-muted">Thank you for your purchase. Your order has been placed successfully.</p>
                </div>
                <div class="bg-light rounded-3 p-3 mb-4 text-start">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Order Number</span>
                        <span class="fw-bold text-primary">{{ $order->order_number }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Status</span>
                        <span class="badge bg-warning text-dark">{{ ucfirst($order->status) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Payment</span>
                        <span>{{ $order->payment_method == 'cod' ? 'Cash on Delivery' : 'Bank Transfer' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Total</span>
                        <span class="fw-bold fs-5 text-primary">${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
                <div class="d-flex gap-3 justify-content-center">
                    <a href="{{ route('orders.show', $order->order_number) }}" class="btn btn-primary">
                        <i class="fas fa-eye me-2"></i>View Order
                    </a>
                    <a href="{{ route('shop.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-store me-2"></i>Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
