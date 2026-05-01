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

    <div class="mt-3">{{ $users->links() }}</div>

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
                    <div class="mb-3">
                        <label class="form-label">Full Name *</label>
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
                            Password {{ $modal === 'edit' ? '(leave blank to keep current)' : '*' }}
                        </label>
                        <input type="password" wire:model="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="{{ $modal === 'edit' ? 'Leave blank to keep current' : 'Min. 8 characters' }}">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
