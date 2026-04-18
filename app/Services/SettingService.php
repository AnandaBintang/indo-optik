<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    const CACHE_KEY_ALL   = 'settings_all';
    const CACHE_KEY_GROUP = 'settings_group_';
    const CACHE_KEY_SINGLE = 'setting_';

    /**
     * Get a single setting value by key.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever(self::CACHE_KEY_SINGLE . $key, function () use ($key, $default) {
            $setting = Setting::where('key', $key)->first();

            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set (insert or update) a setting value by key.
     */
    public function set(string $key, mixed $value, string $group = 'general'): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );

        // Invalidate related caches
        Cache::forget(self::CACHE_KEY_SINGLE . $key);
        Cache::forget(self::CACHE_KEY_GROUP . $group);
        Cache::forget(self::CACHE_KEY_ALL);
    }

    /**
     * Get all settings as a flat key => value array.
     *
     * @return array<string, mixed>
     */
    public function getAll(): array
    {
        return Cache::rememberForever(self::CACHE_KEY_ALL, function () {
            return Setting::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Get all settings belonging to a specific group as key => value array.
     *
     * @param  string  $group
     * @return array<string, mixed>
     */
    public function getGroup(string $group): array
    {
        return Cache::rememberForever(self::CACHE_KEY_GROUP . $group, function () use ($group) {
            return Setting::where('group', $group)
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    /**
     * Clear all setting-related caches.
     */
    public function clearCache(): void
    {
        // Clear the global "all" cache
        Cache::forget(self::CACHE_KEY_ALL);

        // Clear every individual key cache and every group cache
        $settings = Setting::select('key', 'group')->get();

        $flushedGroups = [];

        foreach ($settings as $setting) {
            Cache::forget(self::CACHE_KEY_SINGLE . $setting->key);

            if (! in_array($setting->group, $flushedGroups, true)) {
                Cache::forget(self::CACHE_KEY_GROUP . $setting->group);
                $flushedGroups[] = $setting->group;
            }
        }
    }
}
