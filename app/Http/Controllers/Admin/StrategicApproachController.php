<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StrategicApproach;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StrategicApproachController extends Controller
{
    public function index(): View
    {
        return view('admin.simple.index', [
            'items' => StrategicApproach::query()->orderBy('sort_order')->orderBy('id')->get(),
            'label' => 'Strategic Approach',
            'routePrefix' => 'admin.strategic-approaches',
        ]);
    }

    public function create(): View
    {
        return view('admin.simple.create', ['label' => 'Strategic Approach', 'routePrefix' => 'admin.strategic-approaches']);
    }

    public function store(Request $request): RedirectResponse
    {
        StrategicApproach::query()->create($this->validated($request));

        return redirect()->route('admin.strategic-approaches.index')->with('success', 'Strategic approach added successfully.');
    }

    public function edit(StrategicApproach $strategicApproach): View
    {
        return view('admin.simple.edit', ['item' => $strategicApproach, 'label' => 'Strategic Approach', 'routePrefix' => 'admin.strategic-approaches']);
    }

    public function update(Request $request, StrategicApproach $strategicApproach): RedirectResponse
    {
        $strategicApproach->update($this->validated($request));

        return redirect()->route('admin.strategic-approaches.index')->with('success', 'Strategic approach updated successfully.');
    }

    public function destroy(StrategicApproach $strategicApproach): RedirectResponse
    {
        $strategicApproach->delete();

        return redirect()->route('admin.strategic-approaches.index')->with('success', 'Strategic approach deleted successfully.');
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
