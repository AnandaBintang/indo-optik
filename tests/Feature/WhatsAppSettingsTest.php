<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Services\SettingService;

test('whatsapp setting is normalized for wa me links', function () {
    $service = app(SettingService::class);

    expect($service->normalizeWhatsAppNumber('+62 812-9999-0000'))->toBe('6281299990000')
        ->and($service->normalizeWhatsAppNumber('0812 9999 0000'))->toBe('6281299990000')
        ->and($service->normalizeWhatsAppNumber(''))->toBe(SettingService::DEFAULT_WHATSAPP_NUMBER);
});

test('product page exposes whatsapp number from settings', function () {
    $this->withoutVite();

    Setting::create([
        'key' => 'whatsapp_number',
        'value' => '+62 812-9999-0000',
        'group' => 'contact',
    ]);

    $category = Category::create([
        'name' => 'Frame',
        'slug' => 'frame-wa-settings',
        'status' => 'active',
    ]);

    $product = Product::factory()->create([
        'category_id' => $category->id,
        'slug' => 'wa-settings-product',
        'status' => 'active',
    ]);

    $response = $this->get(route('products.show', $product->slug));

    $response
        ->assertOk()
        ->assertSee('data-wa-number="6281299990000"', false)
        ->assertDontSee('data-wa-number="6281234567890"', false);
});

test('booking whatsapp url uses whatsapp number from settings', function () {
    Setting::create([
        'key' => 'whatsapp_number',
        'value' => '0812 9999 0000',
        'group' => 'contact',
    ]);

    $response = $this->postJson(route('services.booking.store'), [
        'service' => 'exam',
        'booking_date' => now()->toDateString(),
        'booking_time' => '10:00',
        'name' => 'Bintang',
        'phone' => '081234567890',
    ]);

    $response
        ->assertOk()
        ->assertJsonPath('success', true);

    expect($response->json('wa_url'))->toStartWith('https://wa.me/6281299990000?text=');
});
