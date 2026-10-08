<?php

namespace App\Services;

use App\Models\EventRegistration;
use Dompdf\Dompdf;
use Dompdf\Options;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\View;

class EventCardService
{
    public function verificationUrl(EventRegistration $registration): string
    {
        return route('events.verify', ['code' => $registration->registration_id]);
    }

    public function cardUrl(EventRegistration $registration): string
    {
        return route('events.card', ['token' => $registration->verification_token]);
    }

    public function qrDataUri(EventRegistration $registration): string
    {
        $qrCode = new QrCode(
            data: $this->verificationUrl($registration),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 280,
            margin: 8,
        );

        return (new PngWriter)->write($qrCode)->getDataUri();
    }

    /**
     * @return array{registration: EventRegistration, qrDataUri: string, logoDataUri: ?string}
     */
    public function viewData(EventRegistration $registration): array
    {
        $registration->loadMissing('event');

        return [
            'registration' => $registration,
            'qrDataUri' => $this->qrDataUri($registration),
            'logoDataUri' => $this->logoDataUri(),
        ];
    }

    public function pdfBinary(EventRegistration $registration): string
    {
        $options = new Options;
        $options->set('isRemoteEnabled', false);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(View::make('events.pdf.card', $this->viewData($registration))->render());
        $dompdf->setPaper([0, 0, 420, 700]);
        $dompdf->render();

        return $dompdf->output();
    }

    public function downloadName(EventRegistration $registration): string
    {
        $id = $registration->registration_id ?: 'event-card';

        return 'Becoming-Her-'.$id.'.pdf';
    }

    private function logoDataUri(): ?string
    {
        $path = public_path('images/logo.png');

        if (! is_file($path)) {
            return null;
        }

        return 'data:image/png;base64,'.base64_encode((string) file_get_contents($path));
    }
}
