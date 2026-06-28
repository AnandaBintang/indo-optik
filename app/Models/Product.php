<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'discount_price',
        'stock',
        'sku',
        'image',
        'status',
        'is_featured',
        'meta_title',
        'meta_description',
        'color_variants',
        'lens_variants',
        'frame_variants',
    ];

    protected $appends = [
        'effective_price',
        'discount_percent',
    ];

    protected function casts(): array
    {
        return [
            'price'          => 'integer',
            'discount_price' => 'integer',
            'stock'          => 'integer',
            'is_featured'    => 'boolean',
            'color_variants' => 'array',
            'lens_variants'  => 'array',
            'frame_variants' => 'array',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    /**
     * Returns the effective selling price (discount price when set, otherwise regular price).
     */
    public function getEffectivePriceAttribute(): int
    {
        return $this->discount_price ?? $this->price;
    }

    /**
     * Returns the discount percentage relative to the original price.
     * Returns 0 if no discount is applied.
     */
    public function getDiscountPercentAttribute(): int
    {
        if (! $this->discount_price || $this->price <= 0) {
            return 0;
        }

        return (int) round((($this->price - $this->discount_price) / $this->price) * 100);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /**
     * Scope: only active products.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: only featured products.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getImageUrlAttribute()
    {
        $path = $this->image ?? $this->photo;
        if (!$path) return null;
        return \Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])
            ? $path
            : asset('storage/' . $path);
    }

    public function getSafeDescriptionHtmlAttribute(): string
    {
        $html = (string) ($this->description ?? '');
        $html = preg_replace('/<(script|style)\b[^>]*>.*?<\/\1>/is', '', $html) ?? '';
        $html = strip_tags($html, '<p><br><strong><b><em><i><ul><ol><li><a>');
        $html = preg_replace('/<(p|br|strong|b|em|i|ul|ol|li)\b[^>]*>/i', '<$1>', $html) ?? '';
        $html = preg_replace_callback('/<a\b([^>]*)>/i', function (array $matches): string {
            preg_match('/\s+href\s*=\s*([\'"])(.*?)\1/i', $matches[1], $hrefMatch);
            $href = $hrefMatch[2] ?? '';

            if (! preg_match('/^(https?:|mailto:|tel:|#)/i', $href)) {
                return '<a>';
            }

            return '<a href="' . e($href) . '" rel="noopener noreferrer">';
        }, $html) ?? '';

        return trim($html) !== '' ? $html : e($this->short_description ?? '');
    }

}
