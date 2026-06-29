@extends('frontend.audience.master')

@section('content')

@php
    use Illuminate\Support\Str;

    $audienceSlug = $audience->slug;

    $heroPath = "assets/images/{$audienceSlug}-hero.png";
    $trialPath = "assets/images/{$audienceSlug}-trial.png";

    $heroImage = file_exists(public_path($heroPath))
        ? asset($heroPath)
        : asset('assets/images/category-hero-placeholder.png');

    $trialImage = file_exists(public_path($trialPath))
        ? asset($trialPath)
        : null;

    $categoryImage = function ($category) {
        return $category->image_url ?: asset('assets/images/category-placeholder.png');
    };
@endphp

<main>

    {{-- TRUST STRIP --}}
    <section class="category-trust">
        <div class="trust-strip">

            <div class="trust-item">
                <i class="bi bi-truck"></i>
                <span>
                    <strong>60 min</strong>
                    <small>Delivery</small>
                </span>
            </div>

            <div class="trust-item">
                <i class="bi bi-box-seam"></i>
                <span>
                    <strong>30 min</strong>
                    <small>Returns Policy</small>
                </span>
            </div>

            <div class="trust-item">
                <i class="bi bi-house-heart"></i>
                <span>
                    <strong>Home Trial</strong>
                    <small>Try before you buy</small>
                </span>
            </div>

            <div class="trust-item">
                <i class="bi bi-shield-check"></i>
                <span>
                    <strong>Secure</strong>
                    <small>Payment</small>
                </span>
            </div>

        </div>
    </section>

    {{-- AUDIENCE HERO --}}
    <section class="category-hero reference-{{ $audienceSlug }}-hero">
        <img src="{{ $heroImage }}" alt="{{ $audience->name }}">
    </section>

    {{-- AUDIENCE WISE CATEGORY GRID --}}
    <section class="category-grid-wrap">

        @if($audience->categories->count())

            <div class="category-tile-grid">

                @foreach($audience->categories as $category)

                    <a href="{{ route('frontend.category.products', [$audience, $category]) }}"
                       class="category-tile reference-{{ $audienceSlug }}-tile">

                        <img src="{{ $categoryImage($category) }}"
                             alt="{{ $category->name }}">


                    </a>

                @endforeach

            </div>

        @else

            <div class="empty-category-box">
                <h3>No Categories Found</h3>
                <p>{{ $audience->name }} ke liye abhi koi category add nahi hai.</p>
            </div>

        @endif

    </section>

    {{-- HOME TRIAL BANNER --}}
    @if($trialImage)
        <section class="home-trial-banner reference-women-trial">
            <img src="{{ asset('assets/images/kids-trial.png') }}" alt="{{ $audience->name }} Home Trial">
        </section>
    @endif

    <a class="all-categories-link" href="{{ route('frontend.categories.index') }}">
        <i class="bi bi-grid-3x3-gap-fill"></i>
        View All Categories
    </a>

</main>

@endsection
