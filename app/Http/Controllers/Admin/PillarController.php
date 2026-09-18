<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pillar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PillarController extends Controller
{
    public function index(): View
    {
        return view('admin.simple.index', [
            'items' => Pillar::query()->orderBy('sort_order')->orderBy('id')->get(),
            'label' => 'Pillar',
            'routePrefix' => 'admin.pillars',
        ]);
    }

    public function create(): View
    {
        return view('admin.simple.create', ['label' => 'Pillar', 'routePrefix' => 'admin.pillars']);
    }

    public function store(Request $request): RedirectResponse
    {
        Pillar::query()->create($this->validated($request));

        return redirect()->route('admin.pillars.index')->with('success', 'Pillar added successfully.');
    }

    public function edit(Pillar $pillar): View
    {
        return view('admin.simple.edit', ['item' => $pillar, 'label' => 'Pillar', 'routePrefix' => 'admin.pillars']);
    }

    public function update(Request $request, Pillar $pillar): RedirectResponse
    {
        $pillar->update($this->validated($request));

        return redirect()->route('admin.pillars.index')->with('success', 'Pillar updated successfully.');
    }

    public function destroy(Pillar $pillar): RedirectResponse
    {
        $pillar->delete();

        return redirect()->route('admin.pillars.index')->with('success', 'Pillar deleted successfully.');
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
