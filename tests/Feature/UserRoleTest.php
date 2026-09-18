<?php

namespace Tests\Feature;

use App\Mail\AccountCreatedMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class UserRoleTest extends TestCase
{
    public function test_regular_user_can_only_open_news_messages_and_volunteers(): void
    {
        $user = User::factory()->regular()->create();

        try {
            $this->actingAs($user);

            $this->get('/admin/news')->assertOk()->assertDontSee('>Users</span>', false);
            $this->get('/admin/messages')->assertOk();
            $this->get('/admin/volunteers')->assertOk();

            $this->get('/admin/dashboard')->assertRedirect(route('admin.news.index'));
            $this->get('/admin/settings')->assertRedirect(route('admin.news.index'));
            $this->get('/admin/leaders')->assertRedirect(route('admin.news.index'));
            $this->get('/admin/users')->assertRedirect(route('admin.news.index'));
        } finally {
            $user->delete();
        }
    }

    public function test_admin_can_create_a_regular_user(): void
    {
        $admin = User::factory()->create();

        try {
            Mail::fake();
            $this->actingAs($admin);
            $this->get('/admin/users')->assertOk()->assertSee('Create user');
            $this->get('/admin/users/create')->assertOk();

            $this->post('/admin/users', [
                '_token' => csrf_token(),
                'name' => 'Staff Member',
                'email' => 'staff-verify@example.com',
                'password' => 'StaffPass12!',
                'password_confirmation' => 'StaffPass12!',
                'role' => 'user',
            ])->assertRedirect(route('admin.users.index'));

            $this->assertDatabaseHas('users', [
                'email' => 'staff-verify@example.com',
                'role' => 'user',
                'name' => 'Staff Member',
            ]);

            Mail::assertSent(AccountCreatedMail::class, function (AccountCreatedMail $mail) {
                return $mail->hasTo('staff-verify@example.com')
                    && $mail->user->name === 'Staff Member'
                    && $mail->plainPassword === 'StaffPass12!';
            });
        } finally {
            User::query()->where('email', 'staff-verify@example.com')->delete();
            $admin->delete();
        }
    }
}
