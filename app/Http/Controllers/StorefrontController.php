<?php

namespace App\Http\Controllers;

use App\Models\Audience;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class StorefrontController extends Controller
{
    public function home()
    {
        return view('storefront.home', ['audiences' => Audience::active()->with('categories')->orderBy('sort_order')->get(), 'products' => Product::active()->with('brand')->where('is_featured', true)->take(8)->get()]);
    }

    public function audience(Audience $audience)
    {
        abort_unless($audience->is_active, 404);
        return view('storefront.catalogue', ['title' => $audience->name, 'audience' => $audience, 'categories' => $audience->categories()->active()->orderBy('sort_order')->get(), 'brands' => Brand::active()->whereHas('products.category', fn ($query) => $query->where('audience_id', $audience->id))->get(), 'products' => Product::active()->with(['brand', 'category'])->whereHas('category', fn ($query) => $query->where('audience_id', $audience->id))->paginate(12)]);
    }

    public function category(Audience $audience, Category $category, Request $request)
    {
        abort_unless($category->audience_id === $audience->id && $category->is_active, 404);
        $products = Product::active()->with(['brand', 'category'])->where('category_id', $category->id);
        if ($request->filled('brand')) $products->whereHas('brand', fn ($query) => $query->where('slug', $request->string('brand')));
        return view('storefront.catalogue', ['title' => $category->name, 'audience' => $audience, 'categories' => collect([$category]), 'brands' => Brand::active()->whereHas('products', fn ($query) => $query->where('category_id', $category->id))->get(), 'products' => $products->paginate(12)]);
    }

    public function brand(Brand $brand, Request $request)
    {
        abort_unless($brand->is_active, 404);
        $products = Product::active()->with(['brand', 'category.audience'])->where('brand_id', $brand->id);
        if ($request->filled('category')) $products->whereHas('category', fn ($query) => $query->where('slug', $request->string('category')));
        return view('storefront.catalogue', ['title' => $brand->name, 'audience' => null, 'categories' => Category::active()->whereHas('products', fn ($query) => $query->where('brand_id', $brand->id))->get(), 'brands' => collect([$brand]), 'products' => $products->paginate(12)]);
    }

    public function product(Product $product)
    {
        abort_unless($product->is_active, 404);
        return view('storefront.product', ['product' => $product->load(['brand', 'category.audience'])]);
    }
}
