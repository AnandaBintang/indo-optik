<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\PromoCode;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal       = fake()->numberBetween(150_000, 5_000_000);
        $discountAmount = fake()->boolean(30) ? fake()->numberBetween(10_000, 200_000) : 0;
        $total          = max(0, $subtotal - $discountAmount);

        $deliveryType = fake()->randomElement(['pickup', 'delivery']);

        return [
            'user_id'          => fake()->boolean(70) ? User::factory() : null,
            'customer_name'    => fake()->name(),
            'customer_phone'   => $this->indonesianPhone(),
            'promo_code_id'    => null,
            'subtotal'         => $subtotal,
            'discount_amount'  => $discountAmount,
            'total'            => $total,
            'status'           => fake()->randomElement([
                Order::STATUS_PENDING,
                Order::STATUS_PROCESSING,
                Order::STATUS_COMPLETED,
                Order::STATUS_CANCELLED,
            ]),
            'delivery_type'    => $deliveryType,
            'delivery_address' => $deliveryType === 'delivery' ? $this->indonesianAddress() : null,
            'notes'            => fake()->boolean(40) ? fake()->sentence(10) : null,
            'wa_message_sent'  => fake()->boolean(60),
        ];
    }

    // -------------------------------------------------------------------------
    // States
    // -------------------------------------------------------------------------

    /**
     * Order in pending status.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Order::STATUS_PENDING,
        ]);
    }

    /**
     * Order in processing status.
     */
    public function processing(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Order::STATUS_PROCESSING,
        ]);
    }

    /**
     * Order in completed status.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'          => Order::STATUS_COMPLETED,
            'wa_message_sent' => true,
        ]);
    }

    /**
     * Order in cancelled status.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Order::STATUS_CANCELLED,
        ]);
    }

    /**
     * Order placed by a guest (no user_id).
     */
    public function guest(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
        ]);
    }

    /**
     * Order with delivery.
     */
    public function withDelivery(): static
    {
        return $this->state(fn (array $attributes) => [
            'delivery_type'    => 'delivery',
            'delivery_address' => $this->indonesianAddress(),
        ]);
    }

    /**
     * Order for store pickup.
     */
    public function pickup(): static
    {
        return $this->state(fn (array $attributes) => [
            'delivery_type'    => 'pickup',
            'delivery_address' => null,
        ]);
    }

    /**
     * Order with a promo code applied.
     */
    public function withPromo(): static
    {
        return $this->state(function (array $attributes) {
            $subtotal       = $attributes['subtotal'];
            $discountAmount = (int) ($subtotal * 0.1);
            $total          = max(0, $subtotal - $discountAmount);

            return [
                'promo_code_id'   => PromoCode::factory(),
                'discount_amount' => $discountAmount,
                'total'           => $total,
            ];
        });
    }

    /**
     * Order with no discount applied.
     */
    public function noDiscount(): static
    {
        return $this->state(fn (array $attributes) => [
            'promo_code_id'   => null,
            'discount_amount' => 0,
            'total'           => $attributes['subtotal'],
        ]);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Generate a realistic Indonesian mobile phone number.
     */
    private function indonesianPhone(): string
    {
        $prefixes = ['0811', '0812', '0813', '0821', '0822', '0823', '0851', '0852', '0853',
                     '0814', '0815', '0816', '0855', '0856', '0857', '0858',
                     '0817', '0818', '0819', '0859', '0877', '0878'];

        $prefix = fake()->randomElement($prefixes);
        $suffix  = (string) fake()->numberBetween(10000000, 99999999);

        return $prefix . $suffix;
    }

    /**
     * Generate a realistic Indonesian delivery address.
     */
    private function indonesianAddress(): string
    {
        $streets = [
            'Jl. Sudirman', 'Jl. Thamrin', 'Jl. Gatot Subroto', 'Jl. Kuningan',
            'Jl. HR Rasuna Said', 'Jl. Casablanca', 'Jl. Fatmawati', 'Jl. Panjang',
            'Jl. Kebon Jeruk', 'Jl. Raya Bogor', 'Jl. Margonda Raya', 'Jl. Pahlawan',
            'Jl. Diponegoro', 'Jl. Veteran', 'Jl. Ahmad Yani', 'Jl. Pemuda',
        ];

        $cities = [
            'Jakarta Pusat', 'Jakarta Selatan', 'Jakarta Barat', 'Jakarta Utara',
            'Jakarta Timur', 'Depok', 'Bekasi', 'Tangerang', 'Bogor',
            'Bandung', 'Surabaya', 'Yogyakarta', 'Semarang', 'Medan',
        ];

        $street  = fake()->randomElement($streets);
        $no      = fake()->numberBetween(1, 250);
        $rt      = str_pad((string) fake()->numberBetween(1, 20), 3, '0', STR_PAD_LEFT);
        $rw      = str_pad((string) fake()->numberBetween(1, 15), 3, '0', STR_PAD_LEFT);
        $city    = fake()->randomElement($cities);
        $postal  = (string) fake()->numberBetween(10000, 99999);

        return "{$street} No. {$no}, RT {$rt}/RW {$rw}, {$city} {$postal}";
    }
}
