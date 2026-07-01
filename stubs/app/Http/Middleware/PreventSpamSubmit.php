<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PreventSpamSubmit
{
    /**
     * Waktu tunggu (dalam detik) sebelum user bisa submit data lagi.
     * Ubah angka ini untuk mengubah waktu secara global (Frontend & Backend).
     */
    public const COOLDOWN_SECONDS = 3;

    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {

            $userId = $request->user()?->id ?? $request->ip();
            $key = 'spam_lock_'.$userId;

            if (Cache::has($key)) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Terlalu cepat! Harap tunggu beberapa detik.',
                    ], 429);
                }

                return back()->with('error', 'Anda menyimpan data terlalu cepat. Harap tunggu sebentar.');
            }

            Cache::put($key, true, self::COOLDOWN_SECONDS);
        }

        return $next($request);
    }
}
