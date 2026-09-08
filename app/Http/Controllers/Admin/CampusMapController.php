<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CampusLocation;
use Illuminate\Http\Request;

class CampusMapController extends Controller
{
    public function index()
    {
        $locations = CampusLocation::orderBy('number')->get();

        return view('admin.campus-map', compact('locations'));
    }

    public function savePositions(Request $request)
    {
        $validated = $request->validate([
            'positions' => ['required', 'array'],
            'positions.*.id' => [
                'required',
                'integer',
                'exists:campus_locations,id'
            ],
            'positions.*.map_x' => [
                'required',
                'numeric',
                'min:0',
                'max:100'
            ],
            'positions.*.map_y' => [
                'required',
                'numeric',
                'min:0',
                'max:100'
            ],
        ]);

        foreach ($validated['positions'] as $position) {

            CampusLocation::whereKey($position['id'])->update([
                'map_x' => $position['map_x'],
                'map_y' => $position['map_y'],
            ]);

        }

        return response()->json([
            'success' => true,
            'message' => 'Campus map positions saved.',
        ]);
    }

    public function resetPositions()
    {
        CampusLocation::query()->update([
            'map_x' => null,
            'map_y' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'All marker positions have been reset.',
        ]);
    }
}