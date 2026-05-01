<div>
    <h2 class="fw-bold mb-4"><i class="fas fa-shopping-cart me-2 text-primary"></i>Shopping Cart</h2>

    @if(empty($cart))
        <div class="text-center py-5">
            <i class="fas fa-cart-arrow-down fa-5x text-muted mb-3"></i>
            <h4 class="text-muted">Your cart is empty</h4>
            <a href="{{ route('shop.index') }}" class="btn btn-primary mt-3">
                <i class="fas fa-store me-2"></i>Continue Shopping
            </a>
        </div>
    @else
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center">Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $id => $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            @if($item['image'])
                                                @php $imgSrc = str_starts_with($item['image'], 'http') ? $item['image'] : asset($item['image']); @endphp
                                                <img src="{{ $imgSrc }}" width="60" height="60"
                                                     style="object-fit:contain;background:var(--surface-2);border-radius:8px;padding:4px;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width:60px;height:60px;">
                                                    <i class="fas fa-laptop text-muted"></i>
                                                </div>
                                            @endif
                                            <div class="fw-semibold">{{ $item['name'] }}</div>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">${{ number_format($item['price'], 2) }}</td>
                                    <td class="text-center align-middle" style="width:130px;">
                                        <input type="number"
                                               value="{{ $item['quantity'] }}"
                                               min="1" max="99"
                                               wire:change="updateQty({{ $id }}, $event.target.value)"
                                               class="form-control form-control-sm text-center" style="width:70px;">
                                    </td>
                                    <td class="text-center align-middle fw-bold">${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                    <td class="text-center align-middle">
                                        <button wire:click="remove({{ $id }})" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('shop.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                </a>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Order Summary</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold fs-5">Total</span>
                        <span class="fw-bold fs-5 text-primary">${{ number_format($total, 2) }}</span>
                    </div>
                    <a href="{{ auth()->check() ? route('checkout.index') : route('login') }}" class="btn btn-warning btn-lg w-100">
                        <i class="fas fa-lock me-2"></i>
                        {{ auth()->check() ? 'Proceed to Checkout' : 'Login to Checkout' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
