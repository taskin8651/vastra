@extends('frontend.master')
@section('content')
<main class="container py-4">
    <h1>{{ $title }}</h1>
    @if($audience)<p><a href="{{ route('storefront.audience', $audience) }}">All {{ $audience->name }}</a></p>@endif
    <div class="mb-4"><strong>Categories: </strong>@foreach($categories as $category)<a class="me-2" href="{{ $audience ? route('storefront.category', [$audience, $category]) : route('storefront.brand', request()->route('brand')) . '?category=' . $category->slug }}">{{ $category->name }}</a>@endforeach</div>
    <div class="mb-4"><strong>Brands: </strong>@foreach($brands as $brand)<a class="me-2" href="{{ route('storefront.brand', $brand) }}">{{ $brand->name }}</a>@endforeach</div>
    <div class="row g-3">@forelse($products as $product)<div class="col-6 col-md-3"><a class="card text-decoration-none h-100" href="{{ route('storefront.product', $product) }}">@if($product->image_path)<img class="card-img-top" src="{{ asset($product->image_path) }}" alt="{{ $product->name }}">@endif<div class="card-body"><strong>{{ $product->name }}</strong><br><small>{{ $product->brand->name }}</small><br>₹{{ number_format($product->price, 0) }}</div></a></div>@empty<p>No products found.</p>@endforelse</div>
    <div class="mt-4">{{ $products->links() }}</div>
</main>
@endsection
