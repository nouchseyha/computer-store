<div>
    @if(session('cart_success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm"
             style="border-left:4px solid #1e8e3e !important;border-radius:10px;">
            <i class="fas fa-check-circle me-2 text-success"></i>{{ session('cart_success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- ── Sidebar ── --}}
        <div class="col-lg-3 d-none d-lg-block">
            <div class="sticky-top" style="top:80px;">

                {{-- Filter Card --}}
                <div class="card border-0 rounded-4 shadow-sm mb-3">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <span class="fw-bold" style="font-size:1rem;">
                                <i class="fas fa-filter me-2" style="color:var(--primary);"></i>Filters
                            </span>
                            <button wire:click="clearFilters"
                                    class="btn btn-link btn-sm p-0 text-decoration-none"
                                    style="color:var(--text-muted);font-size:.8rem;">
                                Reset all
                            </button>
                        </div>

                        {{-- Search --}}
                        <div class="mb-4">
                            <label class="filter-label">Search</label>
                            <div class="position-relative">
                                <i class="fas fa-search position-absolute"
                                   style="left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:.8rem;"></i>
                                <input type="text" wire:model.live.debounce.400ms="search"
                                       class="form-control ps-4"
                                       style="border-radius:10px;font-size:.88rem;"
                                       placeholder="Search products...">
                            </div>
                        </div>

                        {{-- Category --}}
                        <div class="mb-4">
                            <label class="filter-label">Category</label>
                            <div class="d-flex flex-column gap-1">
                                <button wire:click="$set('category','')"
                                        class="filter-pill {{ $category === '' ? 'active' : '' }}">
                                    All Categories
                                </button>
                                @foreach($categories as $cat)
                                    <button wire:click="$set('category','{{ $cat->slug }}')"
                                            class="filter-pill {{ $category === $cat->slug ? 'active' : '' }}">
                                        {{ $cat->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- Brand --}}
                        @if($brands->count())
                        <div class="mb-4">
                            <label class="filter-label">Brand</label>
                            <select wire:model.live="brand" class="form-select form-select-sm"
                                    style="border-radius:10px;">
                                <option value="">All Brands</option>
                                @foreach($brands as $b)
                                    <option value="{{ $b }}">{{ $b }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        {{-- Sort --}}
                        <div>
                            <label class="filter-label">Sort By</label>
                            <select wire:model.live="sort" class="form-select form-select-sm"
                                    style="border-radius:10px;">
                                <option value="latest">Newest First</option>
                                <option value="price_asc">Price: Low → High</option>
                                <option value="price_desc">Price: High → Low</option>
                                <option value="name">Name A–Z</option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ── Products ── --}}
        <div class="col-lg-9">

            {{-- Mobile filter bar --}}
            <div class="d-flex d-lg-none gap-2 mb-3 overflow-auto pb-1">
                <select wire:model.live="category" class="form-select form-select-sm flex-shrink-0" style="width:auto;border-radius:20px;">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                <select wire:model.live="sort" class="form-select form-select-sm flex-shrink-0" style="width:auto;border-radius:20px;">
                    <option value="latest">Newest</option>
                    <option value="price_asc">Price ↑</option>
                    <option value="price_desc">Price ↓</option>
                    <option value="name">A–Z</option>
                </select>
                <button wire:click="clearFilters" class="btn btn-sm btn-outline-secondary flex-shrink-0" style="border-radius:20px;">
                    <i class="fas fa-times me-1"></i>Clear
                </button>
            </div>

            {{-- Toolbar --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <span class="fw-semibold" style="font-size:.95rem;">
                        @if($search) "<em>{{ $search }}</em>"
                        @elseif($category) {{ ucfirst(str_replace('-', ' ', $category)) }}
                        @else All Products
                        @endif
                    </span>
                    <span class="text-muted small ms-1">· {{ $products->total() }} items</span>
                </div>
                <div wire:loading class="text-muted small d-flex align-items-center gap-1">
                    <span class="spinner-border spinner-border-sm"></span>
                </div>
            </div>

            @if($products->count())
                <div class="row g-3" wire:loading.class="opacity-50" wire:loading.class.delay="opacity-50">
                    @foreach($products as $product)
                    <div class="col-6 col-md-4 col-xl-3">
                        <div class="shop-card h-100">

                            {{-- Image area --}}
                            <div class="shop-card-img position-relative">
                                <a href="{{ route('shop.show', $product->slug) }}">
                                    @if($product->image)
                                        <img src="{{ $product->image_url }}"
                                             alt="{{ $product->name }}"
                                             loading="lazy">
                                    @else
                                        <div class="shop-card-img-placeholder">
                                            <i class="fas fa-laptop fa-2x opacity-25"></i>
                                        </div>
                                    @endif
                                </a>

                                {{-- Top-left badges --}}
                                <div class="position-absolute top-0 start-0 p-2 d-flex flex-column gap-1">
                                    @if($product->sale_price)
                                        <span class="shop-badge shop-badge-sale">
                                            -{{ round((1 - $product->sale_price / $product->price) * 100) }}%
                                        </span>
                                    @endif
                                    @if($product->is_featured)
                                        <span class="shop-badge shop-badge-featured">⭐</span>
                                    @endif
                                </div>

                                @if($product->stock === 0)
                                    <div class="shop-card-oos">Out of Stock</div>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="shop-card-body">
                                <div class="shop-card-meta">
                                    {{ $product->category->name ?? '' }}
                                    @if($product->brand) · {{ $product->brand }} @endif
                                </div>

                                <a href="{{ route('shop.show', $product->slug) }}" class="shop-card-title">
                                    {{ Str::limit($product->name, 48) }}
                                </a>

                                <div class="shop-card-price">
                                    @if($product->sale_price)
                                        <span class="shop-price-old">${{ number_format($product->price, 2) }}</span>
                                        <span class="shop-price-sale">${{ number_format($product->sale_price, 2) }}</span>
                                    @else
                                        <span class="shop-price">${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>

                                @if($product->stock > 0)
                                    <button wire:click="addToCart({{ $product->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="addToCart({{ $product->id }})"
                                            class="shop-btn-cart">
                                        <span wire:loading.remove wire:target="addToCart({{ $product->id }})">
                                            <i class="fas fa-cart-plus me-1"></i>Add to Cart
                                        </span>
                                        <span wire:loading wire:target="addToCart({{ $product->id }})">
                                            <span class="spinner-border spinner-border-sm"></span>
                                        </span>
                                    </button>
                                @else
                                    <button class="shop-btn-oos" disabled>Out of Stock</button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $products->links() }}
                </div>

            @else
                <div class="text-center py-5">
                    <div class="mb-3" style="font-size:3rem;opacity:.2;">🔍</div>
                    <h5 class="fw-bold">No products found</h5>
                    <p class="text-muted small">Try different filters or search terms.</p>
                    <button wire:click="clearFilters" class="btn btn-primary rounded-pill px-4 mt-1">
                        Reset Filters
                    </button>
                </div>
            @endif
        </div>
    </div>

@push('styles')
<style>
    /* ── Filter pills ── */
    .filter-label {
        display: block;
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .6px;
        color: var(--text-muted);
        margin-bottom: .5rem;
    }
    .filter-pill {
        display: block;
        width: 100%;
        text-align: left;
        background: none;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: .35rem .75rem;
        font-size: .83rem;
        color: var(--text);
        cursor: pointer;
        transition: all .15s;
    }
    .filter-pill:hover { background: var(--surface-2); }
    .filter-pill.active {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
        font-weight: 600;
    }

    /* ── Product card ── */
    .shop-card {
        background: var(--surface);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,.06);
        display: flex;
        flex-direction: column;
        transition: transform .2s, box-shadow .2s;
    }
    .shop-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 32px rgba(0,0,0,.13);
    }

    .shop-card-img {
        background: var(--surface-2);
        overflow: hidden;
    }
    .shop-card-img a { display: block; }
    .shop-card-img img {
        width: 100%;
        height: 180px;
        object-fit: contain;
        padding: 14px;
        transition: transform .3s;
    }
    .shop-card:hover .shop-card-img img { transform: scale(1.04); }
    .shop-card-img-placeholder {
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
    }

    .shop-card-oos {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,.45);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .5px;
        text-transform: uppercase;
    }

    .shop-badge {
        display: inline-block;
        padding: 2px 7px;
        border-radius: 6px;
        font-size: .68rem;
        font-weight: 700;
    }
    .shop-badge-sale { background: #ef4444; color: #fff; }
    .shop-badge-featured { background: #ff6b00; color: #fff; }

    .shop-card-body {
        padding: 12px 14px 14px;
        display: flex;
        flex-direction: column;
        flex: 1;
        gap: 4px;
    }
    .shop-card-meta {
        font-size: .7rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: .4px;
    }
    .shop-card-title {
        font-size: .88rem;
        font-weight: 600;
        color: var(--text);
        text-decoration: none;
        line-height: 1.35;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .shop-card-title:hover { color: var(--primary); }

    .shop-card-price { margin-top: auto; padding-top: 8px; }
    .shop-price { font-size: 1.05rem; font-weight: 700; color: var(--primary); }
    .shop-price-sale { font-size: 1.05rem; font-weight: 700; color: var(--accent); }
    .shop-price-old { font-size: .82rem; color: var(--text-muted); text-decoration: line-through; margin-right: 4px; }

    .shop-btn-cart {
        margin-top: 10px;
        width: 100%;
        padding: 7px;
        border: none;
        border-radius: 10px;
        background: #ff6b00;
        color: #fff;
        font-size: .83rem;
        font-weight: 600;
        cursor: pointer;
        transition: background .15s, transform .1s;
    }
    .shop-btn-cart:hover:not(:disabled) { background: #e05a00; transform: translateY(-1px); }
    .shop-btn-cart:disabled { opacity: .6; cursor: not-allowed; }

    .shop-btn-oos {
        margin-top: 10px;
        width: 100%;
        padding: 7px;
        border: 1px solid var(--border);
        border-radius: 10px;
        background: var(--surface-2);
        color: var(--text-muted);
        font-size: .83rem;
        font-weight: 600;
        cursor: not-allowed;
    }
</style>
@endpush
</div>
