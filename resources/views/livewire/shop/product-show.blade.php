<div>
    @if(session('cart_success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm"
             style="border-left:4px solid #1e8e3e !important;border-radius:10px;">
            <i class="fas fa-check-circle me-2 text-success"></i>{{ session('cart_success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="font-size:.83rem;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop.index') }}" class="text-decoration-none">Shop</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}"
                   class="text-decoration-none">{{ $product->category->name }}</a>
            </li>
            <li class="breadcrumb-item active text-muted">{{ Str::limit($product->name, 30) }}</li>
        </ol>
    </nav>

    {{-- Main Product Section --}}
    <div class="row g-4 mb-5">

        {{-- Image --}}
        <div class="col-md-5">
            <div class="card border-0 rounded-4 shadow-sm overflow-hidden"
                 style="background:var(--surface-2);">
                @if($product->image)
                    <img src="{{ $product->image_url }}"
                         alt="{{ $product->name }}"
                         class="w-100"
                         style="height:380px;object-fit:contain;padding:24px;">
                @else
                    <div class="d-flex align-items-center justify-content-center"
                         style="height:380px;color:var(--text-muted);">
                        <i class="fas fa-laptop fa-6x opacity-15"></i>
                    </div>
                @endif
            </div>
        </div>

        {{-- Info --}}
        <div class="col-md-7">
            <div class="h-100 d-flex flex-column">

                {{-- Brand + badges --}}
                <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                    @if($product->brand)
                        <span class="badge rounded-pill"
                              style="background:var(--surface-2);color:var(--text-muted);font-size:.75rem;font-weight:600;">
                            {{ $product->brand }}
                        </span>
                    @endif
                    <span class="badge rounded-pill"
                          style="background:var(--surface-2);color:var(--text-muted);font-size:.75rem;">
                        {{ $product->category->name }}
                    </span>
                    @if($product->is_featured)
                        <span class="badge rounded-pill" style="background:#ff6b00;color:#fff;font-size:.75rem;">
                            ⭐ Featured
                        </span>
                    @endif
                </div>

                {{-- Name --}}
                <h1 class="fw-bold mb-2" style="font-size:1.6rem;line-height:1.25;">
                    {{ $product->name }}
                </h1>

                @if($product->short_description)
                    <p class="text-muted mb-3" style="font-size:.92rem;">{{ $product->short_description }}</p>
                @endif

                {{-- Price --}}
                <div class="mb-3 d-flex align-items-baseline gap-2">
                    @if($product->sale_price)
                        <span style="font-size:2rem;font-weight:800;color:var(--accent);">
                            ${{ number_format($product->sale_price, 2) }}
                        </span>
                        <span class="text-muted text-decoration-line-through" style="font-size:1.1rem;">
                            ${{ number_format($product->price, 2) }}
                        </span>
                        <span class="badge" style="background:#ef4444;color:#fff;font-size:.8rem;">
                            -{{ round((1 - $product->sale_price / $product->price) * 100) }}% OFF
                        </span>
                    @else
                        <span style="font-size:2rem;font-weight:800;color:var(--primary);">
                            ${{ number_format($product->price, 2) }}
                        </span>
                    @endif
                </div>

                {{-- Stock status --}}
                <div class="mb-3">
                    @if($product->stock > 0)
                        <span class="d-inline-flex align-items-center gap-1 px-3 py-1 rounded-pill"
                              style="background:#d1fae5;color:#065f46;font-size:.82rem;font-weight:600;">
                            <i class="fas fa-check-circle"></i>
                            In Stock — {{ $product->stock }} available
                        </span>
                    @else
                        <span class="d-inline-flex align-items-center gap-1 px-3 py-1 rounded-pill"
                              style="background:#fee2e2;color:#991b1b;font-size:.82rem;font-weight:600;">
                            <i class="fas fa-times-circle"></i>
                            Out of Stock
                        </span>
                    @endif
                </div>

                @if($product->sku)
                    <div class="text-muted mb-3" style="font-size:.8rem;">SKU: {{ $product->sku }}</div>
                @endif

                <hr style="border-color:var(--border);">

                {{-- Add to cart --}}
                @if($product->stock > 0)
                    <div class="d-flex gap-2 align-items-center mt-2">
                        <div class="d-flex align-items-center border rounded-3 overflow-hidden"
                             style="border-color:var(--border) !important;">
                            <button type="button"
                                    onclick="const i=document.getElementById('qty');if(i.value>1)i.value=parseInt(i.value)-1;i.dispatchEvent(new Event('input'))"
                                    class="btn btn-sm px-3 border-0"
                                    style="background:var(--surface-2);">−</button>
                            <input type="number" id="qty" wire:model="quantity"
                                   min="1" max="{{ $product->stock }}"
                                   class="form-control border-0 text-center fw-bold"
                                   style="width:56px;background:var(--surface);box-shadow:none;">
                            <button type="button"
                                    onclick="const i=document.getElementById('qty');if(i.value<{{ $product->stock }})i.value=parseInt(i.value)+1;i.dispatchEvent(new Event('input'))"
                                    class="btn btn-sm px-3 border-0"
                                    style="background:var(--surface-2);">+</button>
                        </div>

                        <button wire:click="addToCart"
                                wire:loading.attr="disabled"
                                wire:target="addToCart"
                                class="btn btn-lg fw-bold flex-grow-1 rounded-3"
                                style="background:#ff6b00;border:none;color:#fff;">
                            <span wire:loading.remove wire:target="addToCart">
                                <i class="fas fa-cart-plus me-2"></i>Add to Cart
                            </span>
                            <span wire:loading wire:target="addToCart">
                                <span class="spinner-border spinner-border-sm me-2"></span>Adding...
                            </span>
                        </button>
                    </div>

                    <a href="{{ route('cart.index') }}"
                       class="btn btn-primary btn-lg w-100 rounded-3 mt-2 fw-bold">
                        <i class="fas fa-bolt me-2"></i>Buy Now
                    </a>
                @else
                    <button class="btn btn-lg w-100 rounded-3 fw-bold mt-2" disabled
                            style="background:var(--surface-2);color:var(--text-muted);border:1px solid var(--border);">
                        Out of Stock
                    </button>
                @endif

            </div>
        </div>
    </div>

    {{-- Description --}}
    @if($product->description)
    <div class="card border-0 rounded-4 shadow-sm mb-5">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3">
                <i class="fas fa-align-left me-2 text-primary"></i>Product Description
            </h5>
            <div class="text-muted" style="line-height:1.8;font-size:.93rem;">
                {!! nl2br(e($product->description)) !!}
            </div>
        </div>
    </div>
    @endif

    {{-- Related Products --}}
    @if($related->count())
    <div class="mb-4">
        <h5 class="fw-bold mb-3" style="border-left:4px solid #ff6b00;padding-left:12px;">
            Related Products
        </h5>
        <div class="row g-3">
            @foreach($related as $rel)
            <div class="col-6 col-md-3">
                <div class="shop-card h-100">
                    <div class="shop-card-img">
                        <a href="{{ route('shop.show', $rel->slug) }}">
                            @if($rel->image)
                                <img src="{{ $rel->image_url }}" alt="{{ $rel->name }}">
                            @else
                                <div class="shop-card-img-placeholder">
                                    <i class="fas fa-laptop fa-2x opacity-25"></i>
                                </div>
                            @endif
                        </a>
                    </div>
                    <div class="shop-card-body">
                        <a href="{{ route('shop.show', $rel->slug) }}" class="shop-card-title">
                            {{ $rel->name }}
                        </a>
                        <div class="shop-card-price">
                            @if($rel->sale_price)
                                <span class="shop-price-old">${{ number_format($rel->price, 2) }}</span>
                                <span class="shop-price-sale">${{ number_format($rel->sale_price, 2) }}</span>
                            @else
                                <span class="shop-price">${{ number_format($rel->price, 2) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

@push('styles')
<style>
    .shop-card {
        background: var(--surface);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,.06);
        display: flex;
        flex-direction: column;
        transition: transform .2s, box-shadow .2s;
    }
    .shop-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(0,0,0,.12); }
    .shop-card-img { background: var(--surface-2); }
    .shop-card-img a { display: block; }
    .shop-card-img img { width:100%; height:150px; object-fit:contain; padding:12px; transition:transform .3s; }
    .shop-card:hover .shop-card-img img { transform: scale(1.04); }
    .shop-card-img-placeholder { height:150px; display:flex; align-items:center; justify-content:center; color:var(--text-muted); }
    .shop-card-body { padding:12px 14px 14px; display:flex; flex-direction:column; flex:1; gap:4px; }
    .shop-card-title { font-size:.85rem; font-weight:600; color:var(--text); text-decoration:none; line-height:1.35; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
    .shop-card-title:hover { color:var(--primary); }
    .shop-card-price { margin-top:auto; padding-top:6px; }
    .shop-price { font-size:1rem; font-weight:700; color:var(--primary); }
    .shop-price-sale { font-size:1rem; font-weight:700; color:var(--accent); }
    .shop-price-old { font-size:.8rem; color:var(--text-muted); text-decoration:line-through; margin-right:4px; }
</style>
@endpush
</div>
