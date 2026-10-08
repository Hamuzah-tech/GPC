<?php

namespace Tests\Feature;

use App\Exceptions\DuplicateEventRegistrationException;
use App\Mail\EventRegistrationAccepted;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use App\Services\EventCardService;
use App\Services\EventRegistrationService;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Tests\TestCase;

class EventRegistrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ThrottleRequests::class);
    }

    protected function tearDown(): void
    {
        EventRegistration::query()->where('email', 'like', '%@event-test.example')->delete();
        Event::query()->where('slug', 'like', 'test-event-%')->delete();
        User::query()->where('email', 'like', '%@event-test.example')->delete();

        parent::tearDown();
    }

    public function test_public_registration_page_is_available_without_an_account(): void
    {
        $this->get(route('events.register'))
            ->assertOk()
            ->assertSee('Register for the Event')
            ->assertSee('Becoming Her: A Journey of Growth')
            ->assertSee('Year 1')
            ->assertSee('Register for the Event', false);
    }

    public function test_valid_registration_is_stored_as_pending_without_an_email_or_card(): void
    {
        Mail::fake();
        $payload = $this->payload();

        $this->submit($payload)
            ->assertRedirect(route('events.register.success'));

        $registration = $this->findRegistration($payload['email']);
        $this->assertNotNull($registration);
        $this->assertTrue($registration->isPending());
        $this->assertNull($registration->registration_id);
        $this->assertNull($registration->verification_token);
        $this->assertNull($registration->accepted_at);
        $this->assertSame(0, $registration->email_send_count);
        $this->assertSame(Event::becomingHer()->id, $registration->event_id);
        Mail::assertNothingSent();

        $this->get(route('events.register.success'))
            ->assertOk()
            ->assertSee('Registration Successful!')
            ->assertSee('awaiting confirmation')
            ->assertDontSee('has been accepted');

        $this->assertSame(1, EventRegistration::query()->where('email', $registration->email)->count());
        $this->get(route('events.register.success'))->assertOk();
        $this->assertSame(1, EventRegistration::query()->where('email', $registration->email)->count());
    }

    public function test_missing_and_invalid_fields_are_rejected_on_the_server(): void
    {
        $base = $this->payload();

        $cases = [
            'full_name' => ['full_name' => '', 'message' => 'Please enter your full name.'],
            'registration_number' => ['registration_number' => '', 'message' => 'Please enter your registration number.'],
            'phone' => ['phone' => '', 'message' => 'Please enter your phone number.'],
            'email' => ['email' => '', 'message' => 'Please enter your email address.'],
            'invalid_email' => ['email' => 'not-an-email', 'message' => 'Please enter a valid email address.'],
            'year' => ['year_of_study' => 'Year 2', 'message' => 'This event is only open to Year 1 students.'],
        ];

        foreach ($cases as $field => $case) {
            $input = $base;
            $errorKey = match ($field) {
                'invalid_email' => 'email',
                'year' => 'year_of_study',
                default => $field,
            };
            $input[$errorKey] = $case[$errorKey];

            $response = $this->submit($input);
            $response->assertSessionHasErrors($errorKey);
            $this->followRedirects($response)->assertSee($case['message']);
        }

        $this->assertSame(0, EventRegistration::query()->where('email', strtolower($base['email']))->count());
    }

    public function test_duplicate_registration_number_email_and_phone_are_rejected_for_the_same_event(): void
    {
        $first = $this->payload();
        $this->submit($first)->assertRedirect(route('events.register.success'));

        $sameNumber = $this->payload();
        $sameNumber['registration_number'] = ' '.strtolower($first['registration_number']).' ';
        $numberResponse = $this->submit($sameNumber);
        $numberResponse->assertSessionHas('already_registered');
        $this->followRedirects($numberResponse)
            ->assertSee('Already Registered')
            ->assertSee('you have already registered for this event')
            ->assertDontSee('matches your registration number');

        $sameEmail = $this->payload();
        $sameEmail['email'] = strtoupper($first['email']);
        $this->submit($sameEmail)->assertSessionHas('already_registered');

        $samePhone = $this->payload();
        $samePhone['phone'] = implode(' ', str_split($first['phone'], 4));
        $this->submit($samePhone)->assertSessionHas('already_registered');

        $sameInternationalPhone = $this->payload();
        $sameInternationalPhone['phone'] = '+265 '.substr($first['phone'], 1);
        $this->submit($sameInternationalPhone)->assertSessionHas('already_registered');

        $this->assertSame(1, EventRegistration::query()->where('event_id', Event::becomingHer()->id)->where(function ($query) use ($first) {
            $query->where('email', strtolower($first['email']))
                ->orWhere('registration_number_normalized', strtoupper($first['registration_number']))
                ->orWhere('phone_normalized', preg_replace('/\D+/', '', $first['phone']));
        })->count());
    }

    public function test_the_same_student_can_register_for_another_event(): void
    {
        $payload = $this->payload();
        $this->submit($payload)->assertRedirect(route('events.register.success'));

        $other = Event::query()->create([
            'name' => 'Career Mentorship Session',
            'slug' => 'test-event-'.uniqid(),
            'headline' => 'CAREER',
            'tagline' => 'MENTORSHIP',
            'session_label' => 'MENTORSHIP SESSION',
            'code' => 'CM',
            'year' => 2026,
            'is_active' => true,
        ]);

        $second = app(EventRegistrationService::class)->register($other, $payload);

        $this->assertTrue($second->isPending());
        $this->assertSame($other->id, $second->event_id);
        $this->assertSame(2, EventRegistration::query()->where('email', strtolower($payload['email']))->count());
    }

    public function test_database_unique_indexes_block_a_duplicate_insert(): void
    {
        $payload = $this->payload();
        $created = app(EventRegistrationService::class)->register(Event::becomingHer(), $payload);

        $this->expectException(UniqueConstraintViolationException::class);

        EventRegistration::query()->create([
            'event_id' => $created->event_id,
            'full_name' => 'Someone Else',
            'registration_number' => 'OTHER-1',
            'registration_number_normalized' => $created->registration_number_normalized,
            'phone' => '0888000111',
            'phone_normalized' => '0888000111',
            'email' => 'other-'.uniqid().'@event-test.example',
            'year_of_study' => 'Year 1',
            'status' => EventRegistration::STATUS_PENDING,
        ]);
    }

    public function test_service_rejects_a_second_submit_for_the_same_registration_number(): void
    {
        $payload = $this->payload();
        $service = app(EventRegistrationService::class);
        $service->register(Event::becomingHer(), $payload);

        $this->expectException(DuplicateEventRegistrationException::class);
        $service->register(Event::becomingHer(), $payload);
    }

    public function test_admin_can_search_filter_accept_and_reject_registrations(): void
    {
        $this->get(route('admin.event-registrations.index'))->assertRedirect(route('login'));

        $pending = $this->store($this->payload(['full_name' => 'Pending Person '.uniqid()]));
        $toReject = $this->store($this->payload(['full_name' => 'Reject Person '.uniqid()]));
        $regular = User::factory()->regular()->create([
            'email' => 'regular-'.uniqid().'@event-test.example',
        ]);

        $this->actingAs($regular);
        $this->get(route('admin.event-registrations.index'))->assertRedirect(route('admin.news.index'));

        $this->admin();
        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Total registrations')
            ->assertSee('Pending')
            ->assertSee('Accepted')
            ->assertSee('Rejected')
            ->assertSee("Today's registrations", false);

        $this->get(route('admin.event-registrations.index', ['q' => $pending->registration_number]))
            ->assertOk()
            ->assertSee($pending->full_name)
            ->assertSee('Pending');

        $this->get(route('admin.event-registrations.index', ['q' => $pending->email, 'status' => 'pending', 'event' => $pending->event_id]))
            ->assertOk()
            ->assertSee($pending->full_name);

        $this->get(route('admin.event-registrations.index', ['q' => $pending->phone]))
            ->assertOk()
            ->assertSee($pending->full_name);

        $this->get(route('admin.event-registrations.show', $pending))
            ->assertOk()
            ->assertSee($pending->full_name)
            ->assertSee($pending->email)
            ->assertDontSee('verification_token');

        Mail::fake();
        $this->post(route('admin.event-registrations.accept', $pending), ['_token' => csrf_token()])
            ->assertRedirect()
            ->assertSessionHas('success');

        $pending->refresh();
        $registrationId = $pending->registration_id;
        $token = $pending->verification_token;

        $this->assertTrue($pending->isAccepted());
        $this->assertMatchesRegularExpression('/^BH-2026-\d{4}$/', (string) $registrationId);
        $this->assertNotNull($pending->accepted_at);
        $this->assertNotNull($pending->email_sent_at);
        $this->assertSame(1, $pending->email_send_count);
        $this->assertNotSame($pending->id, $registrationId);

        Mail::assertSent(EventRegistrationAccepted::class, function (EventRegistrationAccepted $mail) use ($pending) {
            return $mail->hasTo($pending->email)
                && $mail->envelope()->subject === 'You Have Been Accepted — Becoming Her: A Journey of Growth'
                && str_contains($mail->render(), 'View My Event Card')
                && str_contains($mail->render(), $pending->registration_id)
                && count($mail->attachments()) === 1;
        });

        $this->post(route('admin.event-registrations.accept', $pending), ['_token' => csrf_token()])
            ->assertSessionHas('success', 'This participant is already accepted.');

        $pending->refresh();
        $this->assertSame($registrationId, $pending->registration_id);
        $this->assertSame($token, $pending->verification_token);
        $this->assertSame(1, $pending->email_send_count);
        Mail::assertSentCount(1);

        $this->post(route('admin.event-registrations.resend', $pending), ['_token' => csrf_token()])
            ->assertSessionHas('success', 'Acceptance email sent successfully.');

        $pending->refresh();
        $this->assertSame($registrationId, $pending->registration_id);
        $this->assertSame($token, $pending->verification_token);
        $this->assertSame(2, $pending->email_send_count);
        Mail::assertSentCount(2);

        $this->post(route('admin.event-registrations.reject', $toReject), ['_token' => csrf_token()])
            ->assertSessionHas('success');
        $toReject->refresh();
        $this->assertTrue($toReject->isRejected());
        $this->assertNull($toReject->registration_id);
        $this->assertNull($toReject->verification_token);
    }

    public function test_acceptance_email_failure_keeps_the_participant_accepted(): void
    {
        $registration = $this->store($this->payload());
        $this->actingAs($this->admin());
        $this->get(route('admin.event-registrations.index'));

        Mail::shouldReceive('to')->once()->andThrow(new RuntimeException('smtp down'));

        $this->post(route('admin.event-registrations.accept', $registration), ['_token' => csrf_token()])
            ->assertSessionHas('warning');

        $registration->refresh();
        $this->assertTrue($registration->isAccepted());
        $this->assertNotNull($registration->registration_id);
        $this->assertSame(0, $registration->email_send_count);
        $this->assertNull($registration->email_sent_at);
    }

    public function test_card_qr_pdf_and_verification_use_the_saved_registration(): void
    {
        $registration = $this->store($this->payload(['full_name' => 'Mary Banda']));
        $this->actingAs($this->admin());
        $this->get(route('admin.event-registrations.index'));
        Mail::fake();
        $this->post(route('admin.event-registrations.accept', $registration), ['_token' => csrf_token()]);
        $registration->refresh();

        $cards = app(EventCardService::class);
        $url = $cards->verificationUrl($registration);
        $this->assertSame(route('events.verify', ['code' => $registration->registration_id]), $url);
        $this->assertStringNotContainsString($registration->email, $url);
        $this->assertStringNotContainsString($registration->phone, $url);
        $this->assertStringStartsWith('data:image/png;base64,', $cards->qrDataUri($registration));
        $this->assertStringStartsWith('%PDF', $cards->pdfBinary($registration));

        $this->get(route('events.verify', ['code' => $registration->registration_id]))
            ->assertOk()
            ->assertSee('Registration Verified')
            ->assertSee('Becoming Her: A Journey of Growth')
            ->assertSee('Mary Banda')
            ->assertSee($registration->registration_id)
            ->assertSee('Accepted')
            ->assertDontSee($registration->email)
            ->assertDontSee($registration->phone);

        $this->get(route('events.verify', ['code' => 'MISSING-CARD']))
            ->assertNotFound()
            ->assertSee('Registration Not Found');

        $this->get(route('events.card', ['token' => $registration->verification_token]))
            ->assertOk()
            ->assertSee('Mary Banda')
            ->assertSee('Year 1')
            ->assertSee($registration->registration_id)
            ->assertSee('SCAN TO VERIFY')
            ->assertSee('Download Card')
            ->assertDontSee($registration->email)
            ->assertDontSee($registration->phone);

        $this->get(route('events.card.download', ['token' => $registration->verification_token]))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        $this->get(route('admin.event-registrations.card', $registration))
            ->assertOk()
            ->assertSee('Mary Banda')
            ->assertSee($registration->registration_id);

        $download = $this->get(route('admin.event-registrations.card.download', $registration));
        $download->assertOk();
        $this->assertStringStartsWith('%PDF', $download->streamedContent());

        $csv = $this->get(route('admin.event-registrations.export', ['q' => $registration->email]));
        $csv->assertOk();
        $contents = $csv->streamedContent();
        $this->assertStringContainsString('Mary Banda', $contents);
        $this->assertStringContainsString($registration->registration_id, $contents);
        $this->assertStringContainsString($registration->email, $contents);
        $this->assertStringNotContainsString($registration->verification_token, $contents);

        $this->post(route('admin.event-registrations.reject', $registration), ['_token' => csrf_token()]);
        $registration->refresh();
        $this->assertTrue($registration->isRejected());
        $this->assertNotNull($registration->registration_id);

        $this->get(route('events.verify', ['code' => $registration->registration_id]))
            ->assertOk()
            ->assertSee('Registration Not Valid')
            ->assertDontSee($registration->email)
            ->assertDontSee($registration->phone);

        $this->get(route('events.card', ['token' => $registration->verification_token]))
            ->assertOk()
            ->assertSee('Registration Not Valid');
    }

    public function test_registration_routes_are_throttled(): void
    {
        $route = app('router')->getRoutes()->getByName('events.register.store');

        $this->assertContains('throttle:event-register', $route->gatherMiddleware());
    }

    /**
     * @param  array<string, string>  $overrides
     * @return array<string, string>
     */
    private function payload(array $overrides = []): array
    {
        $number = random_int(1000000, 9999999);

        return array_merge([
            'full_name' => 'Mary Banda '.$number,
            'registration_number' => 'UNIMA-2026-'.$number,
            'phone' => '099'.$number,
            'email' => 'student'.$number.'@event-test.example',
            'year_of_study' => 'Year 1',
        ], $overrides);
    }

    /**
     * @param  array<string, string>  $input
     */
    private function submit(array $input)
    {
        $this->get(route('events.register'));

        $input['_token'] = csrf_token();

        return $this->post(route('events.register.store'), $input);
    }

    /**
     * @param  array<string, string>  $input
     */
    private function store(array $input): EventRegistration
    {
        return app(EventRegistrationService::class)->register(Event::becomingHer(), $input);
    }

    private function findRegistration(string $email): ?EventRegistration
    {
        return EventRegistration::query()->where('email', strtolower($email))->first();
    }

    private function admin(): User
    {
        $admin = User::factory()->create([
            'email' => 'admin-'.uniqid().'@event-test.example',
        ]);
        $this->actingAs($admin);
        $this->get(route('admin.event-registrations.index'));

        return $admin;
    }
}
