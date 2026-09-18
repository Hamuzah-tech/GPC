<?php

namespace App\Http\Controllers;

use App\Mail\VolunteerApplicationReceived;
use App\Models\VolunteerApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class VolunteerController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'age' => ['nullable', 'integer', 'min:18', 'max:100'],
            'gender' => ['nullable', 'string', 'max:50'],
            'location' => ['required', 'string', 'max:100'],
            'skills' => ['nullable', 'string', 'max:255'],
            'interests' => ['nullable', 'array'],
            'interests.*' => ['string', 'max:100'],
            'availability' => ['nullable', 'string', 'max:100'],
            'message' => ['required', 'string', 'max:5000'],
            'cv' => ['required', 'file', 'mimes:pdf', 'mimetypes:application/pdf', 'max:5120'],
        ]);

        $cv = $data['cv'];
        unset($data['cv']);

        $data['cv_path'] = $cv->store('volunteer-cvs', 'local');
        $data['cv_original_name'] = $cv->getClientOriginalName();

        $application = VolunteerApplication::query()->create($data);

        try {
            Mail::to($application->email)->send(new VolunteerApplicationReceived($application));
        } catch (\Throwable $e) {
            report($e);
        }

        return response()->json([
            'ok' => true,
            'message' => 'Thank you '.$application->full_name.'! Your volunteer application has been received. A confirmation email has been sent to '.$application->email.'.',
        ]);
    }
}
