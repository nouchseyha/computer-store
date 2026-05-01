@extends('layouts.app')
@section('title', 'Home')
@section('content')

{{-- ── Hero ── --}}
<section class="hero-section">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <span class="badge mb-3 px-3 py-2"
                      style="background:rgba(249,115,22,.15);color:#f97316;font-size:.8rem;border-radius:20px;">
                    🔥 Best Tech Deals
                </span>
                <h1 class="mb-3">Your Ultimate<br><span style="color:var(--accent);">Tech Store</span></h1>
                <p class="mb-4" style="color:rgba(255,255,255,.7);font-size:1.05rem;max-width:480px;line-height:1.7;">
                    Discover the latest laptops, desktops, and accessories at unbeatable prices.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('shop.index') }}"
                       class="btn btn-lg px-4 fw-semibold"
                       style="background:var(--accent);color:#fff;border:none;border-radius:12px;">
                        <i class="fas fa-shopping-bag me-2"></i>Shop Now
                    </a>
                    <a href="{{ route('shop.index', ['is_featured' => 1]) }}"
                       class="btn btn-lg px-4 fw-semibold"
                       style="background:rgba(255,255,255,.1);color:#fff;border:1px solid rgba(255,255,255,.25);border-radius:12px;">
                        <i class="fas fa-star me-2"></i>Featured
                    </a>
                </div>
            </div>
            <div class="col-lg-5 text-center d-none d-lg-block">
                <i class="fas fa-laptop" style="font-size:11rem;color:rgba(249,115,22,.2);"></i>
            </div>
        </div>
    </div>
</section>

{{-- ── Features Bar ── --}}
<div style="background:var(--surface);border-bottom:1px solid var(--border);">
    <div class="container py-3">
        <div class="row text-center g-3">
            <div class="col-6 col-md-3">
                <i class="fas fa-shipping-fast me-2" style="color:var(--primary);"></i>
                <small class="fw-semibold">Free Shipping Over $100</small>
            </div>
            <div class="col-6 col-md-3">
                <i class="fas fa-shield-alt me-2" style="color:#22c55e;"></i>
                <small class="fw-semibold">1 Year Warranty</small>
            </div>
            <div class="col-6 col-md-3">
                <i class="fas fa-undo me-2" style="color:var(--accent);"></i>
                <small class="fw-semibold">30-Day Returns</small>
            </div>
            <div class="col-6 col-md-3">
                <i class="fas fa-headset me-2" style="color:#ef4444;"></i>
                <small class="fw-semibold">24/7 Support</small>
            </div>
        </div>
    </div>
</div>

{{-- ── Categories ── --}}
@if($categories->count())
<section class="py-5" style="background:var(--bg);">
    <div class="container">
        <h2 class="section-title">Shop by Category</h2>
        <div class="row g-3">
            @foreach($categories as $cat)
            <div class="col-6 col-md-4 col-lg-2">
                <a href="{{ route('shop.index', ['category' => $cat->slug]) }}" class="text-decoration-none">
                    <div class="card category-card text-center p-3 h-100">
                        <i class="fas fa-microchip fa-2x mb-2" style="color:var(--primary);"></i>
                        <div class="fw-semibold small" style="color:var(--text);">{{ $cat->name }}</div>
                        <small class="text-muted">{{ $cat->products_count }} items</small>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── Featured Products ── --}}
@if($featuredProducts->count())
<section class="py-5" style="background:var(--surface);">
    <div class="container">
        <h2 class="section-title">Featured Products</h2>
        <div class="row g-3">
            @foreach($featuredProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── Latest Arrivals ── --}}
@if($latestProducts->count())
<section class="py-5" style="background:var(--bg);">
    <div class="container">
        <h2 class="section-title">Latest Arrivals</h2>
        <div class="row g-3">
            @foreach($latestProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('shop.index') }}" class="btn btn-primary btn-lg px-5 fw-semibold" style="border-radius:12px;">
                <i class="fas fa-store me-2"></i>View All Products
            </a>
        </div>
    </div>
</section>
@endif

@endsection
