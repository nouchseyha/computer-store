<div>
    {{-- Toolbar --}}
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div class="input-group" style="max-width:300px;">
            <span class="input-group-text bg-transparent"><i class="fas fa-search text-muted"></i></span>
            <input type="text" wire:model.live.debounce.300ms="search"
                   class="form-control border-start-0" placeholder="Search products...">
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i>Add Product
        </a>
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width:60px;">Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th style="width:100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>
                            @if($product->image && $product->image !== 'img/default-product.png')
                                <img src="{{ $product->image_url }}"
                                     width="48" height="48"
                                     style="object-fit:contain;background:var(--surface-2);border-radius:6px;padding:3px;">
                            @else
                                <div class="rounded d-flex align-items-center justify-content-center"
                                     style="width:48px;height:48px;background:var(--surface-2);">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $product->name }}</div>
                            @if($product->brand)
                                <small class="text-muted">{{ $product->brand }}</small>
                            @endif
                            @if($product->is_featured)
                                <span class="badge bg-warning text-dark ms-1" style="font-size:.65rem;">Featured</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $product->category->name }}</span>
                        </td>
                        <td>
                            @if($product->sale_price)
                                <div class="text-decoration-line-through text-muted small">${{ number_format($product->price, 2) }}</div>
                                <div class="fw-bold text-danger">${{ number_format($product->sale_price, 2) }}</div>
                            @else
                                <div class="fw-bold">${{ number_format($product->price, 2) }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="{{ $product->stock > 0 ? 'text-success' : 'text-danger' }} fw-semibold">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product) }}"
                               class="btn btn-sm btn-outline-primary me-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button wire:click="confirmDelete({{ $product->id }})"
                                    class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="fas fa-box-open fa-2x mb-2 d-block opacity-25"></i>
                            No products found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $products->links() }}</div>

    {{-- Delete Confirm Modal --}}
    @if($modal === 'delete')
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.5);">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-danger">
                        <i class="fas fa-trash me-2"></i>Delete Product
                    </h5>
                    <button wire:click="closeModal" class="btn-close"></button>
                </div>
                <div class="modal-body pt-2">
                    Are you sure? This cannot be undone.
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button wire:click="closeModal" class="btn btn-outline-secondary btn-sm">Cancel</button>
                    <button wire:click="delete" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash me-1"></i>Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
