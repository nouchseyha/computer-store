@extends('layouts.app')
@section('title', 'Sign In — Computer TK Store')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">

            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-3 mb-3"
                     style="width:52px;height:52px;background:var(--primary);">
                    <i class="fas fa-desktop text-white" style="font-size:1.2rem;"></i>
                </div>
                <h4 class="fw-bold mb-1">Welcome back</h4>
                <p class="text-muted small mb-0">Sign in to your account</p>
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

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input id="email" type="email" name="email"
                                   value="{{ old('email') }}"
                                   class="form-control @error('email') is-invalid @enderror"
                                   required autofocus autocomplete="username"
                                   placeholder="you@example.com">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="password" class="form-label mb-0">Password</label>
                                <a href="{{ route('password.request') }}"
                                   class="text-decoration-none small fw-semibold"
                                   style="color:var(--primary);">
                                    Forgot password?
                                </a>
                            </div>
                            <input id="password" type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   required autocomplete="current-password"
                                   placeholder="Enter your password">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-4 mt-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label small" for="remember">Keep me signed in</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                            <i class="fas fa-sign-in-alt me-2"></i>Sign In
                        </button>
                    </form>

                </div>
            </div>

            <p class="text-center text-muted small mt-3">
                Don't have an account?
                <a href="{{ route('register') }}" class="fw-semibold text-decoration-none" style="color:var(--primary);">
                    Create one
                </a>
            </p>

        </div>
    </div>
</div>
@endsection
