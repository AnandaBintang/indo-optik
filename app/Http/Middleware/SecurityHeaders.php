<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $headers = $response->headers;
        $headers->set('X-Content-Type-Options', 'nosniff');
        $headers->set('X-Frame-Options', 'SAMEORIGIN');
        $headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');
        $headers->set('Content-Security-Policy', $this->contentSecurityPolicy());

        if ($request->isSecure()) {
            $headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }

    private function contentSecurityPolicy(): string
    {
        $viteSources = [];
        $scriptEval = '';

        if (app()->environment('local')) {
            $viteSources = [
                'http://localhost:5173',
                'http://127.0.0.1:5173',
                'ws://localhost:5173',
                'ws://127.0.0.1:5173',
            ];
            $scriptEval = " 'unsafe-eval'";
        }

        return implode('; ', [
            "default-src 'self'",
            "base-uri 'self'",
            "object-src 'none'",
            "frame-ancestors 'self'",
            "script-src 'self' 'unsafe-inline'" . $scriptEval . $this->appendSources($viteSources),
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com" . $this->appendSources($viteSources),
            "font-src 'self' https://fonts.gstatic.com data:" . $this->appendSources($viteSources),
            "img-src 'self' data: blob: https:",
            "connect-src 'self'" . $this->appendSources($viteSources),
            "form-action 'self' https://wa.me",
            "upgrade-insecure-requests",
        ]);
    }

    /**
     * @param array<int, string> $sources
     */
    private function appendSources(array $sources): string
    {
        if ($sources === []) {
            return '';
        }

        return ' ' . implode(' ', $sources);
    }
}
