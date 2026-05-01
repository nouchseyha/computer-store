@extends('layouts.app')
@section('title', $product->name)
@section('content')
<div style="background:var(--surface);min-height:80vh;">
    <div class="container py-4">
        <livewire:shop.product-show :product="$product" />
    </div>
</div>
@endsection
