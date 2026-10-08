<?php

namespace App\Http\Controllers;

use App\Exceptions\DuplicateEventRegistrationException;
use App\Http\Requests\StoreEventRegistrationRequest;
use App\Models\Event;
use App\Services\EventRegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EventRegistrationController extends Controller
{
    public function create(): View
    {
        return view('events.register', [
            'event' => Event::becomingHer(),
        ]);
    }

    public function store(StoreEventRegistrationRequest $request, EventRegistrationService $registrations): RedirectResponse
    {
        try {
            $registrations->register(Event::becomingHer(), $request->validated());
        } catch (DuplicateEventRegistrationException) {
            return back()
                ->withInput()
                ->with('already_registered', true);
        }

        return redirect()
            ->route('events.register.success')
            ->with('event_registered', true);
    }

    public function success(): View|RedirectResponse
    {
        if (! session()->has('event_registered')) {
            return redirect()->route('events.register');
        }

        session()->reflash();

        return view('events.success', [
            'event' => Event::becomingHer(),
        ]);
    }
}
