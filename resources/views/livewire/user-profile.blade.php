<div>
    {{-- Header --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        @if(auth()->user()->avatar)
            <img src="{{ asset(auth()->user()->avatar) }}"
                 class="rounded-circle border"
                 style="width:64px;height:64px;object-fit:cover;">
        @else
            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold"
                 style="width:64px;height:64px;font-size:1.5rem;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        @endif
        <div>
            <h4 class="fw-bold mb-0">{{ auth()->user()->name }}</h4>
            <small class="text-muted">{{ auth()->user()->email }}</small>
        </div>
    </div>

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <button wire:click="$set('activeTab','profile')"
                    class="nav-link {{ $activeTab === 'profile' ? 'active' : '' }}">
                <i class="fas fa-user me-1"></i>Profile
            </button>
        </li>
        <li class="nav-item">
            <button wire:click="$set('activeTab','password')"
                    class="nav-link {{ $activeTab === 'password' ? 'active' : '' }}">
                <i class="fas fa-lock me-1"></i>Password
            </button>
        </li>
        <li class="nav-item">
            <button wire:click="$set('activeTab','orders')"
                    class="nav-link {{ $activeTab === 'orders' ? 'active' : '' }}">
                <i class="fas fa-box me-1"></i>My Orders
            </button>
        </li>
    </ul>

    {{-- ── Profile Tab ── --}}
    @if($activeTab === 'profile')
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-4">Personal Information</h5>

            @if(session('profile_success'))
                <div class="alert alert-success py-2">
                    <i class="fas fa-check-circle me-2"></i>{{ session('profile_success') }}
                </div>
            @endif

            {{-- Avatar — plain HTML form, no Livewire --}}
            <div class="mb-4 d-flex align-items-center gap-4">
                <div id="avatarWrap">
                    @if(auth()->user()->avatar)
                        <img id="avatarImg" src="{{ asset(auth()->user()->avatar) }}"
                             class="rounded-circle border"
                             style="width:80px;height:80px;object-fit:cover;">
                    @else
                        <div id="avatarInitial"
                             class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold"
                             style="width:80px;height:80px;font-size:1.8rem;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div>
                    {{-- Upload form --}}
                    <form action="{{ route('avatar.update') }}" method="POST"
                          enctype="multipart/form-data" id="avatarForm">
                        @csrf
                        <label class="btn btn-sm btn-outline-primary mb-1" style="cursor:pointer;">
                            <i class="fas fa-camera me-1"></i>Change Photo
                            <input type="file" name="avatar" accept="image/*" class="d-none"
                                   onchange="previewAndSubmit(this)">
                        </label>
                    </form>
                    @if(auth()->user()->avatar)
                        <form action="{{ route('avatar.remove') }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger d-block mt-1">
                                <i class="fas fa-trash me-1"></i>Remove
                            </button>
                        </form>
                    @endif
                    <div class="text-muted small mt-1">JPG, PNG, WEBP — max 2MB</div>
                </div>
            </div>

            {{-- Text fields via Livewire --}}
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Full Name</label>
                    <input type="text" wire:model="name"
                           class="form-control @error('name') is-invalid @enderror">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email Address</label>
                    <input type="email" wire:model="email"
                           class="form-control @error('email') is-invalid @enderror">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Phone Number</label>
                    <input type="text" wire:model="phone"
                           class="form-control @error('phone') is-invalid @enderror"
                           placeholder="012 345 678">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Member Since</label>
                    <input type="text" class="form-control bg-light"
                           value="{{ auth()->user()->created_at->format('M d, Y') }}" readonly>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Delivery Address</label>
                    <textarea wire:model="address" rows="2"
                              class="form-control @error('address') is-invalid @enderror"
                              placeholder="Your default delivery address..."></textarea>
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <button wire:click="saveProfile" wire:loading.attr="disabled" class="btn btn-primary px-4">
                        <span wire:loading.remove wire:target="saveProfile">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </span>
                        <span wire:loading wire:target="saveProfile">
                            <span class="spinner-border spinner-border-sm me-2"></span>Saving...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ── Password Tab ── --}}
    @if($activeTab === 'password')
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-4">Change Password</h5>

            @if(session('password_success'))
                <div class="alert alert-success py-2">
                    <i class="fas fa-check-circle me-2"></i>{{ session('password_success') }}
                </div>
            @endif

            <div class="row g-3" style="max-width:480px;">
                <div class="col-12">
                    <label class="form-label fw-semibold">Current Password</label>
                    <input type="password" wire:model="current_password"
                           class="form-control @error('current_password') is-invalid @enderror"
                           placeholder="Enter current password">
                    @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">New Password</label>
                    <input type="password" wire:model="new_password"
                           class="form-control @error('new_password') is-invalid @enderror"
                           placeholder="Min. 8 characters">
                    @error('new_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Confirm New Password</label>
                    <input type="password" wire:model="new_password_confirm"
                           class="form-control @error('new_password_confirm') is-invalid @enderror"
                           placeholder="Repeat new password">
                    @error('new_password_confirm')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <button wire:click="savePassword" wire:loading.attr="disabled" class="btn btn-warning px-4">
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
    @endif

    {{-- ── Orders Tab ── --}}
    @if($activeTab === 'orders')
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Recent Orders</h5>
                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            @if($orders->isEmpty())
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-box-open fa-3x mb-3 opacity-25"></i>
                    <p>No orders yet.</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-primary btn-sm">Start Shopping</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order #</th><th>Date</th><th>Total</th><th>Status</th><th>Payment</th><th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td class="fw-semibold text-primary">{{ $order->order_number }}</td>
                                <td class="text-muted small">{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="fw-bold">${{ number_format($order->total, 2) }}</td>
                                <td><span class="badge bg-{{ $order->status_badge }}">{{ ucfirst($order->status) }}</span></td>
                                <td>
                                    <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('orders.show', $order->order_number) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
function previewAndSubmit(input) {
    if (input.files && input.files[0]) {
        // Show preview immediately
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('avatarImg');
            const init = document.getElementById('avatarInitial');
            if (img) {
                img.src = e.target.result;
            } else if (init) {
                init.outerHTML = '<img id="avatarImg" src="' + e.target.result + '" class="rounded-circle border" style="width:80px;height:80px;object-fit:cover;">';
            }
        };
        reader.readAsDataURL(input.files[0]);
        // Submit the form
        document.getElementById('avatarForm').submit();
    }
}
</script>
@endpush
