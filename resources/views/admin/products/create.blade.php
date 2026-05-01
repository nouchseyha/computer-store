@extends('layouts.admin')
@section('title', 'Add Product')
@section('content')
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-4">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h5 class="fw-bold mb-0">Add New Product</h5>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">

                {{-- Name --}}
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Product Name *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" required placeholder="e.g. Dell XPS 15">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Category --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Category *</label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Price --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Price *</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                               value="{{ old('price') }}" step="0.01" min="0" required placeholder="0.00">
                    </div>
                    @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Sale Price --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Sale Price <span class="text-muted fw-normal">(optional)</span></label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="sale_price" class="form-control"
                               value="{{ old('sale_price') }}" step="0.01" min="0" placeholder="0.00">
                    </div>
                </div>

                {{-- Stock --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Stock *</label>
                    <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                           value="{{ old('stock', 0) }}" min="0" required>
                    @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Brand --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Brand</label>
                    <input type="text" name="brand" class="form-control" value="{{ old('brand') }}" placeholder="e.g. Dell">
                </div>

                {{-- SKU --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">SKU</label>
                    <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror"
                           value="{{ old('sku') }}" placeholder="e.g. DELL-XPS15">
                    @error('sku')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Image Upload --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Product Image</label>
                    <input type="file" name="image_file" id="imageFile"
                           class="form-control @error('image_file') is-invalid @enderror"
                           accept="image/jpeg,image/png,image/webp"
                           onchange="previewImage(this)">
                    @error('image_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">JPG, PNG, WEBP — max 2MB</small>
                    <div id="imagePreview" class="mt-2 d-none">
                        <img id="previewImg" src="" height="80" class="rounded"
                             style="object-fit:contain;background:var(--surface-2);padding:4px;">
                    </div>
                </div>

                {{-- Short Description --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Short Description</label>
                    <input type="text" name="short_description" class="form-control"
                           value="{{ old('short_description') }}" placeholder="One-line summary">
                </div>

                {{-- Description --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Full Description</label>
                    <textarea name="description" class="form-control" rows="4"
                              placeholder="Detailed product description...">{{ old('description') }}</textarea>
                </div>

                {{-- Flags --}}
                <div class="col-md-6">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" checked>
                        <label class="form-check-label" for="is_active">Active (visible in store)</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check">
                        <input type="checkbox" name="is_featured" class="form-check-input" id="is_featured" value="1">
                        <label class="form-check-label" for="is_featured">Featured Product</label>
                    </div>
                </div>

                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-2"></i>Save Product
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const img     = document.getElementById('previewImg');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { img.src = e.target.result; preview.classList.remove('d-none'); };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.classList.add('d-none');
    }
}
</script>
@endpush
@endsection
