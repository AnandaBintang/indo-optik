<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(
        protected SettingService $settingService,
    ) {}

    /**
     * Display all settings grouped by their group key.
     */
    public function index(): View
    {
        $allSettings = Setting::orderBy('group')->orderBy('key')->get();

        // Group settings by their 'group' column into an associative array:
        // ['general' => Collection, 'contact' => Collection, ...]
        $grouped = $allSettings->groupBy('group');

        // Also expose a flat key => value map for easy Blade access
        $settings = $allSettings->pluck('value', 'key')->toArray();

        // Predefined groups with human-readable labels so the view can render
        // proper section headings even for empty groups.
        $groupLabels = [
            'general'  => 'Umum',
            'contact'  => 'Kontak & Lokasi',
            'social'   => 'Media Sosial',
            'seo'      => 'SEO & Meta',
            'homepage' => 'Halaman Utama',
            'shipping' => 'Pengiriman',
        ];

        return view('admin.settings.index', compact('grouped', 'settings', 'groupLabels'));
    }

    /**
     * Save all settings submitted from the settings form.
     *
     * The form is expected to send a flat `settings` array:
     *   settings[key] = value
     * along with an optional `groups` array:
     *   groups[key] = group_name
     *
     * If the groups array is not provided, the existing group is preserved
     * (or 'general' is used as a fallback for new keys).
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'settings'   => 'nullable|array',
            'settings.*' => 'nullable|string|max:5000',
            'groups'     => 'nullable|array',
            'groups.*'   => 'nullable|string|max:50',
        ]);

        $settingsInput = $request->input('settings', []);
        $groupsInput   = $request->input('groups', []);

        // Pre-fetch existing settings so we can preserve their groups
        $existing = Setting::pluck('group', 'key')->toArray();

        foreach ($settingsInput as $key => $value) {
            // Sanitize the key — only allow alphanumerics, underscores, and dots
            $key = preg_replace('/[^a-zA-Z0-9_.]/', '', $key);

            if (empty($key)) {
                continue;
            }

            if (str_ends_with($key, '_url') && trim((string) $value) === '#') {
                $value = '';
            }

            // Determine the group: prefer the submitted group, then the
            // existing group from the DB, then fall back to 'general'.
            $group = $groupsInput[$key]
                ?? $existing[$key]
                ?? 'general';

            $this->settingService->set($key, $value, $group);
        }

        // Clear all caches so the updated values propagate immediately
        $this->settingService->clearCache();

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Pengaturan berhasil disimpan.');
    }
}
