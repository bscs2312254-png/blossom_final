<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'category_id',
        'category', // Keep for backward compatibility
        'featured',
        'stock'
    ];

    protected $casts = [
        'featured' => 'boolean',
        'price' => 'decimal:2'
    ];

    // Relationship with Category
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Accessor for image URL
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('images/' . $this->image);
        }
        return asset('images/default.jpg');
    }

    // Accessor for category name (fallback)
    public function getCategoryNameAttribute()
    {
        if ($this->categoryRelation) {
            return $this->categoryRelation->name;
        }
        return $this->category; // Fallback to old field
    }

    // Scope for featured products
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    // Scope for active products
    public function scopeActive($query)
    {
        return $query->where('stock', '>', 0);
    }
}