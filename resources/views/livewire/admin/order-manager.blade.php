<div>
    <div class="d-flex gap-2 mb-3 flex-wrap">
        <input type="text" wire:model.live.debounce.300ms="search" class="form-control" style="max-width:250px;" placeholder="Search order / customer...">
        <select wire:model.live="filterStatus" class="form-select" style="max-width:160px;">
            <option value="">All Statuses</option>
            @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                <option value="{{ $s }}">{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <div wire:loading class="spinner-border spinner-border-sm text-primary align-self-center"></div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Order #</th><th>Customer</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="fw-semibold text-primary">{{ $order->order_number }}</td>
                        <td>{{ $order->user->name ?? $order->shipping_name }}<br><small class="text-muted">{{ $order->shipping_email }}</small></td>
                        <td class="fw-bold">${{ number_format($order->total, 2) }}</td>
                        <td>
                            <div class="small">
                                @if($order->payment_method === 'cod') COD
                                @elseif($order->payment_method === 'bakong_khqr') KHQR
                                @else Bank Transfer @endif
                            </div>
                            <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($order->payment_status) }}</span>
                        </td>
                        <td><span class="badge bg-{{ $order->status_badge }}">{{ ucfirst($order->status) }}</span></td>
                        <td class="text-muted small">{{ $order->created_at->format('M d, Y') }}</td>
                        <td><button wire:click="openView({{ $order->id }})" class="btn btn-sm btn-outline-primary">View</button></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No orders found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $orders->links() }}</div>

    {{-- Order Detail Modal --}}
    @if($modal === 'view' && $viewOrder)
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.5);">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Order {{ $viewOrder->order_number }}</h5>
                    <button wire:click="closeModal" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="card border-0 bg-light rounded-3 p-3">
                                <h6 class="fw-bold mb-2">Customer</h6>
                                <p class="mb-1 fw-semibold">{{ $viewOrder->shipping_name }}</p>
                                <p class="mb-1 text-muted small">{{ $viewOrder->shipping_email }}</p>
                                <p class="mb-0 text-muted small">{{ $viewOrder->shipping_phone }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 bg-light rounded-3 p-3">
                                <h6 class="fw-bold mb-2">Shipping Address</h6>
                                <p class="mb-0 text-muted small">{{ $viewOrder->shipping_address }}, {{ $viewOrder->shipping_city }}</p>
                            </div>
                        </div>
                    </div>

                    <table class="table table-sm mb-3">
                        <thead class="table-light"><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
                        <tbody>
                            @foreach($viewOrder->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="fw-semibold">${{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr><td colspan="3" class="text-end text-muted">Subtotal</td><td>${{ number_format($viewOrder->subtotal, 2) }}</td></tr>
                            <tr><td colspan="3" class="text-end text-muted">Shipping</td><td>${{ number_format($viewOrder->shipping, 2) }}</td></tr>
                            <tr class="fw-bold"><td colspan="3" class="text-end">Total</td><td class="text-primary">${{ number_format($viewOrder->total, 2) }}</td></tr>
                        </tfoot>
                    </table>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Order Status</label>
                            <select wire:model="status" class="form-select">
                                @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                                    <option value="{{ $s }}">{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Payment Status</label>
                            <select wire:model="payment_status" class="form-select">
                                <option value="unpaid">Unpaid</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click="closeModal" class="btn btn-outline-secondary">Close</button>
                    <button wire:click="updateOrder" wire:loading.attr="disabled" class="btn btn-primary">
                        <span wire:loading.remove wire:target="updateOrder">Update Order</span>
                        <span wire:loading wire:target="updateOrder"><span class="spinner-border spinner-border-sm me-1"></span>Saving...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
