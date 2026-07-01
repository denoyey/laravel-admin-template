<?php

namespace App\Http\Requests\Auth;

use App\Rules\RecaptchaRule;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LoginRequest extends FormRequest
{
    /**
     * Pengaturan Rate Limiter terpusat
     * Ubah nilai ini jika ingin mengganti batas percobaan atau waktu blokir
     */
    public const MAX_ATTEMPTS = 5;

    public const DECAY_MINUTES = 1;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'g-recaptcha-response' => ['required', new RecaptchaRule],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'g-recaptcha-response.required' => 'Mohon centang verifikasi keamanan (reCAPTCHA).',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            $decaySeconds = self::DECAY_MINUTES * 60;
            RateLimiter::hit($this->throttleKeyIp(), $decaySeconds);
            RateLimiter::hit($this->throttleKeyEmail(), $decaySeconds);

            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        RateLimiter::clear($this->throttleKeyIp());
        RateLimiter::clear($this->throttleKeyEmail());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws HttpException
     */
    public function ensureIsNotRateLimited(): void
    {
        $decaySeconds = self::DECAY_MINUTES * 60;

        if (! empty($this->input('__ks'))) {
            sleep(rand(1, 2));
            abort(429, '', ['Retry-After' => $decaySeconds]);
        }

        $userAgent = $this->header('User-Agent');
        if (empty($userAgent) || strlen(trim($userAgent)) < 15) {
            sleep(rand(1, 3));
            abort(403, 'Akses Ditolak.');
        }

        if (! RateLimiter::tooManyAttempts($this->throttleKeyIp(), self::MAX_ATTEMPTS) &&
            ! RateLimiter::tooManyAttempts($this->throttleKeyEmail(), self::MAX_ATTEMPTS)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKeyIp());
        if ($seconds == 0) {
            $seconds = RateLimiter::availableIn($this->throttleKeyEmail());
        }

        abort(429, '', ['Retry-After' => $seconds ?: $decaySeconds]);
    }

    public function throttleKeyIp(): string
    {
        return 'login_ip|'.$this->ip();
    }

    public function throttleKeyEmail(): string
    {
        return Str::transliterate(Str::lower($this->input('email')));
    }
}
