<?php

namespace App\Mail;

use App\Models\EventRegistration;
use App\Services\EventCardService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventRegistrationAccepted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public EventRegistration $registration,
        public string $pdf,
    ) {}

    public function envelope(): Envelope
    {
        $eventName = $this->registration->event?->name ?? 'Becoming Her: A Journey of Growth';

        return new Envelope(
            subject: 'You Have Been Accepted — '.$eventName,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.event-accepted',
            with: [
                'cardUrl' => app(EventCardService::class)->cardUrl($this->registration),
            ],
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $name = app(EventCardService::class)->downloadName($this->registration);

        return [
            Attachment::fromData(fn () => $this->pdf, $name)->withMime('application/pdf'),
        ];
    }
}
