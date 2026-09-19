<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductReview;
use Livewire\Component;

class ProductReviews extends Component
{
    public Product $product;

    public int    $rating  = 0;
    public string $comment = '';
    public bool   $editing = false;

    public function mount(Product $product): void
    {
        $this->product = $product;

        if (auth()->check()) {
            $existing = ProductReview::where('product_id', $product->id)
                ->where('user_id', auth()->id())
                ->first();
            if ($existing) {
                $this->rating  = $existing->rating;
                $this->comment = $existing->comment ?? '';
                $this->editing = true;
            }
        }
    }

    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }

    public function submitReview(): void
    {
        if (!auth()->check()) return;

        $this->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        ProductReview::updateOrCreate(
            ['product_id' => $this->product->id, 'user_id' => auth()->id()],
            ['rating' => $this->rating, 'comment' => $this->comment]
        );

        $this->editing = true;
        $this->dispatch('$refresh');
        session()->flash('review_success', 'Your review has been saved!');
    }

    public function deleteReview(): void
    {
        if (!auth()->check()) return;

        ProductReview::where('product_id', $this->product->id)
            ->where('user_id', auth()->id())
            ->delete();

        $this->rating  = 0;
        $this->comment = '';
        $this->editing = false;
        session()->flash('review_success', 'Review deleted.');
    }

    public function render()
    {
        $reviews = ProductReview::with('user')
            ->where('product_id', $this->product->id)
            ->latest()
            ->get();

        $avgRating    = round($reviews->avg('rating') ?? 0, 1);
        $totalReviews = $reviews->count();

        $distribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = $reviews->where('rating', $i)->count();
            $distribution[$i] = [
                'count'   => $count,
                'percent' => $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0,
            ];
        }

        return view('livewire.product-reviews', compact(
            'reviews', 'avgRating', 'totalReviews', 'distribution'
        ));
    }
}
