<?php

namespace Tests\Feature;

use App\Mail\LoginVerificationCodeMail;
use App\Mail\PasswordResetCodeMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function test_password_alone_does_not_sign_the_user_in(): void
    {
        Mail::fake();
        $password = 'TempVerifyPass1!';
        $user = User::factory()->create(['password' => $password]);

        try {
            $this->get('/login')->assertOk();
            $this->post('/login', [
                '_token' => csrf_token(),
                'email' => $user->email,
                'password' => $password,
            ])->assertRedirect(route('login.verify.show'));

            $this->assertGuest();
            $this->get('/admin/dashboard')->assertRedirect(route('login'));
        } finally {
            $user->delete();
        }
    }

    public function test_invalid_login_code_is_rejected(): void
    {
        Mail::fake();
        $password = 'TempVerifyPass1!';
        $user = User::factory()->create(['password' => $password]);

        try {
            $this->get('/login')->assertOk();
            $this->post('/login', [
                '_token' => csrf_token(),
                'email' => $user->email,
                'password' => $password,
            ])->assertRedirect(route('login.verify.show'));

            $this->post('/login/verify', [
                '_token' => csrf_token(),
                'code' => '000000',
            ])->assertSessionHasErrors('code');

            $this->assertGuest();
        } finally {
            $user->delete();
        }
    }

    public function test_wrong_password_does_not_send_a_code(): void
    {
        Mail::fake();
        $user = User::factory()->create(['password' => 'TempVerifyPass1!']);

        try {
            $this->get('/login')->assertOk();
            $this->post('/login', [
                '_token' => csrf_token(),
                'email' => $user->email,
                'password' => 'wrong-password',
            ])->assertSessionHasErrors('email');

            Mail::assertNothingSent();
            $this->assertGuest();
        } finally {
            $user->delete();
        }
    }

    public function test_user_can_reset_password_with_email_code(): void
    {
        Mail::fake();
        $user = User::factory()->create(['password' => 'TempVerifyPass1!']);
        $newPassword = 'NewVerifyPass1!';

        try {
            $this->get('/forgot-password')->assertOk()->assertSee('Forgot password');
            $this->post('/forgot-password', [
                '_token' => csrf_token(),
                'email' => $user->email,
            ])->assertRedirect(route('password.reset.show'));

            $code = null;
            Mail::assertSent(PasswordResetCodeMail::class, function (PasswordResetCodeMail $mail) use ($user, &$code) {
                $code = $mail->code;

                return $mail->hasTo($user->email);
            });
            $this->assertNotEmpty($code);

            $this->get('/reset-password')->assertOk();
            $this->post('/reset-password', [
                '_token' => csrf_token(),
                'email' => $user->email,
                'code' => $code,
                'password' => $newPassword,
                'password_confirmation' => $newPassword,
            ])->assertRedirect(route('login'));

            $this->post('/login', [
                '_token' => csrf_token(),
                'email' => $user->email,
                'password' => $newPassword,
            ])->assertRedirect(route('login.verify.show'));

            $loginCode = null;
            Mail::assertSent(LoginVerificationCodeMail::class, function (LoginVerificationCodeMail $mail) use ($user, &$loginCode) {
                $loginCode = $mail->code;

                return $mail->hasTo($user->email);
            });

            $this->post('/login/verify', [
                '_token' => csrf_token(),
                'code' => $loginCode,
            ])->assertRedirect(route('admin.dashboard'));
            $this->assertAuthenticatedAs($user);
        } finally {
            $user->delete();
        }
    }

    public function test_unknown_email_does_not_reveal_accounts(): void
    {
        Mail::fake();

        $this->get('/forgot-password')->assertOk();
        $this->post('/forgot-password', [
            '_token' => csrf_token(),
            'email' => 'nobody@example.com',
        ])->assertRedirect(route('password.reset.show'));

        Mail::assertNothingSent();
    }
}
