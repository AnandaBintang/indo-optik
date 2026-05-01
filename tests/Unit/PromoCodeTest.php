<?php

use App\Models\PromoCode;
use Illuminate\Support\Carbon;

uses(Tests\TestCase::class);

test('percentage promo discounts respect the max discount cap', function () {
    $promo = new PromoCode([
        'type' => 'percentage',
        'value' => 50,
        'max_discount' => 20_000,
    ]);

    expect($promo->calculateDiscount(100_000))->toBe(20_000.0);
});

test('fixed promo discounts cannot exceed the subtotal', function () {
    $promo = new PromoCode([
        'type' => 'fixed',
        'value' => 75_000,
    ]);

    expect($promo->calculateDiscount(50_000))->toBe(50_000.0);
});

test('promo validity checks active state expiry usage limit and minimum purchase', function () {
    $validPromo = new PromoCode([
        'is_active' => true,
        'expired_at' => Carbon::now()->addDay(),
        'usage_limit' => 10,
        'usage_count' => 2,
        'min_purchase' => 100_000,
    ]);

    $expiredPromo = new PromoCode([
        'is_active' => true,
        'expired_at' => Carbon::now()->subDay(),
    ]);

    $exhaustedPromo = new PromoCode([
        'is_active' => true,
        'usage_limit' => 3,
        'usage_count' => 3,
    ]);

    expect($validPromo->isValid(150_000))->toBeTrue()
        ->and($validPromo->isValid(50_000))->toBeFalse()
        ->and($expiredPromo->isValid(150_000))->toBeFalse()
        ->and($exhaustedPromo->isValid(150_000))->toBeFalse();
});
