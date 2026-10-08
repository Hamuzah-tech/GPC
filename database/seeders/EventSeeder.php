<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        Event::query()->updateOrCreate(
            ['slug' => Event::BECOMING_HER_SLUG],
            [
                'name' => 'Becoming Her: A Journey of Growth',
                'headline' => 'BECOMING HER',
                'tagline' => 'A JOURNEY OF GROWTH',
                'session_label' => 'MENTORSHIP SESSION',
                'code' => 'BH',
                'year' => 2026,
                'description' => 'Mentorship session for first-year women.',
                'is_active' => true,
            ]
        );
    }
}
