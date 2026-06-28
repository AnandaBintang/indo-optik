<?php

use App\Models\Category;
use App\Models\Product;

test('product detail uses a relative cart endpoint for csp safe fetches', function () {
    $this->withoutVite();

    $category = Category::create([
        'name' => 'Frame',
        'slug' => 'frame-cart-url',
        'status' => 'active',
    ]);

    $product = Product::factory()->create([
        'category_id' => $category->id,
        'slug' => 'cart-url-product',
        'status' => 'active',
    ]);

    $response = $this->get(route('products.show', $product->slug));

    $response
        ->assertOk()
        ->assertSee('data-cart-url="/keranjang/tambah"', false)
        ->assertDontSee('data-cart-url="http://', false)
        ->assertDontSee('data-cart-url="https://', false);
});
