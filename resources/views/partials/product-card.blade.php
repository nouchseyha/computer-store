<div class="col-6 col-md-4 col-lg-3">
    <div class="card product-card h-100" style="border-radius:16px;overflow:hidden;">
        {{-- Image --}}
        <div class="position-relative" style="background:var(--surface-2);">
            <a href="{{ route('shop.show', $product->slug) }}">
                @if($product->image)
                    <img src="{{ $product->image_url }}"
                         class="card-img-top"
                         alt="{{ $product->name }}"
                         style="height:190px;object-fit:contain;padding:12px;background:var(--surface-2);">
                @else
                    <div class="d-flex align-items-center justify-content-center"
                         style="height:190px;background:var(--surface-2);">
                        <i class="fas fa-laptop fa-3x text-muted opacity-25"></i>
                    </div>
                @endif
            </a>
            {{-- Badges --}}
            <div class="position-absolute top-0 start-0 p-2 d-flex flex-column gap-1">
                @if($product->is_featured)
                    <span class="badge" style="background:var(--accent);font-size:.65rem;">⭐ Featured</span>
                @endif
                @if($product->sale_price)
                    <span class="badge bg-danger" style="font-size:.65rem;">
                        -{{ round((1 - $product->sale_price / $product->price) * 100) }}%
                    </span>
                @endif
            </div>
            @if($product->stock === 0)
                <div class="position-absolute top-0 end-0 p-2">
                    <span class="badge bg-dark bg-opacity-75" style="font-size:.65rem;">Out of Stock</span>
                </div>
            @endif
        </div>

        {{-- Body --}}
        <div class="card-body d-flex flex-column p-3">
            <div class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.3px;">
                {{ $product->category->name ?? '' }}
                @if($product->brand) · {{ $product->brand }} @endif
            </div>
            <h6 class="card-title mb-1 fw-semibold lh-sm" style="font-size:.88rem;">
                <a href="{{ route('shop.show', $product->slug) }}"
                   class="text-decoration-none"
                   style="color:var(--text);">
                    {{ Str::limit($product->name, 48) }}
                </a>
            </h6>
            @php $avg = $product->reviews()->avg('rating') ?? 0; $cnt = $product->reviews()->count(); @endphp
            @if($cnt > 0)
                <div class="d-flex align-items-center gap-1 mb-1">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="{{ $i <= round($avg) ? 'fas' : 'far' }} fa-star"
                           style="color:#f59e0b;font-size:.65rem;"></i>
                    @endfor
                    <span class="text-muted" style="font-size:.72rem;">({{ $cnt }})</span>
                </div>
            @endif

            <div class="mt-auto">
                {{-- Price --}}
                <div class="mb-2">
                    @if($product->sale_price)
                        <span class="text-muted text-decoration-line-through me-1" style="font-size:.82rem;">
                            ${{ number_format($product->price, 2) }}
                        </span>
                        <span class="fw-bold" style="font-size:1.05rem;color:var(--accent);">
                            ${{ number_format($product->sale_price, 2) }}
                        </span>
                    @else
                        <span class="fw-bold" style="font-size:1.05rem;color:var(--primary);">
                            ${{ number_format($product->price, 2) }}
                        </span>
                    @endif
                </div>

                {{-- Add to Cart --}}
                @if($product->stock > 0)
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button class="btn btn-sm w-100 fw-semibold"
                                style="background:var(--accent);color:#fff;border:none;border-radius:10px;padding:7px;">
                            <i class="fas fa-cart-plus me-1"></i>Add to Cart
                        </button>
                    </form>
                @else
                    <button class="btn btn-sm w-100 fw-semibold" disabled
                            style="background:var(--surface-2);color:var(--text-muted);border:1px solid var(--border);border-radius:10px;">
                        Out of Stock
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
