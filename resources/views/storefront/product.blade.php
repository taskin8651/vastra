@extends('frontend.master')
@section('content')
<main class="container py-4"><div class="row g-4"><div class="col-md-6">@if($product->image_path)<img class="img-fluid rounded" src="{{ asset($product->image_path) }}" alt="{{ $product->name }}">@endif</div><div class="col-md-6"><p>{{ $product->category->audience->name }} / {{ $product->category->name }}</p><h1>{{ $product->name }}</h1><h2 class="h5">{{ $product->brand->name }}</h2><h3>₹{{ number_format($product->price, 0) }}</h3>@if($product->compare_at_price)<del>₹{{ number_format($product->compare_at_price, 0) }}</del>@endif<p class="mt-3">{{ $product->description }}</p><p><strong>Stock:</strong> {{ $product->stock_quantity > 0 ? $product->stock_quantity . ' available' : 'Out of stock' }}</p></div></div></main>
@endsection
