<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $products = [
            'Kacamata Rayban Classic',
            'Frame Titanium Premium',
            'Kacamata Anti Radiasi Slim',
            'Frame Bulat Vintage Retro',
            'Kacamata Sporty Pria',
            'Frame Cat Eye Wanita',
            'Kacamata Photochromic UV400',
            'Frame Full Rim Klasik',
            'Kacamata Baca Elegan',
            'Frame Half Rim Modern',
            'Kacamata Pilot Aviator',
            'Frame Wajah Oval Minimalis',
            'Kacamata Kotak Formal',
            'Lensa Progressif Premium',
            'Lensa Blue Light Filter',
            'Lensa Transisi Otomatis',
            'Lensa Anti Radiasi Digital',
            'Kontak Lensa Harian Comfort',
            'Kontak Lensa Bulanan Premium',
            'Kontak Lensa Berwarna Natural',
        ];

        $name  = fake()->randomElement($products) . ' ' . fake()->bothify('??-###');
        $slug  = Str::slug($name) . '-' . fake()->unique()->numberBetween(1000, 9999);
        $price = fake()->numberBetween(150_000, 2_500_000);

        $hasDiscount   = fake()->boolean(40);
        $discountPrice = $hasDiscount
            ? (int) ($price * fake()->randomFloat(2, 0.60, 0.90))
            : null;

        $unsplashIds = [
            'photo-1574258495973-f010dfbb5371',
            'photo-1508296695146-257a814070b4',
            'photo-1511499767150-a48a237f0083',
            'photo-1577744486770-020ab432da65',
            'photo-1584036561566-baf8f5f1b144',
            'photo-1603539947678-cd3954ed515d',
            'photo-1581833971358-2c8b550f87b3',
            'photo-1556306535-0f09a537f0a3',
            'photo-1591076482161-42ce6da69f67',
            'photo-1473496169904-658ba7574b0d',
        ];

        $imageId = fake()->randomElement($unsplashIds);

        $skuPrefix = fake()->randomElement(['KM', 'LN', 'KL', 'FR']);
        $sku       = strtoupper($skuPrefix . '-' . fake()->bothify('??###'));

        return [
            'category_id'       => Category::inRandomOrder()->first()?->id,
            'name'              => $name,
            'slug'              => $slug,
            'description'       => fake()->paragraphs(3, true),
            'short_description' => fake()->sentence(12),
            'price'             => $price,
            'discount_price'    => $discountPrice,
            'stock'             => fake()->numberBetween(0, 100),
            'sku'               => $sku . '-' . fake()->unique()->numberBetween(100, 999),
            'image'             => 'https://images.unsplash.com/' . $imageId . '?auto=format&fit=crop&w=800&q=80',
            'status'            => fake()->randomElement(['active', 'active', 'active', 'inactive']),
            'is_featured'       => fake()->boolean(25),
            'meta_title'        => $name . ' — IndoOptik',
            'meta_description'  => 'Beli ' . $name . ' dengan harga terbaik di IndoOptik. Kualitas terjamin dan bergaransi resmi.',
        ];
    }

    /**
     * State: product is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * State: product is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    /**
     * State: product is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
            'status'      => 'active',
        ]);
    }

    /**
     * State: product has no discount.
     */
    public function withoutDiscount(): static
    {
        return $this->state(fn (array $attributes) => [
            'discount_price' => null,
        ]);
    }

    /**
     * State: product has a discount.
     */
    public function withDiscount(float $percentage = 0.20): static
    {
        return $this->state(function (array $attributes) use ($percentage) {
            $price = $attributes['price'];

            return [
                'discount_price' => (int) ($price * (1 - $percentage)),
            ];
        });
    }

    /**
     * State: product is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }
}
