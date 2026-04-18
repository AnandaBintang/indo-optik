<?php

namespace App\Http\Middleware;

use App\Services\SettingService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ShareSettingsToViews
{
    public function __construct(
        protected SettingService $settingService,
    ) {}

    /**
     * Handle an incoming request.
     *
     * Shares all settings and the WhatsApp number with every view so that
     * layouts and partials can access them without extra controller code.
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $settings = $this->settingService->getAll();
            $waNumber = $this->settingService->get('whatsapp_number', '6281234567890');
        } catch (\Throwable $e) {
            // If the settings table does not yet exist (e.g. during migrations),
            // fall back to safe defaults so the app doesn't crash.
            $settings = [];
            $waNumber = '6281234567890';
        }

        View::share('settings', $settings);
        View::share('waNumber', $waNumber);

        return $next($request);
    }
}
