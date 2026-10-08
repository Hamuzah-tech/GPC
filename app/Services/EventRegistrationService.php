<?php

namespace App\Services;

use App\Exceptions\DuplicateEventRegistrationException;
use App\Mail\EventRegistrationAccepted;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Support\RegistrationNormalizer;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use RuntimeException;

class EventRegistrationService
{
    public function __construct(private EventCardService $cards) {}

    /**
     * @param  array{full_name: string, registration_number: string, phone: string, email: string, year_of_study: string}  $input
     */
    public function register(Event $event, array $input): EventRegistration
    {
        $fullName = trim($input['full_name']);
        $registrationNumber = trim($input['registration_number']);
        $phone = trim($input['phone']);
        $email = RegistrationNormalizer::email($input['email']);
        $registrationNumberNormalized = RegistrationNormalizer::registrationNumber($registrationNumber);
        $phoneNormalized = RegistrationNormalizer::phone($phone);

        $duplicate = EventRegistration::query()
            ->where('event_id', $event->id)
            ->where(function ($query) use ($registrationNumberNormalized, $email, $phoneNormalized) {
                $query->where('registration_number_normalized', $registrationNumberNormalized)
                    ->orWhere('email', $email)
                    ->orWhere('phone_normalized', $phoneNormalized);
            })
            ->exists();

        if ($duplicate) {
            throw new DuplicateEventRegistrationException;
        }

        try {
            return $event->registrations()->create([
                'full_name' => $fullName,
                'registration_number' => $registrationNumber,
                'registration_number_normalized' => $registrationNumberNormalized,
                'phone' => $phone,
                'phone_normalized' => $phoneNormalized,
                'email' => $email,
                'year_of_study' => EventRegistration::YEAR_OF_STUDY,
                'status' => EventRegistration::STATUS_PENDING,
                'email_send_count' => 0,
            ]);
        } catch (UniqueConstraintViolationException) {
            throw new DuplicateEventRegistrationException;
        } catch (QueryException $exception) {
            if (in_array($exception->errorInfo[0] ?? null, ['23000', '23505'], true)) {
                throw new DuplicateEventRegistrationException;
            }

            throw $exception;
        }
    }

    public function accept(EventRegistration $registration): RegistrationDecision
    {
        try {
            return DB::transaction(function () use ($registration) {
                $event = Event::query()->whereKey($registration->event_id)->lockForUpdate()->firstOrFail();
                $locked = EventRegistration::query()->whereKey($registration->id)->lockForUpdate()->firstOrFail();

                if ($locked->isAccepted()) {
                    return new RegistrationDecision($locked->fresh('event'), false);
                }

                if (! $locked->isPending()) {
                    throw new RuntimeException('Only pending registrations can be accepted.');
                }

                $prefix = $event->code.'-'.$event->year.'-';
                $sequence = 0;

                $existingIds = EventRegistration::query()
                    ->where('event_id', $event->id)
                    ->whereNotNull('registration_id')
                    ->lockForUpdate()
                    ->pluck('registration_id');

                foreach ($existingIds as $existingId) {
                    if (is_string($existingId) && str_starts_with($existingId, $prefix)) {
                        $sequence = max($sequence, (int) substr($existingId, strlen($prefix)));
                    }
                }

                $locked->forceFill([
                    'registration_id' => $prefix.str_pad((string) ($sequence + 1), 4, '0', STR_PAD_LEFT),
                    'verification_token' => Str::random(64),
                    'status' => EventRegistration::STATUS_ACCEPTED,
                    'accepted_at' => now(),
                ])->save();

                return new RegistrationDecision($locked->fresh('event'), true);
            });
        } catch (UniqueConstraintViolationException $exception) {
            report($exception);

            throw new RuntimeException('Could not generate a unique registration ID. Please try again.');
        }
    }

    public function reject(EventRegistration $registration): RegistrationDecision
    {
        return DB::transaction(function () use ($registration) {
            $locked = EventRegistration::query()->whereKey($registration->id)->lockForUpdate()->firstOrFail();

            if ($locked->isRejected()) {
                return new RegistrationDecision($locked, false);
            }

            if (! $locked->isPending() && ! $locked->isAccepted()) {
                throw new RuntimeException('This registration cannot be rejected.');
            }

            $locked->forceFill([
                'status' => EventRegistration::STATUS_REJECTED,
            ])->save();

            return new RegistrationDecision($locked->fresh('event'), true);
        });
    }

    public function sendAcceptanceEmail(EventRegistration $registration): void
    {
        $registration->loadMissing('event');

        if (! $registration->isAccepted() || ! $registration->registration_id || ! $registration->verification_token) {
            throw new RuntimeException('Only accepted registrations can receive an acceptance email.');
        }

        $pdf = $this->cards->pdfBinary($registration);

        Mail::to($registration->email)->send(new EventRegistrationAccepted($registration, $pdf));

        $registration->forceFill([
            'email_sent_at' => now(),
            'email_send_count' => $registration->email_send_count + 1,
        ])->save();
    }
}
