<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Categories</h5>
        <button wire:click="openCreate" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>Add Category</button>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Name</th><th>Slug</th><th>Products</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                    <tr>
                        <td>{{ $cat->id }}</td>
                        <td class="fw-semibold">{{ $cat->name }}</td>
                        <td class="text-muted small">{{ $cat->slug }}</td>
                        <td><span class="badge bg-primary">{{ $cat->products_count }}</span></td>
                        <td><span class="badge {{ $cat->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $cat->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td>
                            <button wire:click="openEdit({{ $cat->id }})" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></button>
                            <button wire:click="openDelete({{ $cat->id }})" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No categories found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $categories->links() }}</div>

    @if($modal === 'create' || $modal === 'edit')
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.5);">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">{{ $modal === 'create' ? 'Add Category' : 'Edit Category' }}</h5>
                    <button wire:click="closeModal" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name *</label>
                        <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea wire:model="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" wire:model="is_active" class="form-check-input" id="cat_active">
                        <label class="form-check-label" for="cat_active">Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click="closeModal" class="btn btn-outline-secondary">Cancel</button>
                    <button wire:click="save" wire:loading.attr="disabled" class="btn btn-primary">
                        <span wire:loading.remove wire:target="save">Save</span>
                        <span wire:loading wire:target="save"><span class="spinner-border spinner-border-sm me-1"></span>Saving...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($modal === 'delete')
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.5);">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Delete Category</h5><button wire:click="closeModal" class="btn-close"></button></div>
                <div class="modal-body">Delete this category? Products in it will lose their category.</div>
                <div class="modal-footer">
                    <button wire:click="closeModal" class="btn btn-outline-secondary">Cancel</button>
                    <button wire:click="delete" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
