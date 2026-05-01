@extends('layouts.app')
@section('title', 'Shop')
@section('content')
<div style="background:var(--surface);min-height:80vh;">
    <div class="container py-4">
        <livewire:shop.product-list />
    </div>
</div>
@endsection
