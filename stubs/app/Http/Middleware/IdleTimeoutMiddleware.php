<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IdleTimeoutMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $isEnabled = config('session.idle_timeout_enabled', env('IDLE_TIMEOUT_ENABLED', false));

            if ($isEnabled) {
                $timeoutMinutes = (float) config('session.idle_timeout_minutes', env('IDLE_TIMEOUT_MINUTES', 10));
                $timeoutSeconds = $timeoutMinutes * 60;

                $lastActivity = session('last_activity_time');

                if ($lastActivity) {
                    $timeElapsed = time() - $lastActivity;

                    if ($timeElapsed > ($timeoutSeconds + 60 + 5)) {
                        Auth::logout();
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();

                        if ($request->expectsJson() || $request->ajax()) {
                            return response()->json(['message' => 'Session expired due to inactivity'], 401);
                        }

                        return redirect()->route('admin.login', ['logged_out' => 1])->with('idle_timeout', 'Anda sudah tidak aktif. Silahkan login kembali.');
                    }
                }

                if (! $request->hasHeader('X-Session-Sync')) {
                    session(['last_activity_time' => time()]);
                }
            }
        }

        return $next($request);
    }
}
