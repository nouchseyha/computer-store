@extends('layouts.app')
@section('title', 'Register - Computer TK Store')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-plus fa-3x text-primary mb-2"></i>
                        <h4 class="fw-bold">Create Account</h4>
                        <p class="text-muted small">Join Computer TK Store today</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Avatar Upload --}}
                        <div class="mb-4 text-center">
                            <div class="position-relative d-inline-block">
                                <img id="avatarPreview"
                                     src="https://ui-avatars.com/api/?name=User&background=1a73e8&color=fff&size=100"
                                     class="rounded-circle border"
                                     style="width:90px;height:90px;object-fit:cover;">
                                <label for="avatar"
                                       class="position-absolute bottom-0 end-0 btn btn-sm btn-primary rounded-circle p-1"
                                       style="width:28px;height:28px;cursor:pointer;" title="Upload photo">
                                    <i class="fas fa-camera" style="font-size:.7rem;"></i>
                                </label>
                            </div>
                            <input id="avatar" type="file" name="avatar" accept="image/*"
                                   class="d-none @error('avatar') is-invalid @enderror"
                                   onchange="previewAvatar(this)">
                            <div class="text-muted small mt-1">Profile photo (optional)</div>
                            @error('avatar')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Full Name</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   required autofocus placeholder="John Doe">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email Address</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}"
                                   class="form-control @error('email') is-invalid @enderror"
                                   required placeholder="you@example.com">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <input id="password" type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   required placeholder="Min. 8 characters">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                   class="form-control" required placeholder="Repeat password">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus me-2"></i>CREATE ACCOUNT
                            </button>
                        </div>
                    </form>

                    <hr class="my-3">
                    <div class="text-center">
                        <span class="text-muted small">Already have an account?</span>
                        <a href="{{ route('login') }}" class="ms-1 small fw-semibold">Sign in here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('avatarPreview').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
