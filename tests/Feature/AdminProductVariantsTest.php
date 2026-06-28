<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

function makeAdminUser(): User
{
    return User::factory()->create([
        "role" => User::ROLE_ADMIN,
    ]);
}

function makeActiveCategory(): Category
{
    return Category::create([
        "name" => "Kacamata Pria",
        "slug" => "kacamata-pria-" . fake()->unique()->numberBetween(100, 999),
        "status" => "active",
    ]);
}

test("admin can store product variants with lens icon and mixed color images", function () {
    Storage::fake("public");

    $admin = makeAdminUser();
    $category = makeActiveCategory();

    $response = $this->actingAs($admin)->post(route("admin.products.store"), [
        "name" => "Frame Variant Store",
        "category_id" => $category->id,
        "description" => "Desc",
        "short_description" => "Short desc",
        "price" => 450000,
        "stock" => 7,
        "sku" => "VR-ST-100",
        "status" => "active",
        "color_variants" => [
            [
                "key" => "hitam",
                "label" => "Hitam",
                "color" => "#111827",
                "images" => "https://cdn.example.com/hitam-1.jpg, https://cdn.example.com/hitam-2.jpg",
                "image_uploads" => [
                    UploadedFile::fake()->image("hitam-upload.jpg"),
                ],
            ],
        ],
        "lens_variants" => [
            [
                "key" => "bluelight",
                "label" => "Blue Light",
                "desc" => "Proteksi layar",
                "price" => 150000,
                "icon" => "fa-solid fa-display",
            ],
        ],
    ]);

    $response->assertRedirect(route("admin.products.index"));

    $product = Product::query()->where("name", "Frame Variant Store")->firstOrFail();
    $colorVariant = $product->color_variants[0];
    $lensVariant = $product->lens_variants[0];

    expect($lensVariant["icon"])->toBe("fa-solid fa-display")
        ->and($lensVariant["priceAddon"])->toBe(150000)
        ->and($colorVariant["images"])->toHaveCount(3)
        ->and($colorVariant["images"][0])->toBe("https://cdn.example.com/hitam-1.jpg")
        ->and($colorVariant["images"][2])->toStartWith("/storage/products/variants/");
});

test("admin can store product with supported five megabyte image upload", function () {
    Storage::fake("public");

    $admin = makeAdminUser();
    $category = makeActiveCategory();

    $image = UploadedFile::fake()->image("main.jpg")->size(5120);

    $response = $this->actingAs($admin)->post(route("admin.products.store"), [
        "name" => "Large Image Product",
        "category_id" => $category->id,
        "description" => "Desc",
        "short_description" => "Short desc",
        "price" => 450000,
        "stock" => 7,
        "sku" => "LG-IMG-100",
        "status" => "active",
        "image" => $image,
    ]);

    $response->assertRedirect(route("admin.products.index"));

    $product = Product::query()->where("name", "Large Image Product")->firstOrFail();

    expect($product->image)->not->toBeNull();
    Storage::disk("public")->assertExists($product->image);
});

test("admin can update product variants and keep uploaded color image references", function () {
    Storage::fake("public");

    $admin = makeAdminUser();
    $category = makeActiveCategory();
    $product = Product::factory()->create([
        "category_id" => $category->id,
        "name" => "Frame Variant Update",
        "slug" => "frame-variant-update",
        "sku" => "VR-UP-100",
        "color_variants" => null,
        "lens_variants" => null,
    ]);

    $response = $this->actingAs($admin)->put(route("admin.products.update", $product->id), [
        "name" => "Frame Variant Update",
        "category_id" => $category->id,
        "description" => "Desc",
        "short_description" => "Short desc",
        "price" => 500000,
        "stock" => 12,
        "sku" => "VR-UP-100",
        "status" => "active",
        "color_variants" => [
            [
                "key" => "coklat",
                "label" => "Coklat",
                "color" => "#8B4513",
                "images" => "https://cdn.example.com/coklat-main.jpg",
                "image_uploads" => [
                    UploadedFile::fake()->image("coklat-upload.jpg"),
                ],
            ],
        ],
        "lens_variants" => [
            [
                "key" => "photochromic",
                "label" => "Photochromic",
                "desc" => "Adaptif cahaya",
                "price" => 250000,
                "icon" => "fa-solid fa-sun",
            ],
        ],
    ]);

    $response->assertRedirect(route("admin.products.index"));

    $product->refresh();
    $colorVariant = $product->color_variants[0];
    $lensVariant = $product->lens_variants[0];

    expect($lensVariant["icon"])->toBe("fa-solid fa-sun")
        ->and($lensVariant["priceAddon"])->toBe(250000)
        ->and($colorVariant["images"])->toHaveCount(2)
        ->and($colorVariant["images"][0])->toBe("https://cdn.example.com/coklat-main.jpg")
        ->and($colorVariant["images"][1])->toStartWith("/storage/products/variants/");
});

test("admin can store product frame variants", function () {
    $admin = makeAdminUser();
    $category = makeActiveCategory();

    $response = $this->actingAs($admin)->post(route("admin.products.store"), [
        "name" => "Frame Type Product",
        "category_id" => $category->id,
        "description" => "Desc",
        "short_description" => "Short desc",
        "price" => 650000,
        "stock" => 5,
        "sku" => "FR-TYPE-100",
        "status" => "active",
        "frame_variants" => [
            [
                "key" => "full-rim",
                "label" => "Full Rim",
                "desc" => "Frame penuh klasik",
                "price" => 0,
                "icon" => "fa-solid fa-glasses",
            ],
            [
                "key" => "titanium",
                "label" => "Titanium",
                "desc" => "Frame ringan premium",
                "price" => 250000,
                "icon" => "fa-solid fa-feather",
            ],
        ],
    ]);

    $response->assertRedirect(route("admin.products.index"));

    $product = Product::query()->where("name", "Frame Type Product")->firstOrFail();

    expect($product->frame_variants)->toHaveCount(2)
        ->and($product->frame_variants[0]["key"])->toBe("full-rim")
        ->and($product->frame_variants[0]["priceAddon"])->toBe(0)
        ->and($product->frame_variants[1]["label"])->toBe("Titanium")
        ->and($product->frame_variants[1]["priceAddon"])->toBe(250000);
});
