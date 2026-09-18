<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VolunteerApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VolunteerApplicationController extends Controller
{
    public function index(): View
    {
        return view('admin.volunteers.index', [
            'applications' => VolunteerApplication::query()->latest()->paginate(15),
        ]);
    }

    public function show(VolunteerApplication $volunteerApplication): View
    {
        return view('admin.volunteers.show', ['application' => $volunteerApplication]);
    }

    public function downloadCv(VolunteerApplication $volunteerApplication): StreamedResponse
    {
        abort_unless(
            is_string($volunteerApplication->cv_path)
            && $volunteerApplication->cv_path !== ''
            && Storage::disk('local')->exists($volunteerApplication->cv_path),
            404
        );

        $name = $volunteerApplication->cv_original_name ?: 'volunteer-cv.pdf';

        return Storage::disk('local')->download($volunteerApplication->cv_path, $name, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function destroy(VolunteerApplication $volunteerApplication): RedirectResponse
    {
        if (is_string($volunteerApplication->cv_path) && $volunteerApplication->cv_path !== '') {
            Storage::disk('local')->delete($volunteerApplication->cv_path);
        }

        $volunteerApplication->delete();

        return redirect()->route('admin.volunteers.index')->with('success', 'Application deleted successfully.');
    }
}
