<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Users</h5>
        <button wire:click="openCreate" class="btn btn-primary btn-sm">
            <i class="fas fa-user-plus me-1"></i>Add User
        </button>
    </div>

    <div class="mb-3">
        <input type="text" wire:model.live.debounce.300ms="search"
               class="form-control" placeholder="Search by name or email...">
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Orders</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="text-muted small">{{ $user->id }}</td>
                        <td>
                            <div class="fw-semibold">{{ $user->name }}</div>
                            @if($user->id === auth()->id())
                                <span class="badge bg-info" style="font-size:.65rem;">You</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-primary">{{ $user->orders_count }}</span>
                        </td>
                        <td>
                            {{-- Click to toggle role (disabled for self) --}}
                            @if($user->id !== auth()->id())
                                <button wire:click="toggleRole({{ $user->id }})"
                                        class="badge border-0 {{ $user->is_admin ? 'bg-danger' : 'bg-secondary' }}"
                                        title="Click to toggle role"
                                        style="cursor:pointer;">
                                    {{ $user->is_admin ? 'Admin' : 'Customer' }}
                                </button>
                            @else
                                <span class="badge bg-danger">Admin</span>
                            @endif
                        </td>
                        {{-- Online status --}}
                        <td>
                            @if($user->isOnline())
                                <span class="d-inline-flex align-items-center gap-1"
                                      style="font-size:.78rem;font-weight:600;color:#22c55e;">
                                    <span style="width:8px;height:8px;border-radius:50%;background:#22c55e;
                                                 box-shadow:0 0 0 2px rgba(34,197,94,.25);display:inline-block;
                                                 animation:pulse 2s infinite;"></span>
                                    Online
                                </span>
                            @else
                                <span class="d-inline-flex align-items-center gap-1 text-muted"
                                      style="font-size:.78rem;">
                                    <span style="width:8px;height:8px;border-radius:50%;background:var(--border);display:inline-block;"></span>
                                    {{ $user->lastSeenLabel() }}
                                </span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <button wire:click="openEdit({{ $user->id }})"
                                    class="btn btn-sm btn-outline-primary me-1"
                                    {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="openDelete({{ $user->id }})"
                                    class="btn btn-sm btn-outline-danger"
                                    {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No users found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="text-muted" style="font-size:.78rem;">
            @if($users->total() > 0)
                Showing <strong>{{ $users->firstItem() }}</strong>–<strong>{{ $users->lastItem() }}</strong>
                of <strong>{{ $users->total() }}</strong> results
            @else
                No results
            @endif
        </div>
        <div>{{ $users->links() }}</div>
    </div>

    {{-- ── Create / Edit Modal ── --}}
    @if($modal === 'create' || $modal === 'edit')
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.5);">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        {{ $modal === 'create' ? 'Add New User' : 'Edit User' }}
                    </h5>
                    <button wire:click="closeModal" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    {{-- Honeypot fields to prevent browser autofill --}}
                    <input type="text" style="display:none;" autocomplete="username" tabindex="-1">
                    <input type="password" style="display:none;" autocomplete="new-password" tabindex="-1">

                    <div class="mb-3">
                        <input type="text" wire:model="name"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="John Doe">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" wire:model="email"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="john@example.com">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Password {{ $modal === 'edit' ? '<span class="text-muted fw-normal">(leave blank to keep current)</span>' : '*' }}
                        </label>
                        <div class="input-group">
                            <input type="password"
                                   wire:model.defer="password"
                                   id="admin_user_password"
                                   autocomplete="new-password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="{{ $modal === 'edit' ? 'Leave blank to keep current' : 'Min. 8 characters' }}">
                            <button type="button"
                                    class="btn btn-outline-secondary"
                                    onclick="const f=document.getElementById('admin_user_password');f.type=f.type==='password'?'text':'password';">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        @if($modal === 'edit')
                            <div class="text-muted small mt-1">
                                <i class="fas fa-info-circle me-1"></i>Only fill this if you want to change the password.
                            </div>
                        @endif
                    </div>

                    <div class="mb-1">
                        <label class="form-label">Role</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                       wire:model="is_admin" id="role_customer" :value="false" value="0">
                                <label class="form-check-label" for="role_customer">
                                    <span class="badge bg-secondary">Customer</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                       wire:model="is_admin" id="role_admin" :value="true" value="1">
                                <label class="form-check-label" for="role_admin">
                                    <span class="badge bg-danger">Admin</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click="closeModal" class="btn btn-outline-secondary">Cancel</button>
                    <button wire:click="save" wire:loading.attr="disabled" class="btn btn-primary">
                        <span wire:loading.remove wire:target="save">Save</span>
                        <span wire:loading wire:target="save">
                            <span class="spinner-border spinner-border-sm me-1"></span>Saving...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ── Delete Confirm Modal ── --}}
    @if($modal === 'delete')
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.5);">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-danger">Delete User</h5>
                    <button wire:click="closeModal" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this user? All their data will be permanently removed.</p>
                </div>
                <div class="modal-footer">
                    <button wire:click="closeModal" class="btn btn-outline-secondary">Cancel</button>
                    <button wire:click="delete" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
