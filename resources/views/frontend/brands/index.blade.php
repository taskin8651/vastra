@extends('frontend.brands.master')

@section('title', 'Brands - Vastra Express')
@section('body_class', 'brands-page')
@section('header_class', 'brands-header')

@section('content')

@php
    use Illuminate\Support\Str;
@endphp

<main class="brands-main">

    <section class="brands-intro">
        <h1>Shop By Brand</h1>
        <p>Explore top brand and choose your favourite</p>
    </section>

    <section class="brands-tools">

        <div class="sort-row">
            <span>Sort By:</span>
            <button type="button">
                Popularity <i class="bi bi-chevron-down"></i>
            </button>
        </div>

        <div class="filter-row">
            <a href="#brandFilter">
                <i class="bi bi-sliders"></i> Filter
            </a>

            <span>{{ $brands->count() }}+ Brands</span>
        </div>

    </section>

    <section class="brand-catalogue">

        @forelse($brands as $brand)

            @php
                $brandClass = Str::slug($brand->name) . '-logo';
            @endphp

            <a href="{{ route('frontend.brands.show', $brand) }}" class="brand-catalogue-card">

                <b class="logo {{ $brandClass }}">
                    {{ strtoupper($brand->name) }}
                </b>

                <strong>{{ $brand->name }}</strong>

                <small>
                    {{ $brand->active_products_count }}+ Products
                </small>

            </a>

        @empty

            <div class="empty-category-box">
                <h3>No Brands Found</h3>
                <p>Abhi koi active brand add nahi hai.</p>
            </div>

        @endforelse

    </section>

    <a class="view-more-link" href="#">View More</a>

    <section class="subscribe-strip">

        <div class="subscribe-icon">
            <i class="bi bi-envelope-heart"></i>
        </div>

        <div>
            <h2>STAY IN STYLE</h2>
            <p>GET UPDATES ON NEW ARRIVALS, OFFERS AND EXCLUSIVE DEALS</p>
        </div>

        <form>
            <input type="email" placeholder="Enter Your Email" aria-label="Email">
            <button type="submit">SUBSCRIBE</button>
        </form>

    </section>

</main>

@endsection

@section('filter_sheet')

<section class="brand-filter-sheet" id="brandFilter">

    <div class="filter-sheet-head">
        <h2>Filter By Category</h2>
        <a href="#" aria-label="Close filter">
            <i class="bi bi-x-lg"></i>
        </a>
    </div>

    <div class="filter-group">

        @foreach($audiences as $audience)
            <a href="{{ url('/shop/' . $audience->slug) }}">
                <span></span>
                {{ $audience->name }}
                <i class="bi bi-chevron-right"></i>
            </a>
        @endforeach

        <a href="{{ url('/shop/accessories') }}">
            <span></span>
            Accessories
            <i class="bi bi-chevron-right"></i>
        </a>

    </div>

    <h2 class="filter-title">Discount Range</h2>

    <div class="filter-group">
        <a href="#brandFilter"><span></span>10% and above <i class="bi bi-chevron-right"></i></a>
        <a href="#brandFilter"><span></span>20% and above <i class="bi bi-chevron-right"></i></a>
        <a href="#brandFilter"><span></span>30% and above <i class="bi bi-chevron-right"></i></a>
        <a href="#brandFilter"><span></span>40% and above <i class="bi bi-chevron-right"></i></a>
        <a href="#brandFilter"><span></span>50% and above <i class="bi bi-chevron-right"></i></a>
    </div>

    <h2 class="filter-title">Price Range</h2>

    <div class="filter-group">
        <a href="#brandFilter"><span></span>Under Rs999 <i class="bi bi-chevron-right"></i></a>
        <a href="#brandFilter"><span></span>Rs500 - Rs999 <i class="bi bi-chevron-right"></i></a>
        <a href="#brandFilter"><span></span>Rs1000 - Rs1999 <i class="bi bi-chevron-right"></i></a>
        <a href="#brandFilter"><span></span>Rs2000 - Rs2999 <i class="bi bi-chevron-right"></i></a>
        <a href="#brandFilter"><span></span>Rs3000 and above <i class="bi bi-chevron-right"></i></a>
    </div>

    <div class="filter-actions">
        <a href="{{ route('frontend.brands.index') }}">CLEAR ALL</a>
        <a href="#">APPLY FILTER</a>
    </div>

</section>

@endsection