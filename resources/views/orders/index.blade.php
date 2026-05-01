@extends('layouts.app')
@section('title', 'My Orders')
@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4"><i class="fas fa-box me-2 text-primary"></i>My Orders</h2>
    @if($orders->count())
    <div class="card border-0 shadow-sm rounded-3">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td class="fw-semibold text-primary">{{ $order->order_number }}</td>
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                        <td>{{ $order->items->count() }} item(s)</td>
                        <td class="fw-bold">${{ number_format($order->total, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $order->status_badge }}">{{ ucfirst($order->status) }}</span>
                        </td>
                        <td>
                            <span class="badge {{ $order->payment_status == 'paid' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('orders.show', $order->order_number) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $orders->links() }}</div>
    @else
    <div class="text-center py-5">
        <i class="fas fa-box-open fa-5x text-muted mb-3"></i>
        <h4 class="text-muted">No orders yet</h4>
        <a href="{{ route('shop.index') }}" class="btn btn-primary mt-2">Start Shopping</a>
    </div>
    @endif
</div>
@endsection
