@extends('layouts.app')
@section('title', 'Enter OTP — Computer TK Store')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">

            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-3 mb-3"
                     style="width:52px;height:52px;background:var(--primary);">
                    <i class="fas fa-shield-alt text-white" style="font-size:1.2rem;"></i>
                </div>
                <h4 class="fw-bold mb-1">Enter OTP Code</h4>
                <p class="text-muted small mb-0">
                    We sent a 6-digit code to<br>
                    <strong>{{ session('otp_email') }}</strong>
                </p>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius:18px;">
                <div class="card-body p-4">

                    @if(session('status'))
                        <div class="alert alert-success py-2 small">
                            <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger py-2 small">
                            @foreach($errors->all() as $error)
                                <div><i class="fas fa-exclamation-circle me-1"></i>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.otp.verify') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label">6-Digit OTP Code</label>
                            <input type="text" name="otp"
                                   class="form-control text-center fw-bold @error('otp') is-invalid @enderror"
                                   style="font-size:1.8rem;letter-spacing:10px;font-family:'Courier New',monospace;"
                                   maxlength="6" inputmode="numeric" pattern="[0-9]{6}"
                                   placeholder="000000" autofocus autocomplete="off">
                            @error('otp')<div class="invalid-feedback text-center">{{ $message }}</div>@enderror
                            <div class="text-muted small text-center mt-2">
                                <i class="fas fa-clock me-1"></i>Code expires in 10 minutes
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                            <i class="fas fa-check me-2"></i>Verify OTP
                        </button>
                    </form>

                </div>
            </div>

            <p class="text-center text-muted small mt-3">
                Didn't receive the code?
                <a href="{{ route('password.request') }}" class="fw-semibold text-decoration-none" style="color:var(--primary);">
                    Resend
                </a>
            </p>

        </div>
    </div>
</div>
@endsection
