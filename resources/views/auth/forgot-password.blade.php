@extends('layouts.app')
@section('title', 'Forgot Password — Computer TK Store')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">

            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-3 mb-3"
                     style="width:52px;height:52px;background:var(--accent);">
                    <i class="fas fa-key text-white" style="font-size:1.2rem;"></i>
                </div>
                <h4 class="fw-bold mb-1">Forgot your password?</h4>
                <p class="text-muted small mb-0">
                    Enter your email and we'll send a 6-digit OTP code.
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

                    <form method="POST" action="{{ route('password.otp.send') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="email" class="form-label">Email Address</label>
                            <input id="email" type="email" name="email"
                                   value="{{ old('email') }}"
                                   class="form-control @error('email') is-invalid @enderror"
                                   required autofocus placeholder="you@example.com">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                            <i class="fas fa-paper-plane me-2"></i>Send OTP Code
                        </button>
                    </form>

                </div>
            </div>

            <p class="text-center text-muted small mt-3">
                Remember your password?
                <a href="{{ route('login') }}" class="fw-semibold text-decoration-none" style="color:var(--primary);">
                    Back to Sign In
                </a>
            </p>

        </div>
    </div>
</div>
@endsection
