<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = (string) config('admin.email');
        $password = config('admin.password');
        $name = (string) config('admin.name');

        if ($email === '' || ! is_string($password) || $password === '') {
            throw new \RuntimeException(
                'Set ADMIN_EMAIL and ADMIN_PASSWORD in your .env file, then run `php artisan config:cache` before seeding.'
            );
        }

        $user = User::query()->where('email', $email)->first()
            ?? User::query()->where('email', 'admin@girlpowerforchange.org')->first();

        $attributes = [
            'name' => $name !== '' ? $name : 'GPC Administrator',
            'email' => $email,
            'password' => $password,
            'role' => User::ROLE_ADMIN,
        ];

        if ($user) {
            $user->fill($attributes)->save();
        } else {
            User::query()->create($attributes);
        }
    }
}
