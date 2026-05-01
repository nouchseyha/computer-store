@extends('layouts.admin')
@section('title', 'Edit Product')
@section('content')
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-4">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h5 class="fw-bold mb-0">Edit: {{ $product->name }}</h5>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">

                {{-- Name --}}
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Product Name *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $product->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Category --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Category *</label>
                    <select name="category_id" class="form-select" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Price --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Price *</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="price" class="form-control"
                               value="{{ old('price', $product->price) }}" step="0.01" min="0" required>
                    </div>
                </div>

                {{-- Sale Price --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Sale Price <span class="text-muted fw-normal">(optional)</span></label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="sale_price" class="form-control"
                               value="{{ old('sale_price', $product->sale_price) }}" step="0.01" min="0">
                    </div>
                </div>

                {{-- Stock --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Stock *</label>
                    <input type="number" name="stock" class="form-control"
                           value="{{ old('stock', $product->stock) }}" min="0" required>
                </div>

                {{-- Brand --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Brand</label>
                    <input type="text" name="brand" class="form-control" value="{{ old('brand', $product->brand) }}">
                </div>

                {{-- SKU --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">SKU</label>
                    <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror"
                           value="{{ old('sku', $product->sku) }}">
                    @error('sku')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Image Upload --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Product Image</label>

                    {{-- Current image preview --}}
                    <div id="imagePreview" class="mb-2">
                        @if($product->image && $product->image !== 'img/default-product.png')
                            <img id="previewImg" src="{{ $product->image_url }}"
                                 height="80" class="rounded"
                                 style="object-fit:contain;background:var(--surface-2);padding:4px;">
                        @else
                            <img id="previewImg" src="" height="80" class="rounded d-none"
                                 style="object-fit:contain;background:var(--surface-2);padding:4px;">
                        @endif
                    </div>

                    <input type="file" name="image_file" id="imageFile"
                           class="form-control @error('image_file') is-invalid @enderror"
                           accept="image/jpeg,image/png,image/webp"
                           onchange="previewImage(this)">
                    @error('image_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">Leave blank to keep current. JPG, PNG, WEBP — max 2MB</small>
                </div>

                {{-- Short Description --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Short Description</label>
                    <input type="text" name="short_description" class="form-control"
                           value="{{ old('short_description', $product->short_description) }}">
                </div>

                {{-- Description --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Full Description</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
                </div>

                {{-- Flags --}}
                <div class="col-md-6">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1"
                               {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active (visible in store)</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check">
                        <input type="checkbox" name="is_featured" class="form-check-input" id="is_featured" value="1"
                               {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">Featured Product</label>
                    </div>
                </div>

                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-2"></i>Update Product
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
    const img = document.getElementById('previewImg');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { img.src = e.target.result; img.classList.remove('d-none'); };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
