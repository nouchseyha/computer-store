@extends('layouts.app')
@section('title', 'Reset Password — Computer TK Store')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">

            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-3 mb-3"
                     style="width:52px;height:52px;background:var(--primary);">
                    <i class="fas fa-lock text-white" style="font-size:1.2rem;"></i>
                </div>
                <h4 class="fw-bold mb-1">Set new password</h4>
                <p class="text-muted small mb-0">Choose a strong password for your account.</p>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius:18px;">
                <div class="card-body p-4">

                    @if($errors->any())
                        <div class="alert alert-danger py-2 small">
                            @foreach($errors->all() as $error)
                                <div><i class="fas fa-exclamation-circle me-1"></i>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input id="email" type="email" name="email"
                                   value="{{ old('email', $request->email) }}"
                                   class="form-control @error('email') is-invalid @enderror"
                                   required autofocus autocomplete="username"
                                   placeholder="you@example.com">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input id="password" type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   required autocomplete="new-password"
                                   placeholder="Min. 8 characters">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                   class="form-control"
                                   required autocomplete="new-password"
                                   placeholder="Repeat new password">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                            <i class="fas fa-check me-2"></i>Reset Password
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
