<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (method_exists($response, 'header')) {
            $response->header('X-Frame-Options', 'SAMEORIGIN');
            $response->header('X-Content-Type-Options', 'nosniff');
            $response->header('Referrer-Policy', 'strict-origin-when-cross-origin');
            $response->header('X-XSS-Protection', '1; mode=block');
            $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
            $response->header('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');


            $viteHost = app()->environment('local') ? ' http://127.0.0.1:* http://localhost:*' : '';
            $wsHost = app()->environment('local') ? ' wss: ws:' : '';

            $csp = "default-src 'self'; ";
            $csp .= "img-src 'self' data: blob: https:$viteHost; ";
            $csp .= "script-src 'self' 'unsafe-inline' https:$viteHost; ";
            $csp .= "style-src 'self' 'unsafe-inline' https:$viteHost; ";
            $csp .= "font-src 'self' data: https:$viteHost; ";
            $csp .= "connect-src 'self' https:$wsHost$viteHost; ";
            $csp .= "frame-src 'self' https:;";

            $response->header('Content-Security-Policy', $csp);
        }

        return $response;
    }
}
