<?php

namespace App\Services;

use App\Models\PromoCode;

class PromoService
{
    /**
     * Validate a promo code against a given subtotal without incrementing usage.
     *
     * @return array{valid: bool, promo: PromoCode|null, message: string, discount: float}
     */
    public function validate(string $code, float $subtotal): array
    {
        $promo = PromoCode::where('code', strtoupper(trim($code)))->first();

        if (! $promo) {
            return [
                'valid'    => false,
                'promo'    => null,
                'message'  => 'Kode promo tidak ditemukan.',
                'discount' => 0.0,
            ];
        }

        if (! $promo->is_active) {
            return [
                'valid'    => false,
                'promo'    => $promo,
                'message'  => 'Kode promo tidak aktif.',
                'discount' => 0.0,
            ];
        }

        if ($promo->expired_at !== null && $promo->expired_at->isPast()) {
            return [
                'valid'    => false,
                'promo'    => $promo,
                'message'  => 'Kode promo sudah kedaluwarsa.',
                'discount' => 0.0,
            ];
        }

        if ($promo->usage_limit !== null && $promo->usage_count >= $promo->usage_limit) {
            return [
                'valid'    => false,
                'promo'    => $promo,
                'message'  => 'Kode promo sudah mencapai batas penggunaan.',
                'discount' => 0.0,
            ];
        }

        if ($subtotal < (float) $promo->min_purchase) {
            $minFormatted = 'Rp ' . number_format((float) $promo->min_purchase, 0, ',', '.');

            return [
                'valid'    => false,
                'promo'    => $promo,
                'message'  => "Minimum pembelian untuk promo ini adalah {$minFormatted}.",
                'discount' => 0.0,
            ];
        }

        $discount = $promo->calculateDiscount($subtotal);

        return [
            'valid'    => true,
            'promo'    => $promo,
            'message'  => 'Kode promo berhasil diterapkan.',
            'discount' => $discount,
        ];
    }

    /**
     * Validate a promo code and, if valid, increment its usage count.
     *
     * @return array{valid: bool, promo: PromoCode|null, message: string, discount: float}
     */
    public function apply(string $code, float $subtotal): array
    {
        $result = $this->validate($code, $subtotal);

        if ($result['valid'] && $result['promo'] !== null) {
            $result['promo']->increment('usage_count');
        }

        return $result;
    }
}
