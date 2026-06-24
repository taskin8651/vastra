@extends('frontend.master')
@section('content')
<main class="container py-4">
    <h1>Shop fashion by audience</h1>
    <div class="row g-3 mt-1">
        @foreach($audiences as $audience)
            <div class="col-6 col-md-4"><a class="card text-decoration-none h-100" href="{{ route('storefront.audience', $audience) }}"><div class="card-body"><h2 class="h4">{{ $audience->name }}</h2><p class="mb-0">{{ $audience->categories->count() }} categories</p></div></a></div>
        @endforeach
    </div>
    <h2 class="mt-5">Featured products</h2>
    <div class="row g-3">@foreach($products as $product)<div class="col-6 col-md-3"><a class="card text-decoration-none h-100" href="{{ route('storefront.product', $product) }}">@if($product->image_path)<img class="card-img-top" src="{{ asset($product->image_path) }}" alt="{{ $product->name }}">@endif<div class="card-body"><strong>{{ $product->name }}</strong><br><small>{{ $product->brand->name }}</small><br>₹{{ number_format($product->price, 0) }}</div></a></div>@endforeach</div>
</main>
@endsection
