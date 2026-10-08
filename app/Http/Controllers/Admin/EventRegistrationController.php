<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Services\EventCardService;
use App\Services\EventRegistrationService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EventRegistrationController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.events.index', [
            'registrations' => $this->filtered($request)->paginate(15)->withQueryString(),
            'events' => Event::query()->orderBy('name')->get(),
            'filters' => $request->only(['q', 'event', 'status', 'registered']),
        ]);
    }

    public function show(EventRegistration $eventRegistration): View
    {
        $eventRegistration->load('event');

        return view('admin.events.show', [
            'registration' => $eventRegistration,
        ]);
    }

    public function accept(EventRegistration $eventRegistration, EventRegistrationService $registrations): RedirectResponse
    {
        try {
            $decision = $registrations->accept($eventRegistration);
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        if (! $decision->changed) {
            return back()->with('success', 'This participant is already accepted.');
        }

        return $this->sendEmail(
            $decision->registration,
            $registrations,
            'Participant accepted. An acceptance email and digital event card were sent.',
            'The participant was accepted, but the acceptance email could not be sent. Use Resend Email to try again.'
        );
    }

    public function reject(EventRegistration $eventRegistration, EventRegistrationService $registrations): RedirectResponse
    {
        try {
            $decision = $registrations->reject($eventRegistration);
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        if (! $decision->changed) {
            return back()->with('success', 'This registration is already rejected.');
        }

        return back()->with('success', 'Registration rejected.');
    }

    public function resend(EventRegistration $eventRegistration, EventRegistrationService $registrations): RedirectResponse
    {
        $eventRegistration->load('event');

        if (! $eventRegistration->isAccepted()) {
            return back()->with('error', 'Only accepted registrations can receive an acceptance email.');
        }

        $beforeId = $eventRegistration->registration_id;
        $beforeToken = $eventRegistration->verification_token;

        $response = $this->sendEmail(
            $eventRegistration,
            $registrations,
            'Acceptance email sent successfully.',
            'The acceptance email could not be sent. Please try again.'
        );

        $eventRegistration->refresh();

        if ($eventRegistration->registration_id !== $beforeId || $eventRegistration->verification_token !== $beforeToken) {
            Log::error('Resend changed registration credentials.', [
                'event_registration_id' => $eventRegistration->id,
            ]);
        }

        return $response;
    }

    public function card(EventRegistration $eventRegistration, EventCardService $cards): View
    {
        $this->ensureAccepted($eventRegistration);

        return view('admin.events.card', $cards->viewData($eventRegistration));
    }

    public function downloadCard(EventRegistration $eventRegistration, EventCardService $cards): StreamedResponse
    {
        $this->ensureAccepted($eventRegistration);

        $pdf = $cards->pdfBinary($eventRegistration);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, $cards->downloadName($eventRegistration), [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $query = $this->filtered($request);

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Full Name',
                'Registration Number',
                'Phone',
                'Email',
                'Year of Study',
                'Event',
                'Status',
                'Registration ID',
                'Registered At',
                'Accepted At',
            ]);

            foreach ($query->get() as $registration) {
                fputcsv($handle, [
                    $registration->full_name,
                    $registration->registration_number,
                    $registration->phone,
                    $registration->email,
                    $registration->year_of_study,
                    $registration->event?->name,
                    $registration->statusLabel(),
                    $registration->registration_id,
                    optional($registration->created_at)->format('Y-m-d H:i'),
                    optional($registration->accepted_at)->format('Y-m-d H:i'),
                ]);
            }

            fclose($handle);
        }, 'event-registrations.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function sendEmail(
        EventRegistration $registration,
        EventRegistrationService $registrations,
        string $success,
        string $failure,
    ): RedirectResponse {
        try {
            $registrations->sendAcceptanceEmail($registration);
        } catch (\Throwable $exception) {
            report($exception);
            Log::error('Acceptance email failed.', [
                'event_registration_id' => $registration->id,
                'registration_id' => $registration->registration_id,
                'message' => $exception->getMessage(),
            ]);

            return back()->with('warning', $failure);
        }

        return back()->with('success', $success);
    }

    private function ensureAccepted(EventRegistration $registration): void
    {
        abort_unless($registration->isAccepted() && $registration->registration_id, 404);
    }

    private function filtered(Request $request): Builder
    {
        $query = EventRegistration::query()->with('event')->latest();

        $search = trim((string) $request->query('q', ''));
        if ($search !== '') {
            $like = '%'.$search.'%';
            $query->where(function (Builder $inner) use ($like, $search) {
                $inner->where('full_name', 'like', $like)
                    ->orWhere('registration_number', 'like', $like)
                    ->orWhere('registration_number_normalized', 'like', '%'.strtoupper(preg_replace('/\s+/', '', $search) ?? '').'%')
                    ->orWhere('phone', 'like', $like)
                    ->orWhere('email', 'like', '%'.strtolower($search).'%')
                    ->orWhere('registration_id', 'like', $like);

                $digits = preg_replace('/\D+/', '', $search) ?? '';
                if ($digits !== '') {
                    $inner->orWhere('phone_normalized', 'like', '%'.$digits.'%');
                }
            });
        }

        if ($request->filled('event')) {
            $query->where('event_id', $request->integer('event'));
        }

        $status = (string) $request->query('status', '');
        if (in_array($status, [
            EventRegistration::STATUS_PENDING,
            EventRegistration::STATUS_ACCEPTED,
            EventRegistration::STATUS_REJECTED,
        ], true)) {
            $query->where('status', $status);
        }

        if ($request->query('registered') === 'today') {
            $query->whereDate('created_at', today());
        }

        return $query;
    }
}
