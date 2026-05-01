<div>

    {{-- ══════════════════════════════════════════════════════
         KHQR PAYMENT SCREEN (shown after placing order)
    ══════════════════════════════════════════════════════ --}}
    @if($khqrString)
    <div class="row justify-content-center py-4">
        <div class="col-md-5 text-center">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-4">

                    {{-- Header --}}
                    <div class="mb-3">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b0/Bakong_logo.png/240px-Bakong_logo.png"
                             height="36" alt="Bakong" class="mb-2"
                             onerror="this.style.display='none'">
                        <h4 class="fw-bold text-primary mb-0">Scan to Pay</h4>
                        <p class="text-muted small mb-0">Use ABA / ACLEDA / Wing / any Bakong app</p>
                    </div>

                    {{-- QR Code --}}
                    <div class="my-3 p-3 bg-white border rounded-4 d-inline-block shadow-sm">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=230x230&ecc=M&data={{ urlencode($khqrString) }}"
                             alt="KHQR QR Code"
                             style="width:230px;height:230px;display:block;">
                    </div>

                    {{-- Order Info --}}
                    <div class="bg-light rounded-3 p-3 mb-4 text-start">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Order</span>
                            <span class="fw-bold small">#{{ $orderNumber }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Amount</span>
                            <span class="fw-bold fs-5 text-danger">${{ number_format($orderTotal, 2) }}</span>
                        </div>
                    </div>

                    {{-- Verify Button --}}
                    <button wire:click="verifyKhqr"
                            wire:loading.attr="disabled"
                            wire:target="verifyKhqr"
                            class="btn btn-success btn-lg w-100 fw-bold rounded-3 py-3">
                        <span wire:loading.remove wire:target="verifyKhqr">
                            <i class="fas fa-check-circle me-2"></i>I HAVE PAID
                        </span>
                        <span wire:loading wire:target="verifyKhqr">
                            <span class="spinner-border spinner-border-sm me-2"></span>Verifying with Bakong...
                        </span>
                    </button>

                    {{-- Status Messages --}}
                    @if($verifyStatus === 'not_found')
                        <div class="alert alert-warning mt-3 py-2 small mb-0">
                            <i class="fas fa-clock me-1"></i>
                            Payment not detected yet. Please wait a moment and try again.
                        </div>
                    @elseif($verifyStatus === 'error')
                        <div class="alert alert-danger mt-3 py-2 small mb-0">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            Payment gateway not configured. Contact support.
                        </div>
                    @endif

                    <p class="mt-3 mb-0 small text-muted">
                        <i class="fas fa-shield-alt text-success me-1"></i>
                        Secured &amp; encrypted via NBC Bakong KHQR
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
         CHECKOUT FORM
    ══════════════════════════════════════════════════════ --}}
    @else
    <h2 class="fw-bold mb-4">
        <i class="fas fa-shopping-bag me-2 text-primary"></i>Complete Your Order
    </h2>

    @if($errors->has('cart'))
        <div class="alert alert-danger">{{ $errors->first('cart') }}</div>
    @endif

    <div class="row g-4">

        {{-- LEFT: Shipping + Payment --}}
        <div class="col-lg-7">

            {{-- Shipping Details --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-truck me-2 text-primary"></i>Shipping Details
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">FULL NAME</label>
                            <input type="text" wire:model="shipping_name"
                                   class="form-control bg-light border-0 @error('shipping_name') is-invalid @enderror"
                                   placeholder="John Doe">
                            @error('shipping_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">EMAIL</label>
                            <input type="email" wire:model="shipping_email"
                                   class="form-control bg-light border-0 @error('shipping_email') is-invalid @enderror"
                                   placeholder="you@example.com">
                            @error('shipping_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">PHONE</label>
                            <input type="text" wire:model="shipping_phone"
                                   class="form-control bg-light border-0 @error('shipping_phone') is-invalid @enderror"
                                   placeholder="012 345 678">
                            @error('shipping_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">CITY / PROVINCE</label>
                            <input type="text" wire:model="shipping_city"
                                   class="form-control bg-light border-0 @error('shipping_city') is-invalid @enderror"
                                   placeholder="Phnom Penh">
                            @error('shipping_city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">ADDRESS</label>
                            <textarea wire:model="shipping_address" rows="2"
                                      class="form-control bg-light border-0 @error('shipping_address') is-invalid @enderror"
                                      placeholder="Street, House number..."></textarea>
                            @error('shipping_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">NOTES (OPTIONAL)</label>
                            <textarea wire:model="notes" rows="1"
                                      class="form-control bg-light border-0"
                                      placeholder="Delivery notes..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment Method — 2 options only --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-wallet me-2 text-primary"></i>Payment Method
                    </h5>

                    {{-- Option 1: COD --}}
                    <div class="mb-3">
                        <input class="btn-check" type="radio"
                               wire:model.live="payment_method"
                               id="pm_cod" value="cod">
                        <label class="btn btn-outline-light text-start w-100 p-3 rounded-3 border d-flex align-items-center
                                      {{ $payment_method === 'cod' ? 'border-success bg-success-subtle' : '' }}"
                               for="pm_cod">
                            <div class="p-2 rounded-circle me-3" style="background:#d1fae5;">
                                <i class="fas fa-money-bill-wave text-success fa-lg"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark">Cash on Delivery (COD)</div>
                                <div class="small text-muted">Pay cash when you receive your order</div>
                            </div>
                            @if($payment_method === 'cod')
                                <i class="fas fa-check-circle text-success ms-auto"></i>
                            @endif
                        </label>
                    </div>

                    {{-- Option 2: Bakong KHQR --}}
                    <div class="mb-2">
                        <input class="btn-check" type="radio"
                               wire:model.live="payment_method"
                               id="pm_khqr" value="bakong_khqr">
                        <label class="btn btn-outline-light text-start w-100 p-3 rounded-3 border d-flex align-items-center
                                      {{ $payment_method === 'bakong_khqr' ? 'border-primary bg-primary-subtle' : '' }}"
                               for="pm_khqr">
                            <div class="p-2 rounded-circle me-3" style="background:#dbeafe;">
                                <i class="fas fa-qrcode text-primary fa-lg"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark">Bakong KHQR</div>
                                <div class="small text-muted">Scan QR with ABA / ACLEDA / Wing / any bank app</div>
                            </div>
                            @if($payment_method === 'bakong_khqr')
                                <i class="fas fa-check-circle text-primary ms-auto"></i>
                            @endif
                        </label>
                    </div>

                    @if($payment_method === 'bakong_khqr')
                        <div class="alert alert-primary py-2 small border-0 rounded-3 mt-2 mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            A QR code will appear after you click "Place Order". Scan it to complete payment.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- RIGHT: Order Summary --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top:90px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Order Summary</h5>

                    {{-- Items --}}
                    <div class="mb-3" style="max-height:260px;overflow-y:auto;">
                        @foreach($cart as $item)
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                @if(!empty($item['image']))
                                    @php $imgSrc = str_starts_with($item['image'], 'http') ? $item['image'] : asset($item['image']); @endphp
                                    <img src="{{ $imgSrc }}" width="40" height="40"
                                         style="object-fit:contain;background:var(--surface-2);border-radius:6px;padding:2px;">
                                @endif
                                <div>
                                    <div class="small fw-semibold">{{ $item['name'] }}</div>
                                    <div class="text-muted" style="font-size:.75rem;">
                                        {{ $item['quantity'] }} × ${{ number_format($item['price'], 2) }}
                                    </div>
                                </div>
                            </div>
                            <span class="fw-bold small">${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                        </div>
                        @endforeach
                    </div>

                    {{-- Totals --}}
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-semibold">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center border-top pt-3 mb-4">
                        <span class="fw-bold fs-5">Total</span>
                        <span class="fw-bold fs-4 text-primary">${{ number_format($subtotal, 2) }}</span>
                    </div>

                    {{-- Place Order --}}
                    <button wire:click="placeOrder"
                            wire:loading.attr="disabled"
                            wire:target="placeOrder"
                            class="btn btn-primary btn-lg w-100 rounded-3 py-3 fw-bold">
                        <span wire:loading.remove wire:target="placeOrder">
                            @if($payment_method === 'bakong_khqr')
                                <i class="fas fa-qrcode me-2"></i>PAY WITH KHQR
                            @else
                                <i class="fas fa-check me-2"></i>PLACE ORDER
                            @endif
                        </span>
                        <span wire:loading wire:target="placeOrder">
                            <span class="spinner-border spinner-border-sm me-2"></span>Processing...
                        </span>
                    </button>

                    <a href="{{ route('cart.index') }}"
                       class="btn btn-link w-100 text-muted text-decoration-none small mt-2">
                        <i class="fas fa-chevron-left me-1"></i>Return to Cart
                    </a>
                </div>
            </div>
        </div>

    </div>
    @endif

</div>
