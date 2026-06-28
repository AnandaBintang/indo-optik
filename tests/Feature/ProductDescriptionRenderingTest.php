<?php

use App\Models\Category;
use App\Models\Product;

test('product description renders allowed formatting without visible html tags', function () {
    $this->withoutVite();

    $category = Category::create([
        'name' => 'Frame',
        'slug' => 'frame',
        'status' => 'active',
    ]);

    $product = Product::factory()->create([
        'category_id' => $category->id,
        'slug' => 'formatted-description',
        'status' => 'active',
        'description' => '<p>Product <strong>description</strong></p><script>alert("xss")</script>',
    ]);

    $response = $this->get(route('products.show', $product->slug));

    $response
        ->assertOk()
        ->assertSee('<p>Product <strong>description</strong></p>', false)
        ->assertDontSee('alert("xss")', false)
        ->assertDontSee('&lt;p&gt;', false);
});
