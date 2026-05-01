@extends('layouts.admin')
@section('title', 'Dashboard')

@push('styles')
<style>
    .stat-card-new {
        border-radius: 16px;
        border: none;
        overflow: hidden;
        position: relative;
        transition: transform .2s, box-shadow .2s;
    }
    .stat-card-new:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,.15) !important; }
    .stat-card-new .stat-icon {
        width: 52px; height: 52px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; flex-shrink: 0;
    }
    .stat-card-new .stat-value { font-size: 1.8rem; font-weight: 800; line-height: 1; }
    .stat-card-new .stat-label { font-size: .78rem; text-transform: uppercase; letter-spacing: .8px; opacity: .7; }
    .stat-card-new .stat-trend { font-size: .75rem; }

    .quick-action {
        border-radius: 12px; border: none; padding: 1rem;
        display: flex; align-items: center; gap: .75rem;
        font-weight: 600; transition: transform .15s, box-shadow .15s;
        text-decoration: none;
    }
    .quick-action:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,.12); }
    .quick-action .qa-icon {
        width: 40px; height: 40px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
    }

    .order-row td { vertical-align: middle; }
    .section-header { font-size: 1rem; font-weight: 700; letter-spacing: .3px; }
</style>
@endpush

@section('content')

{{-- ── Greeting ── --}}
<div class="mb-4">
    <h4 class="fw-bold mb-0">Welcome back, {{ Auth::user()->name }} 👋</h4>
    <p class="text-muted small mb-0">{{ now()->format('l, F j, Y') }} — Here's what's happening today.</p>
</div>

{{-- ── Stat Cards ── --}}
<div class="row g-3 mb-4">

    <div class="col-6 col-md-4 col-xl-2">
        <div class="card stat-card-new shadow-sm h-100">
            <div class="card-body p-3 d-flex flex-column gap-2">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="stat-icon" style="background:rgba(26,115,232,.15);color:#1a73e8;">
                        <i class="fas fa-box"></i>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size:.65rem;">Total</span>
                </div>
                <div>
                    <div class="stat-value">{{ $stats['total_products'] }}</div>
                    <div class="stat-label text-muted">Products</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-2">
        <div class="card stat-card-new shadow-sm h-100">
            <div class="card-body p-3 d-flex flex-column gap-2">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="stat-icon" style="background:rgba(217,48,37,.12);color:#d93025;">
                        <i class="fas fa-tags"></i>
                    </div>
                    <span class="badge bg-danger bg-opacity-10 text-danger" style="font-size:.65rem;">Total</span>
                </div>
                <div>
                    <div class="stat-value">{{ $stats['total_categories'] }}</div>
                    <div class="stat-label text-muted">Categories</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-2">
        <div class="card stat-card-new shadow-sm h-100">
            <div class="card-body p-3 d-flex flex-column gap-2">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="stat-icon" style="background:rgba(249,171,0,.15);color:#f9ab00;">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <span class="badge bg-warning bg-opacity-10 text-warning" style="font-size:.65rem;">Total</span>
                </div>
                <div>
                    <div class="stat-value">{{ $stats['total_orders'] }}</div>
                    <div class="stat-label text-muted">Orders</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-2">
        <div class="card stat-card-new shadow-sm h-100">
            <div class="card-body p-3 d-flex flex-column gap-2">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="stat-icon" style="background:rgba(30,142,62,.12);color:#1e8e3e;">
                        <i class="fas fa-users"></i>
                    </div>
                    <span class="badge bg-success bg-opacity-10 text-success" style="font-size:.65rem;">Total</span>
                </div>
                <div>
                    <div class="stat-value">{{ $stats['total_users'] }}</div>
                    <div class="stat-label text-muted">Customers</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-2">
        <div class="card stat-card-new shadow-sm h-100" style="background:linear-gradient(135deg,#1a73e8,#0d47a1);">
            <div class="card-body p-3 d-flex flex-column gap-2">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="stat-icon" style="background:rgba(255,255,255,.2);color:#fff;">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <span class="badge" style="background:rgba(255,255,255,.2);color:#fff;font-size:.65rem;">Revenue</span>
                </div>
                <div>
                    <div class="stat-value text-white">${{ number_format($stats['revenue'], 0) }}</div>
                    <div class="stat-label" style="color:rgba(255,255,255,.7);">Total Revenue</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-2">
        <div class="card stat-card-new shadow-sm h-100" style="background:linear-gradient(135deg,#ff6b00,#e05a00);">
            <div class="card-body p-3 d-flex flex-column gap-2">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="stat-icon" style="background:rgba(255,255,255,.2);color:#fff;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <span class="badge" style="background:rgba(255,255,255,.2);color:#fff;font-size:.65rem;">Pending</span>
                </div>
                <div>
                    <div class="stat-value text-white">{{ $stats['pending_orders'] }}</div>
                    <div class="stat-label" style="color:rgba(255,255,255,.7);">Pending Orders</div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── Quick Actions ── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <a href="{{ route('admin.products.index') }}" class="quick-action card shadow-sm">
            <div class="qa-icon" style="background:rgba(26,115,232,.12);color:#1a73e8;"><i class="fas fa-plus"></i></div>
            <span style="color:var(--text);">Add Product</span>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('admin.categories.index') }}" class="quick-action card shadow-sm">
            <div class="qa-icon" style="background:rgba(217,48,37,.1);color:#d93025;"><i class="fas fa-tags"></i></div>
            <span style="color:var(--text);">Categories</span>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('admin.orders.index') }}" class="quick-action card shadow-sm">
            <div class="qa-icon" style="background:rgba(249,171,0,.12);color:#f9ab00;"><i class="fas fa-shopping-bag"></i></div>
            <span style="color:var(--text);">View Orders</span>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('home') }}" target="_blank" class="quick-action card shadow-sm">
            <div class="qa-icon" style="background:rgba(30,142,62,.1);color:#1e8e3e;"><i class="fas fa-store"></i></div>
            <span style="color:var(--text);">View Store</span>
        </a>
    </div>
</div>

{{-- ── Recent Orders ── --}}
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="d-flex justify-content-between align-items-center p-3 pb-0">
            <h6 class="section-header mb-0"><i class="fas fa-receipt me-2 text-primary"></i>Recent Orders</h6>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                View All <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="table-responsive mt-2">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Order #</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="pe-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                    <tr class="order-row">
                        <td class="ps-3 fw-semibold text-primary">{{ $order->order_number }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white"
                                     style="width:30px;height:30px;font-size:.7rem;font-weight:700;flex-shrink:0;">
                                    {{ strtoupper(substr($order->user->name ?? $order->shipping_name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="small fw-semibold">{{ $order->user->name ?? $order->shipping_name }}</div>
                                    <div class="text-muted" style="font-size:.72rem;">{{ $order->shipping_city }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="fw-bold">${{ number_format($order->total, 2) }}</td>
                        <td>
                            <span class="badge rounded-pill {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge rounded-pill bg-{{ $order->status_badge }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="text-muted small">{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="pe-3">
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>No orders yet
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
