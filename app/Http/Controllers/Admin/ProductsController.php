<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductsController extends Controller
{
    public function index() { return view('admin.catalog.products.index', ['products' => Product::with(['category.audience', 'brand'])->latest()->get()]); }
    public function create() { return $this->form(new Product()); }
    public function store(Request $request) { Product::create($this->validated($request)); return redirect()->route('admin.products.index')->with('message', 'Product created.'); }
    public function edit(Product $product) { return $this->form($product); }
    public function update(Request $request, Product $product) { $product->update($this->validated($request, $product)); return redirect()->route('admin.products.index')->with('message', 'Product updated.'); }
    public function destroy(Product $product) { $product->delete(); return back()->with('message', 'Product deleted.'); }
    private function form(Product $product) { return view('admin.catalog.products.form', ['product' => $product, 'categories' => Category::with('audience')->active()->get(), 'brands' => Brand::active()->orderBy('name')->get()]); }
    private function validated(Request $request, ?Product $product = null): array
    {
        $data = $request->validate(['category_id' => 'required|exists:categories,id', 'brand_id' => 'required|exists:brands,id', 'name' => 'required|string|max:150', 'slug' => 'nullable|alpha_dash|max:160|unique:products,slug,'.optional($product)->id, 'sku' => 'required|string|max:80|unique:products,sku,'.optional($product)->id, 'description' => 'nullable|string', 'price' => 'required|numeric|min:0', 'compare_at_price' => 'nullable|numeric|gte:price', 'stock_quantity' => 'required|integer|min:0', 'image_path' => 'nullable|string|max:255']);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        return $data;
    }
}
