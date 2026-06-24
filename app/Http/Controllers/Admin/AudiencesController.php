<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Audience;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AudiencesController extends Controller
{
    public function index() { return view('admin.catalog.audiences.index', ['audiences' => Audience::orderBy('sort_order')->get()]); }
    public function create() { return view('admin.catalog.audiences.form', ['audience' => new Audience()]); }
    public function store(Request $request) { Audience::create($this->validated($request)); return redirect()->route('admin.audiences.index')->with('message', 'Audience created.'); }
    public function edit(Audience $audience) { return view('admin.catalog.audiences.form', compact('audience')); }
    public function update(Request $request, Audience $audience) { $audience->update($this->validated($request, $audience)); return redirect()->route('admin.audiences.index')->with('message', 'Audience updated.'); }
    public function destroy(Audience $audience) { $audience->delete(); return back()->with('message', 'Audience deleted.'); }

    private function validated(Request $request, ?Audience $audience = null): array
    {
        $data = $request->validate(['name' => 'required|string|max:100', 'slug' => 'nullable|alpha_dash|max:100|unique:audiences,slug,'.optional($audience)->id, 'sort_order' => 'nullable|integer|min:0']);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        return $data;
    }
}
