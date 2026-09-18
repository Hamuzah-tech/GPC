<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $news = News::query()
            ->with('images')
            ->when($search !== '', fn ($query) => $query->where('title', 'like', '%'.$search.'%'))
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('admin.news.index', compact('news', 'search'));
    }

    public function create(): View
    {
        return view('admin.news.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $news = News::query()->create($data);
        $this->syncImages($request, $news);

        return redirect()->route('admin.news.index')->with('success', 'News published successfully.');
    }

    public function edit(News $news): View
    {
        $news->load('images');

        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news): RedirectResponse
    {
        $news->update($this->validated($request));
        $this->syncImages($request, $news);

        return redirect()->route('admin.news.index')->with('success', 'News updated successfully.');
    }

    public function destroy(News $news): RedirectResponse
    {
        foreach ($news->images as $image) {
            $this->deleteStoredFile($image->path);
        }

        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'News deleted successfully.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'published_at' => ['required', 'date'],
        ]);
    }

    private function syncImages(Request $request, News $news): void
    {
        if ($request->filled('remove_images')) {
            $ids = array_filter((array) $request->input('remove_images', []));
            $images = $news->images()->whereIn('id', $ids)->get();
            foreach ($images as $image) {
                $this->deleteStoredFile($image->path);
                $image->delete();
            }
        }

        if ($request->hasFile('images')) {
            $existingCount = $news->images()->count();
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('news', 'public');
                $news->images()->create([
                    'path' => 'storage/'.$path,
                    'is_cover' => $existingCount === 0 && $index === 0,
                    'sort_order' => $existingCount + $index,
                ]);
            }
        }

        if ($request->filled('cover_image_id')) {
            $news->images()->update(['is_cover' => false]);
            NewsImage::query()
                ->where('news_id', $news->id)
                ->where('id', $request->integer('cover_image_id'))
                ->update(['is_cover' => true]);
        } elseif ($news->images()->exists() && ! $news->images()->where('is_cover', true)->exists()) {
            $news->images()->orderBy('id')->first()?->update(['is_cover' => true]);
        }
    }

    private function deleteStoredFile(string $path): void
    {
        $relative = str_starts_with($path, 'storage/') ? substr($path, 8) : $path;
        if (Storage::disk('public')->exists($relative)) {
            Storage::disk('public')->delete($relative);
        }
    }
}
