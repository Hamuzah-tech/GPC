<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct(private AuthCodeService $codes)
    {
    }

    public function show(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->homeRoute());
        }

        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::validate($credentials)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Invalid email or password']);
        }

        $user = User::query()->where('email', $credentials['email'])->firstOrFail();
        $this->codes->sendLoginCode($user);

        $request->session()->put('auth.pending', [
            'user_id' => $user->id,
            'email' => $user->email,
            'remember' => $request->boolean('remember'),
        ]);

        return redirect()
            ->route('login.verify.show')
            ->with('status', 'We sent a verification code to '.$this->codes->maskEmail($user->email).'.');
    }

    public function showVerify(Request $request): View|RedirectResponse
    {
        $pending = $request->session()->get('auth.pending');
        if (! is_array($pending) || empty($pending['user_id'])) {
            return redirect()->route('login');
        }

        return view('auth.verify', [
            'maskedEmail' => $this->codes->maskEmail((string) $pending['email']),
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $pending = $request->session()->get('auth.pending');
        if (! is_array($pending) || empty($pending['user_id'])) {
            return redirect()->route('login');
        }

        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $user = User::query()->find($pending['user_id']);
        if (! $user || ! $this->codes->verify(AuthCodeService::LOGIN, $user, $request->string('code')->toString())) {
            return back()->withErrors(['code' => 'Invalid or expired verification code.']);
        }

        $request->session()->forget('auth.pending');
        Auth::login($user, (bool) ($pending['remember'] ?? false));
        $request->session()->regenerate();

        return redirect()->intended(route($user->homeRoute()));
    }

    public function resend(Request $request): RedirectResponse
    {
        $pending = $request->session()->get('auth.pending');
        if (! is_array($pending) || empty($pending['user_id'])) {
            return redirect()->route('login');
        }

        $user = User::query()->findOrFail($pending['user_id']);
        $this->codes->sendLoginCode($user);

        return back()->with('status', 'We sent a new verification code to '.$this->codes->maskEmail($user->email).'.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
