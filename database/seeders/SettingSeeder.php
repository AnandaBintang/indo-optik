<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'site_name',       'value' => 'IndoOptik',                              'group' => 'general'],
            ['key' => 'site_description','value' => 'Kacamata & Lensa Berkualitas Terbaik',   'group' => 'general'],
            ['key' => 'copyright_year',  'value' => '2026',                                   'group' => 'general'],

            // Contact
            ['key' => 'whatsapp_number', 'value' => '6281234567890',                          'group' => 'contact'],
            ['key' => 'address',         'value' => 'Jl. Optik Utama No. 123, Jakarta Pusat, 10110', 'group' => 'contact'],
            ['key' => 'email',           'value' => 'info@indooptik.com',                     'group' => 'contact'],
            ['key' => 'phone',           'value' => '+62 812-3456-7890',                      'group' => 'contact'],

            // Social
            ['key' => 'facebook_url',   'value' => '',                                        'group' => 'social'],
            ['key' => 'instagram_url',  'value' => '',                                        'group' => 'social'],
            ['key' => 'tiktok_url',     'value' => '',                                        'group' => 'social'],

            // SEO
            ['key' => 'meta_title',       'value' => 'IndoOptik — Kacamata & Lensa Berkualitas Terbaik',          'group' => 'seo'],
            ['key' => 'meta_description', 'value' => 'IndoOptik menyediakan kacamata dan lensa berkualitas tinggi dengan harga terjangkau.', 'group' => 'seo'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'group' => $setting['group']]
            );
        }
    }
}
