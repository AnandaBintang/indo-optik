<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Kacamata',
                'slug'        => 'kacamata',
                'description' => 'Koleksi kacamata berkualitas tinggi untuk berbagai kebutuhan, mulai dari kacamata minus, plus, silinder, hingga kacamata fashion terkini.',
                'status'      => 'active',
            ],
            [
                'name'        => 'Lensa',
                'slug'        => 'lensa',
                'description' => 'Berbagai pilihan lensa kacamata terbaik, termasuk lensa single vision, progressive, photochromic, dan anti-radiasi untuk kenyamanan penglihatan optimal.',
                'status'      => 'active',
            ],
            [
                'name'        => 'Kontak Lensa',
                'slug'        => 'kontak-lensa',
                'description' => 'Kontak lensa berkualitas untuk koreksi penglihatan dan estetika, tersedia dalam pilihan harian, bulanan, dan tahunan dengan berbagai warna.',
                'status'      => 'active',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
