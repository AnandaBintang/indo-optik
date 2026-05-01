<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class PromoCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'label',
        'type',
        'value',
        'max_discount',
        'min_purchase',
        'expired_at',
        'usage_limit',
        'usage_count',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'expired_at'   => 'datetime',
            'value'        => 'decimal:2',
            'max_discount' => 'decimal:2',
            'min_purchase' => 'decimal:2',
            'is_active'    => 'boolean',
        ];
    }

    // -------------------------------------------------------------------------
    // Attribute mutators
    // -------------------------------------------------------------------------

    public function setValueAttribute($value): void
    {
        $this->attributes['value'] = $this->normalizeDecimalInput($value);
    }

    public function setMaxDiscountAttribute($value): void
    {
        $this->attributes['max_discount'] = $this->normalizeDecimalInput(
            $value,
            true,
        );
    }

    public function setMinPurchaseAttribute($value): void
    {
        $this->attributes['min_purchase'] =
            $this->normalizeDecimalInput($value) ?? '0.00';
    }

    private function normalizeDecimalInput($value, bool $nullable = false): ?string
    {
        if ($value === null || $value === '') {
            return $nullable ? null : '0.00';
        }

        $normalized = str_replace(' ', '', (string) $value);
        $normalized = str_replace(',', '.', $normalized);

        $lastDot = strrpos($normalized, '.');
        if ($lastDot !== false) {
            $normalized = str_replace('.', '', substr($normalized, 0, $lastDot))
                . substr($normalized, $lastDot);
        }

        $parts = explode('.', $normalized, 2);
        $integer = ltrim($parts[0], '0');
        if ($integer === '') {
            $integer = '0';
        }

        $decimal = $parts[1] ?? '';
        $decimal = substr($decimal . '00', 0, 2);

        return $integer . '.' . $decimal;
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /**
     * Only return promo codes that are currently usable:
     *  - is_active = true
     *  - expired_at is null OR still in the future
     *  - usage_limit is null OR usage_count < usage_limit
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->where(function (Builder $q) {
                $q->whereNull('expired_at')
                  ->orWhere('expired_at', '>', now());
            })
            ->where(function (Builder $q) {
                $q->whereNull('usage_limit')
                  ->orWhereColumn('usage_count', '<', 'usage_limit');
            });
    }

    // -------------------------------------------------------------------------
    // Business logic
    // -------------------------------------------------------------------------

    /**
     * Calculate the discount amount for a given subtotal.
     *
     * @param  float  $subtotal
     * @return float
     */
    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'percentage') {
            $discount = $subtotal * ((float) $this->value / 100);

            if ($this->max_discount !== null) {
                $discount = min($discount, (float) $this->max_discount);
            }
        } else {
            // fixed
            $discount = (float) $this->value;
        }

        // Discount cannot exceed the subtotal
        return min($discount, $subtotal);
    }

    /**
     * Determine whether this promo code is valid for a given subtotal.
     *
     * @param  float  $subtotal
     * @return bool
     */
    public function isValid(float $subtotal = 0): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->expired_at !== null && $this->expired_at->isPast()) {
            return false;
        }

        if ($this->usage_limit !== null && $this->usage_count >= $this->usage_limit) {
            return false;
        }

        if ($subtotal < (float) $this->min_purchase) {
            return false;
        }

        return true;
    }
}
