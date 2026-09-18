<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThematicArea;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ThematicAreaController extends Controller
{
    public function index(): View
    {
        return view('admin.simple.index', [
            'items' => ThematicArea::query()->orderBy('sort_order')->orderBy('id')->get(),
            'label' => 'Thematic Area',
            'routePrefix' => 'admin.thematic-areas',
        ]);
    }

    public function create(): View
    {
        return view('admin.simple.create', ['label' => 'Thematic Area', 'routePrefix' => 'admin.thematic-areas']);
    }

    public function store(Request $request): RedirectResponse
    {
        ThematicArea::query()->create($this->validated($request));

        return redirect()->route('admin.thematic-areas.index')->with('success', 'Thematic area added successfully.');
    }

    public function edit(ThematicArea $thematicArea): View
    {
        return view('admin.simple.edit', ['item' => $thematicArea, 'label' => 'Thematic Area', 'routePrefix' => 'admin.thematic-areas']);
    }

    public function update(Request $request, ThematicArea $thematicArea): RedirectResponse
    {
        $thematicArea->update($this->validated($request));

        return redirect()->route('admin.thematic-areas.index')->with('success', 'Thematic area updated successfully.');
    }

    public function destroy(ThematicArea $thematicArea): RedirectResponse
    {
        $thematicArea->delete();

        return redirect()->route('admin.thematic-areas.index')->with('success', 'Thematic area deleted successfully.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }
}
