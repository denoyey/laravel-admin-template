<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        if (RateLimiter::tooManyAttempts($request->ip(), LoginRequest::MAX_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($request->ip());
            $decaySeconds = LoginRequest::DECAY_MINUTES * 60;
            abort(429, '', ['Retry-After' => $seconds ?: $decaySeconds]);
        }

        return view('pages.admin.auth.login');
    }

    public function login(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $request->session()->flash('login_success', true);

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson() || $request->ajax()) {
            if ($request->has('is_idle')) {
                session()->flash('idle_timeout', 'Anda sudah tidak aktif. Silahkan login kembali.');
            }
            return response()->json(['redirect' => route('admin.login', ['logged_out' => 1])]);
        }

        if ($request->has('is_idle')) {
            return redirect()->route('admin.login', ['logged_out' => 1])->with('idle_timeout', 'Anda sudah tidak aktif. Silahkan login kembali.');
        }

        return redirect()->route('admin.login', ['logged_out' => 1]);
    }

    public function keepAlive()
    {
        return response()->json(['status' => 'ok', 'message' => 'Session extended']);
    }
}
