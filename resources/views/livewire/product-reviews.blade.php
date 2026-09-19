<div class="mt-5">

    {{-- Header --}}
    <h5 class="fw-bold mb-4" style="border-left:4px solid var(--accent);padding-left:12px;">
        Customer Reviews
        @if($totalReviews > 0)
            <span class="text-muted fw-normal ms-1" style="font-size:.85rem;">({{ $totalReviews }})</span>
        @endif
    </h5>

    @if(session('review_success'))
        <div class="alert alert-success py-2 small mb-4 border-0 shadow-sm">
            <i class="fas fa-check-circle me-2"></i>{{ session('review_success') }}
        </div>
    @endif

    {{-- ── Rating Summary (only when reviews exist) ── --}}
    @if($totalReviews > 0)
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center g-4">
                {{-- Average score --}}
                <div class="col-4 col-md-2 text-center">
                    <div style="font-size:3rem;font-weight:800;line-height:1;color:var(--text);">
                        {{ number_format($avgRating, 1) }}
                    </div>
                    <div class="my-1">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= round($avgRating) ? 'fas' : 'far' }} fa-star"
                               style="color:#f59e0b;font-size:.8rem;"></i>
                        @endfor
                    </div>
                    <div class="text-muted" style="font-size:.75rem;">
                        {{ $totalReviews }} {{ $totalReviews === 1 ? 'review' : 'reviews' }}
                    </div>
                </div>
                {{-- Distribution --}}
                <div class="col-8 col-md-10">
                    @foreach($distribution as $star => $data)
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="text-muted fw-semibold" style="font-size:.75rem;width:10px;">{{ $star }}</span>
                        <i class="fas fa-star" style="color:#f59e0b;font-size:.65rem;"></i>
                        <div class="flex-grow-1 rounded-pill" style="height:7px;background:var(--surface-2);overflow:hidden;">
                            <div class="rounded-pill" style="height:7px;width:{{ $data['percent'] }}%;background:#f59e0b;"></div>
                        </div>
                        <span class="text-muted" style="font-size:.72rem;width:20px;text-align:right;">{{ $data['count'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ── Write / Edit Review Form ── --}}
    @auth
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3">
                <i class="fas fa-{{ $editing ? 'edit' : 'star' }} me-2" style="color:var(--accent);"></i>
                {{ $editing ? 'Edit Your Review' : 'Write a Review' }}
            </h6>

            {{-- Star Rating --}}
            <div class="mb-3">
                <label class="form-label small fw-semibold text-muted">Rating *</label>
                <div class="d-flex gap-2 align-items-center">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button"
                                wire:click="setRating({{ $i }})"
                                class="border-0 bg-transparent p-0"
                                style="font-size:2rem;cursor:pointer;line-height:1;"
                                onmouseover="hoverStars({{ $i }})"
                                onmouseout="resetStars({{ $rating }})">
                            <i id="star-{{ $i }}"
                               class="{{ $i <= $rating ? 'fas' : 'far' }} fa-star"
                               style="color:{{ $i <= $rating ? '#f59e0b' : 'var(--border)' }};transition:color .1s;"></i>
                        </button>
                    @endfor
                    @if($rating > 0)
                        <span class="ms-1 fw-semibold small" style="color:var(--accent);">
                            {{ ['','😞 Terrible','😕 Poor','😐 Average','😊 Good','🤩 Excellent'][$rating] }}
                        </span>
                    @endif
                </div>
                @error('rating')
                    <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            {{-- Comment --}}
            <div class="mb-3">
                <label class="form-label small fw-semibold text-muted">Comment <span class="fw-normal">(optional)</span></label>
                <textarea wire:model="comment"
                          class="form-control"
                          rows="3"
                          placeholder="Share your experience with this product..."
                          style="border-radius:12px;resize:none;font-size:.9rem;"></textarea>
                @error('comment')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="d-flex gap-2">
                <button wire:click="submitReview"
                        wire:loading.attr="disabled"
                        wire:target="submitReview"
                        class="btn btn-primary fw-semibold px-4"
                        style="border-radius:10px;">
                    <span wire:loading.remove wire:target="submitReview">
                        <i class="fas fa-paper-plane me-2"></i>
                        {{ $editing ? 'Update Review' : 'Submit Review' }}
                    </span>
                    <span wire:loading wire:target="submitReview">
                        <span class="spinner-border spinner-border-sm me-2"></span>Saving...
                    </span>
                </button>
                @if($editing)
                    <button wire:click="deleteReview"
                            onclick="return confirm('Delete your review?')"
                            class="btn btn-outline-danger fw-semibold"
                            style="border-radius:10px;">
                        <i class="fas fa-trash me-1"></i>Delete
                    </button>
                @endif
            </div>
        </div>
    </div>
    @else
    <div class="mb-4 p-3 rounded-3 d-flex align-items-center gap-3"
         style="background:var(--surface-2);border:1px solid var(--border);">
        <i class="fas fa-user-circle fa-2x" style="color:var(--text-muted);"></i>
        <div>
            <div class="fw-semibold small">Want to leave a review?</div>
            <div class="text-muted small">
                <a href="{{ route('login') }}" class="fw-semibold text-decoration-none" style="color:var(--primary);">Sign in</a>
                to share your experience.
            </div>
        </div>
    </div>
    @endauth

    {{-- ── Reviews List ── --}}
    @if($reviews->count())
        <div class="d-flex flex-column gap-3">
            @foreach($reviews as $review)
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    {{-- Top row: user + stars --}}
                    <div class="d-flex align-items-start justify-content-between gap-3 mb-2">
                        <div class="d-flex align-items-center gap-3">
                            @if($review->user->avatar)
                                <img src="{{ asset($review->user->avatar) }}"
                                     class="rounded-circle flex-shrink-0"
                                     style="width:38px;height:38px;object-fit:cover;">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                                     style="width:38px;height:38px;background:var(--primary);font-size:.8rem;">
                                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <div class="fw-semibold" style="font-size:.9rem;line-height:1.2;">
                                    {{ $review->user->name }}
                                    @if(auth()->check() && $review->user_id === auth()->id())
                                        <span class="badge ms-1 rounded-pill"
                                              style="background:var(--primary);color:#fff;font-size:.6rem;">You</span>
                                    @endif
                                </div>
                                <div class="text-muted" style="font-size:.72rem;">
                                    {{ $review->created_at->format('M d, Y') }}
                                    · {{ $review->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                        {{-- Stars --}}
                        <div class="d-flex gap-1 flex-shrink-0 align-items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"
                                   style="color:{{ $i <= $review->rating ? '#f59e0b' : 'var(--border)' }};font-size:.8rem;"></i>
                            @endfor
                            <span class="ms-1 fw-semibold" style="font-size:.8rem;color:var(--text-muted);">
                                {{ $review->rating }}/5
                            </span>
                        </div>
                    </div>

                    {{-- Comment text --}}
                    @if($review->comment)
                        <p class="mb-0" style="font-size:.9rem;line-height:1.7;color:var(--text);">
                            {{ $review->comment }}
                        </p>
                    @else
                        <p class="mb-0 text-muted fst-italic" style="font-size:.85rem;">
                            No comment left.
                        </p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <div style="font-size:2.5rem;opacity:.2;">💬</div>
            <p class="text-muted mt-2 mb-0">No reviews yet. Be the first!</p>
        </div>
    @endif

</div>

@push('scripts')
<script>
function hoverStars(n) {
    for (let i = 1; i <= 5; i++) {
        const el = document.getElementById('star-' + i);
        if (!el) continue;
        el.className = i <= n ? 'fas fa-star' : 'far fa-star';
        el.style.color = i <= n ? '#f59e0b' : 'var(--border)';
    }
}
function resetStars(current) {
    for (let i = 1; i <= 5; i++) {
        const el = document.getElementById('star-' + i);
        if (!el) continue;
        el.className = i <= current ? 'fas fa-star' : 'far fa-star';
        el.style.color = i <= current ? '#f59e0b' : 'var(--border)';
    }
}
</script>
@endpush
