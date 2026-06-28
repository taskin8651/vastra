<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'brand_id', 'name', 'slug', 'sku', 'description', 'price',
        'compare_at_price', 'stock_quantity', 'image_path', 'is_active', 'is_featured',
        'colour', 'available_colours', 'available_sizes', 'closure_type', 'fashion_type',
        'hemline', 'knit_or_woven', 'product_length', 'season', 'transparency',
        'stretchability', 'wash_care', 'fit_type', 'fabric_details', 'fabric_composition',
        'occasion', 'pattern_type', 'sleeve_length',
    ];

    protected $casts = [
        'price' => 'decimal:2', 'compare_at_price' => 'decimal:2',
        'is_active' => 'boolean', 'is_featured' => 'boolean',
        'available_colours' => 'array', 'available_sizes' => 'array',
    ];

    public function scopeActive($query) { return $query->where('is_active', true); }

    public function getRouteKeyName(): string { return 'slug'; }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
