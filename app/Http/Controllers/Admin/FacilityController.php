<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::withCount('reservations')
            ->orderBy('name')
            ->get();

        return view('admin.facility-reservations.facilities', compact('facilities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:facilities,name'],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'capacity' => ['nullable', 'integer', 'min:1'],
        ]);

        $validated['is_active'] = true;

        Facility::create($validated);

        return back()->with('success', 'Facility added successfully.');
    }

    public function update(Request $request, Facility $facility)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('facilities', 'name')->ignore($facility->id),
            ],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'capacity' => ['nullable', 'integer', 'min:1'],
        ]);

        $facility->update($validated);

        return back()->with('success', 'Facility updated successfully.');
    }

    public function toggleStatus(Facility $facility)
    {
        $facility->update([
            'is_active' => !$facility->is_active,
        ]);

        return back()->with(
            'success',
            $facility->is_active
                ? 'Facility activated successfully.'
                : 'Facility deactivated successfully.'
        );
    }
}
