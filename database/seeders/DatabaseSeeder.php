<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\SettingSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\TestimonialSeeder;
use Database\Seeders\PromoCodeSeeder;
use Database\Seeders\UserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SettingSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            TestimonialSeeder::class,
            PromoCodeSeeder::class,
            UserSeeder::class,
        ]);
    }
}
