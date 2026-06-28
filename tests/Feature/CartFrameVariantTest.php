<?php

use App\Models\Category;
use App\Models\Product;

test("cart stores selected frame type and includes frame price in subtotal", function () {
    $category = Category::create([
        "name" => "Frame",
        "slug" => "frame",
        "status" => "active",
    ]);

    $product = Product::factory()->create([
        "category_id" => $category->id,
        "name" => "Frame Cart Product",
        "price" => 500000,
        "discount_price" => null,
        "status" => "active",
        "frame_variants" => [
            [
                "key" => "titanium",
                "label" => "Titanium",
                "desc" => "Ringan",
                "priceAddon" => 200000,
                "icon" => "fa-solid fa-feather",
            ],
        ],
    ]);

    $response = $this->post(route("cart.add"), [
        "product_id" => $product->id,
        "frame_type" => "Titanium",
        "frame_price" => 200000,
        "lens_type" => "Blue Light",
        "lens_price" => 150000,
        "color" => "Hitam",
        "quantity" => 2,
        "delivery_type" => "pickup",
    ]);

    $response->assertRedirect();

    $items = session("cart");
    $item = array_values($items)[0];

    expect($item["frame_type"])->toBe("Titanium")
        ->and($item["frame_price"])->toBe(200000)
        ->and(app(\App\Services\CartService::class)->getSubtotal())->toBe(1700000);
});

test("cart add returns a success payload for json requests", function () {
    $category = Category::create([
        "name" => "Frame",
        "slug" => "frame-json",
        "status" => "active",
    ]);

    $product = Product::factory()->create([
        "category_id" => $category->id,
        "name" => "JSON Cart Product",
        "price" => 500000,
        "discount_price" => null,
        "status" => "active",
    ]);

    $response = $this->postJson(route("cart.add"), [
        "product_id" => $product->id,
        "frame_type" => "Standar",
        "frame_price" => 0,
        "lens_type" => "Standar",
        "lens_price" => 0,
        "color" => "Hitam",
        "quantity" => 1,
        "delivery_type" => "pickup",
    ]);

    $response
        ->assertOk()
        ->assertJsonPath("success", true)
        ->assertJsonPath("cart_count", 1)
        ->assertJsonPath("message", "Produk berhasil ditambahkan ke keranjang!");
});

test("whatsapp message includes selected frame type and frame price", function () {
    $cart = app(\App\Services\CartService::class);

    $cart->add([
        "product_id" => 10,
        "product_name" => "Frame WA Product",
        "product_price" => 500000,
        "frame_type" => "Titanium",
        "frame_price" => 200000,
        "lens_type" => "Blue Light",
        "lens_price" => 150000,
        "color" => "Hitam",
        "quantity" => 1,
        "delivery_type" => "delivery",
    ]);

    $url = $cart->buildWhatsAppMessage("Bintang", waNumber: "628111111111");
    $message = urldecode(parse_url($url, PHP_URL_QUERY));

    expect($message)->toContain("Frame     : Titanium")
        ->and($message)->toContain("Harga Tipe Frame: Rp 200.000")
        ->and($message)->toContain("Subtotal   : Rp 850.000");
});
