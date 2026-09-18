<?php

namespace Tests\Feature;

use App\Mail\LoginVerificationCodeMail;
use App\Mail\VolunteerApplicationReceived;
use App\Models\ContactMessage;
use App\Models\News;
use App\Models\User;
use App\Models\VolunteerApplication;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WebsiteConversionTest extends TestCase
{
    public function test_public_pages_render(): void
    {
        foreach ([
            '/',
            '/leadership',
            '/volunteer',
            '/mission',
            '/vision',
            '/pillars',
            '/thematic-areas',
            '/strategic-approach',
            '/programs',
            '/contact',
            '/news',
            '/login',
            '/forgot-password',
        ] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_guests_cannot_access_admin(): void
    {
        $this->get('/admin/dashboard')->assertRedirect(route('login'));
        $this->get('/admin/news')->assertRedirect(route('login'));
    }

    public function test_admin_can_login_manage_news_and_logout(): void
    {
        $password = 'TempVerifyPass1!';
        $user = User::factory()->create([
            'name' => 'Verify Admin',
            'password' => $password,
        ]);

        try {
            Mail::fake();
            $this->get('/login')->assertOk();
            $this->post('/login', [
                '_token' => csrf_token(),
                'email' => $user->email,
                'password' => $password,
            ])->assertRedirect(route('login.verify.show'));
            $this->assertGuest();

            $code = null;
            Mail::assertSent(LoginVerificationCodeMail::class, function (LoginVerificationCodeMail $mail) use ($user, &$code) {
                $code = $mail->code;

                return $mail->hasTo($user->email);
            });
            $this->assertNotEmpty($code);

            $this->get('/login/verify')->assertOk();
            $this->post('/login/verify', [
                '_token' => csrf_token(),
                'code' => $code,
            ])->assertRedirect(route('admin.dashboard'));
            $this->assertAuthenticatedAs($user);

            $this->get('/admin/dashboard')->assertOk()->assertSee('Dashboard')->assertSee('Overview')->assertSee('Leadership')->assertDontSee('Content studio');
            $this->get('/admin/news')->assertOk();
            $this->get('/admin/leaders')->assertOk()->assertSee('Overview');
            $this->get('/admin/programs')->assertOk();
            $this->get('/admin/pillars')->assertOk();
            $this->get('/admin/settings')->assertOk();

            $title = 'Verify News '.uniqid();
            $this->post('/admin/news', [
                '_token' => csrf_token(),
                'title' => $title,
                'content' => 'Temporary verification article.',
                'published_at' => now()->toDateString(),
            ])->assertRedirect(route('admin.news.index'));

            $news = News::query()->where('title', $title)->first();
            $this->assertNotNull($news);

            $this->put('/admin/news/'.$news->id, [
                '_token' => csrf_token(),
                'title' => $title.' Updated',
                'content' => 'Updated verification article.',
                'published_at' => now()->toDateString(),
            ])->assertRedirect(route('admin.news.index'));

            $this->delete('/admin/news/'.$news->id, [
                '_token' => csrf_token(),
            ])->assertRedirect(route('admin.news.index'));
            $this->assertDatabaseMissing('news', ['id' => $news->id]);

            $this->post('/logout', ['_token' => csrf_token()])->assertRedirect(route('login'));
            $this->get('/admin/dashboard')->assertRedirect(route('login'));
        } finally {
            News::query()->where('title', 'like', 'Verify News %')->delete();
            $user->delete();
        }
    }

    public function test_contact_and_volunteer_forms_save_records(): void
    {
        $this->get('/contact')->assertOk();
        $this->postJson('/contact', [
            'name' => 'Verify Contact',
            'email' => 'verify-contact@example.com',
            'phone' => '+265000000',
            'message' => 'Temporary verification message.',
        ], ['X-CSRF-TOKEN' => csrf_token()])->assertOk()->assertJson(['ok' => true]);

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'verify-contact@example.com',
            'name' => 'Verify Contact',
        ]);

        $this->get('/volunteer')->assertOk();
        Storage::fake('local');
        Mail::fake();
        $cv = UploadedFile::fake()->create('cv.pdf', 120, 'application/pdf');
        $this->post('/volunteer', [
            '_token' => csrf_token(),
            'full_name' => 'Verify Volunteer',
            'email' => 'verify-volunteer@example.com',
            'phone' => '+265111111',
            'age' => 24,
            'gender' => 'Female',
            'location' => 'Lilongwe',
            'skills' => 'Teaching',
            'interests' => ['Education'],
            'availability' => 'Weekends',
            'message' => 'Temporary verification application.',
            'cv' => $cv,
        ], ['Accept' => 'application/json', 'X-CSRF-TOKEN' => csrf_token()])->assertOk()->assertJson(['ok' => true]);

        $this->assertDatabaseHas('volunteer_applications', [
            'email' => 'verify-volunteer@example.com',
            'full_name' => 'Verify Volunteer',
        ]);

        Mail::assertSent(VolunteerApplicationReceived::class, function (VolunteerApplicationReceived $mail) {
            return $mail->hasTo('verify-volunteer@example.com');
        });

        ContactMessage::query()->where('email', 'verify-contact@example.com')->delete();
        VolunteerApplication::query()->where('email', 'verify-volunteer@example.com')->delete();
    }
}
