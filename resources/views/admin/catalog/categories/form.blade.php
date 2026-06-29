@extends('layouts.admin')

@section('page-title', $category->exists ? 'Edit Category' : 'Add Category')

@section('content')
<div class="admin-page-head">
    <div>
        <a class="admin-back-link" href="{{ route('admin.categories.index') }}">Back to list</a>
        <h2 class="admin-page-title">{{ $category->exists ? 'Edit' : 'Add' }} Category</h2>
    </div>
</div>

<form method="POST" enctype="multipart/form-data" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
    @csrf
    @if($category->exists)
        @method('PUT')
    @endif

    <div class="form-card">
        <div class="form-card-body">
            <div class="field-group">
                <label class="field-label">Audience *</label>
                <select class="field-input" name="audience_id" required>
                    @foreach($audiences as $id => $name)
                        <option value="{{ $id }}" @selected(old('audience_id', $category->audience_id) == $id)>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field-group"><label class="field-label">Name *</label><input class="field-input" name="name" value="{{ old('name', $category->name) }}" required></div>
            <div class="field-group"><label class="field-label">Slug</label><input class="field-input" name="slug" value="{{ old('slug', $category->slug) }}"></div>
            <div class="field-group"><label class="field-label">Category image</label><input class="field-input" type="file" name="image" accept="image/*">@if($category->image_url)<small>Current image uploaded.</small>@endif</div>
            <div class="field-group"><label class="field-label">Display order</label><input class="field-input" type="number" min="0" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}"></div>
            <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->exists ? $category->is_active : true))> Active</label>
        </div>
    </div>

    <div class="form-actions">
        <button class="btn-primary">Save Category</button>
        <a class="btn-ghost" href="{{ route('admin.categories.index') }}">Cancel</a>
    </div>
</form>
@endsection
