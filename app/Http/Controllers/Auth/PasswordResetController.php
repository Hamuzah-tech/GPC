<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    public function __construct(private AuthCodeService $codes)
    {
    }

    public function requestForm(): View
    {
        return view('auth.forgot-password');
    }

    public function sendCode(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::query()->where('email', $data['email'])->first();
        if ($user) {
            $this->codes->sendPasswordResetCode($user);
        }

        $request->session()->put('auth.password_reset_email', $data['email']);

        return redirect()
            ->route('password.reset.show')
            ->with('status', 'If that email is registered, we sent a reset code.');
    }

    public function resetForm(Request $request): View|RedirectResponse
    {
        $email = $request->session()->get('auth.password_reset_email', old('email'));
        if (! is_string($email) || $email === '') {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password', [
            'email' => $email,
            'maskedEmail' => $this->codes->maskEmail($email),
        ]);
    }

    public function reset(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'digits:6'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::query()->where('email', $data['email'])->first();
        if (! $user || ! $this->codes->verify(AuthCodeService::PASSWORD_RESET, $user, $data['code'])) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['code' => 'Invalid or expired reset code.']);
        }

        $user->update([
            'password' => $data['password'],
        ]);

        $request->session()->forget('auth.password_reset_email');

        return redirect()
            ->route('login')
            ->with('status', 'Password updated. Sign in with your new password.');
    }
}
