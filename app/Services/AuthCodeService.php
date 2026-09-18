<?php

namespace App\Services;

use App\Mail\LoginVerificationCodeMail;
use App\Mail\PasswordResetCodeMail;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthCodeService
{
    public const LOGIN = 'login';

    public const PASSWORD_RESET = 'password_reset';

    public const TTL_MINUTES = 10;

    public const MAX_ATTEMPTS = 5;

    public function sendLoginCode(User $user): void
    {
        $this->send($user, self::LOGIN, fn (string $code) => new LoginVerificationCodeMail($user, $code));
    }

    public function sendPasswordResetCode(User $user): void
    {
        $this->send($user, self::PASSWORD_RESET, fn (string $code) => new PasswordResetCodeMail($user, $code));
    }

    public function verify(string $purpose, User $user, string $code): bool
    {
        $key = $this->key($purpose, $user);
        $payload = Cache::get($key);

        if (! is_array($payload) || empty($payload['hash'])) {
            return false;
        }

        $attempts = (int) ($payload['attempts'] ?? 0);
        if ($attempts >= self::MAX_ATTEMPTS) {
            Cache::forget($key);

            return false;
        }

        if (! Hash::check($code, $payload['hash'])) {
            $payload['attempts'] = $attempts + 1;
            Cache::put($key, $payload, now()->addMinutes(self::TTL_MINUTES));

            return false;
        }

        Cache::forget($key);

        return true;
    }

    public function maskEmail(string $email): string
    {
        [$local, $domain] = array_pad(explode('@', $email, 2), 2, '');

        if ($domain === '') {
            return $email;
        }

        $visible = substr($local, 0, 1);

        return $visible.str_repeat('*', max(strlen($local) - 1, 1)).'@'.$domain;
    }

    /**
     * @param  callable(string): \Illuminate\Mail\Mailable  $mailable
     */
    private function send(User $user, string $purpose, callable $mailable): void
    {
        $limiterKey = $this->sendLimiterKey($purpose, $user);

        if (RateLimiter::tooManyAttempts($limiterKey, 1)) {
            throw ValidationException::withMessages([
                'code' => 'Please wait a minute before requesting another code.',
            ]);
        }

        $code = $this->issue($purpose, $user);

        try {
            Mail::to($user->email)->send($mailable($code));
        } catch (\Throwable $e) {
            report($e);

            throw ValidationException::withMessages([
                'email' => 'We could not send a verification code. Please try again.',
            ]);
        }

        RateLimiter::hit($limiterKey, 60);
    }

    private function issue(string $purpose, User $user): string
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        Cache::put($this->key($purpose, $user), [
            'hash' => Hash::make($code),
            'user_id' => $user->id,
            'attempts' => 0,
        ], now()->addMinutes(self::TTL_MINUTES));

        return $code;
    }

    private function key(string $purpose, User $user): string
    {
        return "auth-code:{$purpose}:{$user->id}";
    }

    private function sendLimiterKey(string $purpose, User $user): string
    {
        return "auth-code-send:{$purpose}:{$user->id}";
    }
}
