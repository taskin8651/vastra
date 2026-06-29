<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandsController extends Controller
{
    public function index() { return view('admin.catalog.brands.index', ['brands' => Brand::withCount('products')->orderBy('sort_order')->get()]); }
    public function create() { return view('admin.catalog.brands.form', ['brand' => new Brand()]); }
    public function store(Request $request) { $brand = Brand::create($this->validated($request)); $this->syncMedia($request, $brand); return redirect()->route('admin.brands.index')->with('message', 'Brand created.'); }
    public function edit(Brand $brand) { return view('admin.catalog.brands.form', compact('brand')); }
    public function update(Request $request, Brand $brand) { $brand->update($this->validated($request, $brand)); $this->syncMedia($request, $brand); return redirect()->route('admin.brands.index')->with('message', 'Brand updated.'); }
    public function destroy(Brand $brand) { $brand->delete(); return back()->with('message', 'Brand deleted.'); }
    private function validated(Request $request, ?Brand $brand = null): array
    {
        $data = $request->validate(['name' => 'required|string|max:100', 'slug' => 'nullable|alpha_dash|max:100|unique:brands,slug,'.optional($brand)->id, 'image' => 'nullable|image|max:4096', 'sort_order' => 'nullable|integer|min:0']);
        unset($data['image']);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        return $data;
    }

    private function syncMedia(Request $request, Brand $brand): void
    {
        if ($request->hasFile('image')) {
            $brand->addMedia($request->file('image'))->toMediaCollection('image');
        }
    }
}
