@extends('layouts.app')
@section('title', 'Scan to Pay')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 text-center">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <h3 class="fw-bold text-primary mb-1">Scan to Pay</h3>
                    <p class="text-muted small">Scan this KHQR with your ABA / ACLEDA / any Bakong-supported app.</p>

                    {{-- QR Code generated from the KHQR string --}}
                    <div class="my-4 p-3 bg-white border rounded-4 d-inline-block shadow-sm">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&ecc=M&data={{ urlencode($khqrString) }}"
                             alt="Bakong KHQR"
                             style="width:220px; height:220px;">
                    </div>

                    <div class="mb-4">
                        <span class="badge bg-light text-danger border py-2 px-3">
                            <i class="fas fa-clock me-1"></i> Expires in: <span id="timer">10:00</span>
                        </span>
                    </div>

                    <div class="bg-light p-3 rounded-3 mb-4 text-start">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Order Number</span>
                            <span class="fw-bold">#{{ $order->order_number }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Amount</span>
                            <span class="fw-bold text-danger fs-5">${{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>

                    <button type="button" id="verifyBtn" class="btn btn-primary w-100 py-3 fw-bold rounded-3">
                        <i class="fas fa-check-circle me-2"></i>I HAVE PAID
                    </button>

                    <div id="statusMsg" class="mt-3 small"></div>

                    <p class="mt-3 small text-muted">
                        <i class="fas fa-shield-alt text-success me-1"></i>Payment secured via Bakong KHQR
                    </p>
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('cart.index') }}" class="text-decoration-none text-muted small">
                    <i class="fas fa-arrow-left me-1"></i>Cancel & Return to Cart
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Countdown timer
    (function () {
        let seconds = 600; // 10 minutes
        const display = document.getElementById('timer');
        const interval = setInterval(function () {
            const m = String(Math.floor(seconds / 60)).padStart(2, '0');
            const s = String(seconds % 60).padStart(2, '0');
            display.textContent = m + ':' + s;
            if (--seconds < 0) {
                clearInterval(interval);
                display.textContent = 'EXPIRED';
                alert('Payment session expired. Please try again.');
                window.location.href = "{{ route('cart.index') }}";
            }
        }, 1000);
    })();

    // Verify payment
    document.getElementById('verifyBtn').addEventListener('click', function () {
        const btn = this;
        const msg = document.getElementById('statusMsg');

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Verifying...';
        msg.innerHTML = '<span class="text-muted">Checking with Bakong...</span>';

        fetch("{{ route('checkout.verify', $order->order_number) }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                msg.innerHTML = '<span class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i>Payment confirmed! Redirecting...</span>';
                setTimeout(() => {
                    window.location.href = "{{ route('checkout.success', $order->order_number) }}";
                }, 1000);
            } else {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check-circle me-2"></i>I HAVE PAID';
                msg.innerHTML = '<span class="text-danger"><i class="fas fa-times-circle me-1"></i>Payment not detected yet. Please wait a moment and try again.</span>';
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check-circle me-2"></i>I HAVE PAID';
            msg.innerHTML = '<span class="text-danger">Connection error. Please try again.</span>';
        });
    });
</script>
@endpush
@endsection
