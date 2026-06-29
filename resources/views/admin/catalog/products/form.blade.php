@extends('layouts.admin')

@section('page-title', $product->exists ? 'Edit Product' : 'Add Product')

@section('content')
<div class="admin-page-head">
    <div>
        <a class="admin-back-link" href="{{ route('admin.products.index') }}">Back to list</a>
        <h2 class="admin-page-title">{{ $product->exists ? 'Edit' : 'Add' }} Product</h2>
    </div>
</div>

<form method="POST" enctype="multipart/form-data" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}">
    @csrf
    @if($product->exists)
        @method('PUT')
    @endif

    <div class="form-card">
        <div class="form-card-body">
            <h3 class="form-card-title">Basic information</h3>

            <div class="field-group">
                <label class="field-label">Category *</label>
                <select class="field-input" name="category_id" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                            {{ $category->audience->name }} - {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field-group">
                <label class="field-label">Brand *</label>
                <select class="field-input" name="brand_id" required>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" @selected(old('brand_id', $product->brand_id) == $brand->id)>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field-group"><label class="field-label">Name *</label><input class="field-input" name="name" value="{{ old('name', $product->name) }}" required></div>
            <div class="field-group"><label class="field-label">SKU *</label><input class="field-input" name="sku" value="{{ old('sku', $product->sku) }}" required></div>
            <div class="field-group"><label class="field-label">Slug</label><input class="field-input" name="slug" value="{{ old('slug', $product->slug) }}"></div>
            <div class="field-group"><label class="field-label">Price *</label><input class="field-input" type="number" step=".01" min="0" name="price" value="{{ old('price', $product->price) }}" required></div>
            <div class="field-group"><label class="field-label">Old price</label><input class="field-input" type="number" step=".01" min="0" name="compare_at_price" value="{{ old('compare_at_price', $product->compare_at_price) }}"></div>
            <div class="field-group"><label class="field-label">Stock *</label><input class="field-input" type="number" min="0" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}" required></div>

            <div class="field-group">
                <label class="field-label">Product images</label>
                <input class="field-input" type="file" name="product_images[]" accept="image/*" multiple>
                @if($product->exists && $product->getMedia('images')->isNotEmpty())
                    <small>{{ $product->getMedia('images')->count() }} image(s) uploaded. Upload more to add to gallery.</small>
                @endif
            </div>

            <div class="field-group"><label class="field-label">Description</label><textarea class="field-input" name="description" rows="5">{{ old('description', $product->description) }}</textarea></div>

            <h3 class="form-card-title mt-4">Colour and sizes</h3>
            <div class="field-group"><label class="field-label">Primary colour</label><input class="field-input" name="colour" value="{{ old('colour', $product->colour) }}" placeholder="Black"></div>
            <div class="field-group"><label class="field-label">Available colours</label><input class="field-input" name="available_colours" value="{{ old('available_colours', implode(', ', $product->available_colours ?? [])) }}" placeholder="Black, White, Blue"></div>
            <div class="field-group"><label class="field-label">Available sizes</label><input class="field-input" name="available_sizes" value="{{ old('available_sizes', implode(', ', $product->available_sizes ?? [])) }}" placeholder="S, M, L, XL"></div>

            <h3 class="form-card-title mt-4">Product details</h3>
            @foreach(['closure_type' => 'Closure type', 'fashion_type' => 'Fashion type', 'hemline' => 'Hemline', 'knit_or_woven' => 'Knit or woven', 'product_length' => 'Product length', 'season' => 'Season', 'transparency' => 'Transparency', 'stretchability' => 'Stretchability', 'wash_care' => 'Wash care', 'fit_type' => 'Fit type', 'fabric_details' => 'Fabric details', 'fabric_composition' => 'Fabric composition', 'occasion' => 'Occasion', 'pattern_type' => 'Pattern type', 'sleeve_length' => 'Sleeve length'] as $field => $label)
                <div class="field-group">
                    <label class="field-label">{{ $label }}</label>
                    <input class="field-input" name="{{ $field }}" value="{{ old($field, $product->$field) }}">
                </div>
            @endforeach

            <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->exists ? $product->is_active : true))> Active</label>
            &nbsp;
            <label><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))> Featured on home</label>
        </div>
    </div>

    <div class="form-actions">
        <button class="btn-primary">Save Product</button>
        <a class="btn-ghost" href="{{ route('admin.products.index') }}">Cancel</a>
    </div>
</form>
@endsection
