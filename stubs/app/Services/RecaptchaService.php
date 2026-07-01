<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    /**
     * @var string|null
     */
    protected $secretKey;

    public function __construct()
    {
        $this->secretKey = env('RECAPTCHA_SECRET_KEY');
    }

    /**
     * Verify the reCAPTCHA response token with Google API.
     *
     * @param  string  $token
     * @param  string|null  $ip
     * @return bool
     */
    public function verify($token, $ip = null)
    {
        if (empty($this->secretKey)) {
            Log::warning('reCAPTCHA Secret Key is not configured. Bypassing verification.');

            return true;
        }

        if (empty($token)) {
            return false;
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $this->secretKey,
                'response' => $token,
                'remoteip' => $ip,
            ]);

            $body = $response->json();

            if (! isset($body['success']) || $body['success'] !== true) {
                Log::warning('reCAPTCHA verification failed.', ['response' => $body]);

                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('reCAPTCHA API request failed: '.$e->getMessage());

            return false;
        }
    }
}
