<div>
    {{-- Header card --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4 d-flex align-items-center gap-4">
            @if(auth()->user()->avatar)
                <img src="{{ asset(auth()->user()->avatar) }}"
                     class="rounded-circle border flex-shrink-0"
                     style="width:64px;height:64px;object-fit:cover;">
            @else
                <div class="rounded-circle bg-danger d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                     style="width:64px;height:64px;font-size:1.6rem;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <h4 class="fw-bold mb-0">{{ auth()->user()->name }}</h4>
                <div class="text-muted small">{{ auth()->user()->email }}</div>
                <span class="badge bg-danger mt-1">Admin</span>
            </div>
            <div class="ms-auto text-end d-none d-md-block">
                <div class="text-muted small">Member since</div>
                <div class="fw-semibold">{{ auth()->user()->created_at->format('M d, Y') }}</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Profile Info --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-user-circle me-2 text-primary"></i>Profile Information
                    </h5>

                    @if(session('success'))
                        <div class="alert alert-success py-2">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    {{-- Avatar upload — plain form --}}
                    <div class="mb-4 d-flex align-items-center gap-3">
                        @if(auth()->user()->avatar)
                            <img id="adminAvatarImg" src="{{ asset(auth()->user()->avatar) }}"
                                 class="rounded-circle border"
                                 style="width:60px;height:60px;object-fit:cover;">
                        @else
                            <div id="adminAvatarInitial"
                                 class="rounded-circle bg-danger d-flex align-items-center justify-content-center text-white fw-bold"
                                 style="width:60px;height:60px;font-size:1.3rem;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <form action="{{ route('avatar.update') }}" method="POST"
                                  enctype="multipart/form-data" id="adminAvatarForm">
                                @csrf
                                <label class="btn btn-sm btn-outline-primary mb-1" style="cursor:pointer;">
                                    <i class="fas fa-camera me-1"></i>Change Photo
                                    <input type="file" name="avatar" accept="image/*" class="d-none"
                                           onchange="adminPreviewAndSubmit(this)">
                                </label>
                            </form>
                            @if(auth()->user()->avatar)
                                <form action="{{ route('avatar.remove') }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash me-1"></i>Remove
                                    </button>
                                </form>
                            @endif
                            <div class="text-muted small mt-1">JPG, PNG, WEBP — max 2MB</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Full Name</label>
                        <input type="text" wire:model="name"
                               class="form-control @error('name') is-invalid @enderror">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email" wire:model="email"
                               class="form-control @error('email') is-invalid @enderror">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Phone</label>
                        <input type="text" wire:model="phone"
                               class="form-control @error('phone') is-invalid @enderror"
                               placeholder="012 345 678">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button wire:click="saveProfile" wire:loading.attr="disabled" class="btn btn-primary">
                        <span wire:loading.remove wire:target="saveProfile">
                            <i class="fas fa-save me-2"></i>Save Profile
                        </span>
                        <span wire:loading wire:target="saveProfile">
                            <span class="spinner-border spinner-border-sm me-2"></span>Saving...
                        </span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Change Password --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-lock me-2 text-warning"></i>Change Password
                    </h5>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Current Password</label>
                        <input type="password" wire:model="current_password"
                               class="form-control @error('current_password') is-invalid @enderror"
                               placeholder="Enter current password">
                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">New Password</label>
                        <input type="password" wire:model="new_password"
                               class="form-control @error('new_password') is-invalid @enderror"
                               placeholder="Min. 8 characters">
                        @error('new_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Confirm New Password</label>
                        <input type="password" wire:model="new_password_confirm"
                               class="form-control @error('new_password_confirm') is-invalid @enderror"
                               placeholder="Repeat new password">
                        @error('new_password_confirm')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button wire:click="savePassword" wire:loading.attr="disabled" class="btn btn-warning">
                        <span wire:loading.remove wire:target="savePassword">
                            <i class="fas fa-key me-2"></i>Update Password
                        </span>
                        <span wire:loading wire:target="savePassword">
                            <span class="spinner-border spinner-border-sm me-2"></span>Updating...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function adminPreviewAndSubmit(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img  = document.getElementById('adminAvatarImg');
            const init = document.getElementById('adminAvatarInitial');
            if (img)  img.src = e.target.result;
            else if (init) init.outerHTML = '<img id="adminAvatarImg" src="' + e.target.result + '" class="rounded-circle border" style="width:60px;height:60px;object-fit:cover;">';
        };
        reader.readAsDataURL(input.files[0]);
        document.getElementById('adminAvatarForm').submit();
    }
}
</script>
@endpush
