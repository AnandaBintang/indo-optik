<?php

use App\Models\Category;
use App\Models\Product;

it('shows empty state when no products match', function () {
    Category::create([
        'name' => 'Kacamata',
        'slug' => 'kacamata',
        'description' => 'Kategori kacamata',
        'status' => 'active',
    ]);

    Product::factory()->active()->create([
        'name' => 'Frame Alpha',
        'slug' => 'frame-alpha',
        'sku' => 'KM-AAA-001',
    ]);

    $response = $this->get('/katalog?q=tidak-ada-produk');

    $response->assertOk();
    $response->assertSee('Produk tidak ditemukan');
    $response->assertDontSee('Classic Round Frame');
    $response->assertDontSee('Modern Square Frame');
});

it('shows products when search matches', function () {
    Category::create([
        'name' => 'Lensa',
        'slug' => 'lensa',
        'description' => 'Kategori lensa',
        'status' => 'active',
    ]);

    Product::factory()->active()->create([
        'name' => 'Lensa Premium',
        'slug' => 'lensa-premium',
        'sku' => 'LN-PRM-001',
    ]);

    $response = $this->get('/katalog?q=Lensa%20Premium');

    $response->assertOk();
    $response->assertSee('Lensa Premium');
    $response->assertDontSee('Produk tidak ditemukan');
});
