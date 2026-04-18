<?php

namespace Database\Seeders;

use App\Models\PromoCode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromoCodeSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $promoCodes = [
            [
                'code'         => 'HEMAT10',
                'label'        => 'Hemat 10% untuk semua produk',
                'type'         => 'percentage',
                'value'        => 10.00,
                'max_discount' => 100000.00,
                'min_purchase' => 0.00,
                'expired_at'   => null,
                'usage_limit'  => null,
                'usage_count'  => 0,
                'is_active'    => true,
            ],
            [
                'code'         => 'LENSA50',
                'label'        => 'Potongan Rp 50.000 untuk pembelian lensa',
                'type'         => 'fixed',
                'value'        => 50000.00,
                'max_discount' => null,
                'min_purchase' => 300000.00,
                'expired_at'   => null,
                'usage_limit'  => null,
                'usage_count'  => 0,
                'is_active'    => true,
            ],
            [
                'code'         => 'BARU30',
                'label'        => 'Diskon 30% untuk pelanggan baru',
                'type'         => 'percentage',
                'value'        => 30.00,
                'max_discount' => 200000.00,
                'min_purchase' => 0.00,
                'expired_at'   => null,
                'usage_limit'  => null,
                'usage_count'  => 0,
                'is_active'    => true,
            ],
        ];

        foreach ($promoCodes as $promoCode) {
            PromoCode::updateOrCreate(
                ['code' => $promoCode['code']],
                $promoCode,
            );
        }

        $this->command->info('PromoCodeSeeder: ' . count($promoCodes) . ' promo codes seeded successfully.');
    }
}
