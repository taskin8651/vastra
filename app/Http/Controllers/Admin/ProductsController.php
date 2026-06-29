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
    public function store(Request $request) { $product = Product::create($this->validated($request)); $this->syncMedia($request, $product); return redirect()->route('admin.products.index')->with('message', 'Product created.'); }
    public function edit(Product $product) { return $this->form($product); }
    public function update(Request $request, Product $product) { $product->update($this->validated($request, $product)); $this->syncMedia($request, $product); return redirect()->route('admin.products.index')->with('message', 'Product updated.'); }
    public function destroy(Product $product) { $product->delete(); return back()->with('message', 'Product deleted.'); }
    private function form(Product $product) { return view('admin.catalog.products.form', ['product' => $product, 'categories' => Category::with('audience')->active()->get(), 'brands' => Brand::active()->orderBy('name')->get()]); }
    private function validated(Request $request, ?Product $product = null): array
    {
        $data = $request->validate(['category_id' => 'required|exists:categories,id', 'brand_id' => 'required|exists:brands,id', 'name' => 'required|string|max:150', 'slug' => 'nullable|alpha_dash|max:160|unique:products,slug,'.optional($product)->id, 'sku' => 'required|string|max:80|unique:products,sku,'.optional($product)->id, 'description' => 'nullable|string', 'price' => 'required|numeric|min:0', 'compare_at_price' => 'nullable|numeric|gte:price', 'stock_quantity' => 'required|integer|min:0', 'product_images.*' => 'nullable|image|max:4096', 'colour' => 'nullable|string|max:50', 'available_colours' => 'nullable|string|max:500', 'available_sizes' => 'nullable|string|max:500', 'closure_type' => 'nullable|string|max:100', 'fashion_type' => 'nullable|string|max:100', 'hemline' => 'nullable|string|max:100', 'knit_or_woven' => 'nullable|string|max:100', 'product_length' => 'nullable|string|max:100', 'season' => 'nullable|string|max:100', 'transparency' => 'nullable|string|max:100', 'stretchability' => 'nullable|string|max:100', 'wash_care' => 'nullable|string|max:100', 'fit_type' => 'nullable|string|max:100', 'fabric_details' => 'nullable|string|max:150', 'fabric_composition' => 'nullable|string|max:255', 'occasion' => 'nullable|string|max:100', 'pattern_type' => 'nullable|string|max:100', 'sleeve_length' => 'nullable|string|max:100']);
        unset($data['product_images']);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['available_colours'] = $this->csvToArray($data['available_colours'] ?? null);
        $data['available_sizes'] = $this->csvToArray($data['available_sizes'] ?? null);
        return $data;
    }

    private function csvToArray(?string $value): ?array
    {
        return filled($value) ? array_values(array_filter(array_map('trim', explode(',', $value)))) : null;
    }

    private function syncMedia(Request $request, Product $product): void
    {
        if (! $request->hasFile('product_images')) {
            return;
        }

        foreach ($request->file('product_images', []) as $image) {
            $product->addMedia($image)->toMediaCollection('images');
        }
    }
}
