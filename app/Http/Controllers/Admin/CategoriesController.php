<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Audience;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    public function index() { return view('admin.catalog.categories.index', ['categories' => Category::with('audience')->orderBy('sort_order')->get()]); }
    public function create() { return view('admin.catalog.categories.form', ['category' => new Category(), 'audiences' => Audience::active()->orderBy('sort_order')->pluck('name', 'id')]); }
    public function store(Request $request) { $category = Category::create($this->validated($request)); $this->syncMedia($request, $category); return redirect()->route('admin.categories.index')->with('message', 'Category created.'); }
    public function edit(Category $category) { return view('admin.catalog.categories.form', ['category' => $category, 'audiences' => Audience::active()->orderBy('sort_order')->pluck('name', 'id')]); }
    public function update(Request $request, Category $category) { $category->update($this->validated($request, $category)); $this->syncMedia($request, $category); return redirect()->route('admin.categories.index')->with('message', 'Category updated.'); }
    public function destroy(Category $category) { $category->delete(); return back()->with('message', 'Category deleted.'); }
    private function validated(Request $request, ?Category $category = null): array
    {
        $data = $request->validate(['audience_id' => 'required|exists:audiences,id', 'name' => 'required|string|max:100', 'slug' => 'nullable|alpha_dash|max:100|unique:categories,slug,'.optional($category)->id, 'image' => 'nullable|image|max:4096', 'sort_order' => 'nullable|integer|min:0']);
        unset($data['image']);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name'].'-'.$data['audience_id']);
        $data['is_active'] = $request->boolean('is_active');
        return $data;
    }

    private function syncMedia(Request $request, Category $category): void
    {
        if ($request->hasFile('image')) {
            $category->addMedia($request->file('image'))->toMediaCollection('image');
        }
    }
}
