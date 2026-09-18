<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\GalleryImage;
use App\Models\Leader;
use App\Models\News;
use App\Models\Page;
use App\Models\Pillar;
use App\Models\Program;
use App\Models\StrategicApproach;
use App\Models\ThematicArea;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('pages.home', [
            'about' => Page::bySlug('home-about'),
            'heroSlides' => GalleryImage::query()->where('type', 'hero')->orderBy('sort_order')->get(),
            'galleryImages' => GalleryImage::query()->where('type', 'gallery')->orderBy('sort_order')->get(),
            'newsItems' => News::query()->with('images')->orderByDesc('published_at')->orderByDesc('id')->take(8)->get(),
        ]);
    }

    public function leadership(): View
    {
        return view('pages.leadership', [
            'leaders' => Leader::query()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function mission(): View
    {
        return view('pages.mission', [
            'page' => Page::bySlug('mission'),
        ]);
    }

    public function vision(): View
    {
        return view('pages.vision', [
            'page' => Page::bySlug('vision'),
        ]);
    }

    public function pillars(): View
    {
        return view('pages.pillars', [
            'pillars' => Pillar::query()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function thematicAreas(): View
    {
        return view('pages.thematic-areas', [
            'areas' => ThematicArea::query()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function strategicApproach(): View
    {
        return view('pages.strategic-approach', [
            'approaches' => StrategicApproach::query()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function programs(): View
    {
        return view('pages.programs', [
            'programs' => Program::query()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function volunteer(): View
    {
        return view('pages.volunteer');
    }

    public function contact(): View
    {
        return view('pages.contact', [
            'faqs' => Faq::query()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function news(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $news = News::query()
            ->with('images')
            ->when($search !== '', fn ($query) => $query->where('title', 'like', '%'.$search.'%'))
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(6)
            ->withQueryString();

        return view('pages.news', [
            'news' => $news,
            'search' => $search,
        ]);
    }
}
