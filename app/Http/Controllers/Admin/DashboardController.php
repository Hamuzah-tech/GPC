<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\EventRegistration;
use App\Models\Leader;
use App\Models\News;
use App\Models\Program;
use App\Models\VolunteerApplication;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'newsCount' => News::query()->count(),
            'imageCount' => News::query()->withCount('images')->get()->sum('images_count'),
            'leaderCount' => Leader::query()->count(),
            'programCount' => Program::query()->count(),
            'messageCount' => ContactMessage::query()->count(),
            'unreadMessages' => ContactMessage::query()->where('is_read', false)->count(),
            'volunteerCount' => VolunteerApplication::query()->count(),
            'registrationCount' => EventRegistration::query()->count(),
            'pendingRegistrations' => EventRegistration::query()->where('status', EventRegistration::STATUS_PENDING)->count(),
            'acceptedRegistrations' => EventRegistration::query()->where('status', EventRegistration::STATUS_ACCEPTED)->count(),
            'rejectedRegistrations' => EventRegistration::query()->where('status', EventRegistration::STATUS_REJECTED)->count(),
            'todayRegistrations' => EventRegistration::query()->whereDate('created_at', today())->count(),
            'recentNews' => News::query()->with('images')->latest('published_at')->latest('id')->take(5)->get(),
            'recentMessages' => ContactMessage::query()->latest()->take(5)->get(),
        ]);
    }
}
