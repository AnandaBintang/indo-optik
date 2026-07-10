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

test('product description preserves wysiwyg lists and strips unsafe html', function () {
    $this->withoutVite();

    $category = Category::create([
        'name' => 'Frame Premium',
        'slug' => 'frame-premium',
        'status' => 'active',
    ]);

    $product = Product::factory()->create([
        'category_id' => $category->id,
        'slug' => 'wysiwyg-description',
        'status' => 'active',
        'description' => implode('', [
            '<h1>Keunggulan</h1>',
            '<ul><li><strong>Ringan</strong></li><li>Anti radiasi</li></ul>',
            '<ol><li>Ukur wajah</li><li>Pilih lensa</li></ol>',
            '<p><a href="javascript:alert(1)" onclick="alert(2)">Link buruk</a></p>',
            '<script>alert("xss")</script>',
        ]),
    ]);

    $response = $this->get(route('products.show', $product->slug));

    $response
        ->assertOk()
        ->assertSee('<h1>Keunggulan</h1>', false)
        ->assertSee('<ul><li><strong>Ringan</strong></li><li>Anti radiasi</li></ul>', false)
        ->assertSee('<ol><li>Ukur wajah</li><li>Pilih lensa</li></ol>', false)
        ->assertSee('<a>Link buruk</a>', false)
        ->assertDontSee('javascript:alert', false)
        ->assertDontSee('onclick', false)
        ->assertDontSee('alert("xss")', false);
});
