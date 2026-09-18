<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        return view('admin.gallery.index', [
            'images' => GalleryImage::query()->orderBy('type')->orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'photo' => ['required', 'image', 'max:5120'],
            'alt' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:gallery,hero'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        GalleryImage::query()->create([
            'path' => 'storage/'.$request->file('photo')->store('gallery', 'public'),
            'alt' => $data['alt'] ?? null,
            'type' => $data['type'],
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.gallery.index')->with('success', 'Image added successfully.');
    }

    public function destroy(GalleryImage $gallery): RedirectResponse
    {
        $gallery->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'Image deleted successfully.');
    }
}
