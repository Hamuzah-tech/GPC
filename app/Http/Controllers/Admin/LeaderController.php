<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LeaderController extends Controller
{
    public function index(): View
    {
        return view('admin.leaders.index', [
            'leaders' => Leader::query()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.leaders.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['image'] = $this->storeImage($request) ?? $data['image'] ?? null;
        Leader::query()->create($data);

        return redirect()->route('admin.leaders.index')->with('success', 'Leader added successfully.');
    }

    public function edit(Leader $leader): View
    {
        return view('admin.leaders.edit', compact('leader'));
    }

    public function update(Request $request, Leader $leader): RedirectResponse
    {
        $data = $this->validated($request);
        if ($path = $this->storeImage($request)) {
            $data['image'] = $path;
        }
        $leader->update($data);

        return redirect()->route('admin.leaders.index')->with('success', 'Leader updated successfully.');
    }

    public function destroy(Leader $leader): RedirectResponse
    {
        $leader->delete();

        return redirect()->route('admin.leaders.index')->with('success', 'Leader deleted successfully.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'bio_preview' => ['nullable', 'string'],
            'full_bio' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable'],
        ]);
    }

    private function storeImage(Request $request): ?string
    {
        if (! $request->hasFile('photo')) {
            return null;
        }

        $path = $request->file('photo')->store('leaders', 'public');

        return 'storage/'.$path;
    }
}
