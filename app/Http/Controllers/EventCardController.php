<?php

namespace App\Http\Controllers;

use App\Models\EventRegistration;
use App\Services\EventCardService;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EventCardController extends Controller
{
    public function verify(string $code): View|Response
    {
        $registration = EventRegistration::query()
            ->with('event')
            ->where('registration_id', $code)
            ->first();

        if (! $registration) {
            return response()->view('events.unavailable', [
                'heading' => 'Registration Not Found',
                'message' => 'This event card could not be verified. Please contact the event organisers.',
            ], 404);
        }

        if (! $registration->isAccepted()) {
            return response()->view('events.unavailable', [
                'heading' => 'Registration Not Valid',
                'message' => 'This registration is no longer valid. Please contact the event organisers.',
            ]);
        }

        return view('events.verify', [
            'registration' => $registration,
        ]);
    }

    public function show(string $token, EventCardService $cards): View|Response
    {
        $registration = $this->registrationForToken($token);

        if (! $registration) {
            return response()->view('events.unavailable', [
                'heading' => 'Registration Not Found',
                'message' => 'This event card could not be verified. Please contact the event organisers.',
            ], 404);
        }

        if (! $registration->isAccepted()) {
            return response()->view('events.unavailable', [
                'heading' => 'Registration Not Valid',
                'message' => 'This registration is no longer valid. Please contact the event organisers.',
            ]);
        }

        return view('events.card', $cards->viewData($registration));
    }

    public function download(string $token, EventCardService $cards): StreamedResponse|Response
    {
        $registration = $this->registrationForToken($token);

        if (! $registration || ! $registration->isAccepted()) {
            abort(404);
        }

        return $this->pdfResponse($registration, $cards);
    }

    private function registrationForToken(string $token): ?EventRegistration
    {
        return EventRegistration::query()
            ->with('event')
            ->where('verification_token', $token)
            ->first();
    }

    private function pdfResponse(EventRegistration $registration, EventCardService $cards): StreamedResponse
    {
        $pdf = $cards->pdfBinary($registration);
        $name = $cards->downloadName($registration);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, $name, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
