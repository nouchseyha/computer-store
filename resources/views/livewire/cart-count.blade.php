<a class="nav-link position-relative" href="{{ route('cart.index') }}">
    <i class="fas fa-shopping-cart fa-lg"></i>
    @if($count > 0)
        <span class="cart-badge">{{ $count }}</span>
    @endif
</a>
