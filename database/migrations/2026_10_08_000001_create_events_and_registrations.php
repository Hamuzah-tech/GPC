<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('headline');
            $table->string('tagline');
            $table->string('session_label');
            $table->string('code', 12);
            $table->unsignedSmallInteger('year');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->string('full_name');
            $table->string('registration_number');
            $table->string('registration_number_normalized');
            $table->string('phone');
            $table->string('phone_normalized');
            $table->string('email');
            $table->string('year_of_study');
            $table->string('status')->default('pending')->index();
            $table->string('registration_id')->nullable()->unique();
            $table->string('verification_token', 80)->nullable()->unique();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('email_sent_at')->nullable();
            $table->unsignedInteger('email_send_count')->default(0);
            $table->timestamps();

            $table->unique(['event_id', 'registration_number_normalized'], 'event_reg_number_unique');
            $table->unique(['event_id', 'email'], 'event_reg_email_unique');
            $table->unique(['event_id', 'phone_normalized'], 'event_reg_phone_unique');
        });

        $now = now();

        DB::table('events')->insert([
            'name' => 'Becoming Her: A Journey of Growth',
            'slug' => 'becoming-her',
            'headline' => 'BECOMING HER',
            'tagline' => 'A JOURNEY OF GROWTH',
            'session_label' => 'MENTORSHIP SESSION',
            'code' => 'BH',
            'year' => 2026,
            'description' => 'Mentorship session for first-year women.',
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
        Schema::dropIfExists('events');
    }
};
