<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CampusLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Building;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class CampusMapController extends Controller
{
   

public function index()
{
    $locations = CampusLocation::with('building')
        ->orderBy('number')
        ->get();

    $buildings = Building::with([
        'rooms' => function ($query) {
            $query->orderBy('floor')
                  ->orderBy('room_number');
        }
    ])->orderBy('building_code')->get();

    return view('admin.campus-map', compact('locations', 'buildings'));
}


    public function savePositions(Request $request)
    {
        $validated = $request->validate([
            'positions' => ['required', 'array', 'min:1'],
            'positions.*.id' => [
                'required',
                'integer',
                'distinct',
                'exists:campus_locations,id',
            ],
            'positions.*.map_x' => [
                'required',
                'numeric',
                'between:0,100',
            ],
            'positions.*.map_y' => [
                'required',
                'numeric',
                'between:0,100',
            ],
            'positions.*.marker_size' => [
                'sometimes',
                'required',
                'integer',
                'between:16,64',
            ],
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['positions'] as $position) {
                $updates = [
                    'map_x' => $position['map_x'],
                    'map_y' => $position['map_y'],
                ];

                if (array_key_exists('marker_size', $position)) {
                    $updates['marker_size'] = $position['marker_size'];
                }

                CampusLocation::whereKey($position['id'])
                    ->update($updates);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Campus map positions and marker sizes saved successfully.',
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

    public function removePosition(\App\Models\CampusLocation $location)
{
    $location->map_x = null;
    $location->map_y = null;
    $location->save();

    return response()->json([
        'message' => 'Location removed from the map. You can place it again later.',
    ]);
}


public function updateBuilding(Request $request, Building $building)
{
    $validated = $request->validate([
        'building_name' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string', 'max:5000'],
        'total_floor_area' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
        'year_constructed' => ['nullable', 'integer', 'min:1000', 'max:' . (date('Y') + 1)],
        'classroom_count' => ['nullable', 'integer', 'min:0'],
        'laboratory_count' => ['nullable', 'integer', 'min:0'],
        'office_count' => ['nullable', 'integer', 'min:0'],
        'restroom_count' => ['nullable', 'integer', 'min:0'],
        'function_room_count' => ['nullable', 'integer', 'min:0'],
    ]);

    $oldName = $building->building_name;
    $changedFields = [];

    foreach ($validated as $field => $value) {
        $oldValue = $building->{$field};

        if ((string) ($oldValue ?? '') !== (string) ($value ?? '')) {
            $changedFields[] = $field;
        }
    }

    DB::transaction(function () use ($building, $validated) {
        $building->update($validated);

        // Keep linked campus map labels synchronized after renaming.
        CampusLocation::where('building_id', $building->id)
            ->update([
                'name' => $validated['building_name'],
            ]);
    });

   
if (!empty($changedFields)) {
    try {
    $userId = Auth::guard('web')->id();

    ActivityLog::create([
        'user_id' => $userId,
        'user_name' => Auth::guard('web')->user()?->name ?? 'System Administrator',
        'module' => 'Campus Map',
        'action' => 'Updated building information',
        'description' => sprintf(
            'Updated building "%s" (Code: %s). Changed fields: %s.',
            $oldName,
            $building->building_code,
            implode(', ', $changedFields)
        ),
    ]);
} catch (\Throwable $e) {
    report($e);
}
}


    return redirect()
        ->route('admin.campus-map')
        ->with('success', 'Building information updated successfully.');
}



public function linkBuilding(Request $request, \App\Models\CampusLocation $location)
{
    $validated = $request->validate([
        'building_id' => [
            'required',
            'integer',
            'exists:buildings,id',
        ],
    ]);

    $building = \App\Models\Building::findOrFail(
        $validated['building_id']
    );

    \Illuminate\Support\Facades\DB::transaction(function () use ($location, $building) {
        $location->update([
            'building_id' => $building->id,
            'name' => $building->building_name,
        ]);
    });

    return redirect()
        ->route('admin.campus-map')
        ->with('success', 'Campus location linked to building successfully.');
}



public function updateLocation(Request $request, CampusLocation $location)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string', 'max:5000'],
        'total_floor_area' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
        'year_constructed' => ['nullable', 'integer', 'min:1000', 'max:' . (date('Y') + 1)],
        'classroom_count' => ['nullable', 'integer', 'min:0'],
        'laboratory_count' => ['nullable', 'integer', 'min:0'],
        'office_count' => ['nullable', 'integer', 'min:0'],
        'restroom_count' => ['nullable', 'integer', 'min:0'],
        'function_room_count' => ['nullable', 'integer', 'min:0'],
    ]);

    
$oldName = $location->name;
$changedFields = [];

foreach ($validated as $field => $value) {
    if ((string) ($location->{$field} ?? '') !== (string) ($value ?? '')) {
        $changedFields[] = $field;
    }
}

$location->update($validated);


if (!empty($changedFields)) {
    
try {
    $userId = Auth::guard('web')->id();

    ActivityLog::create([
        'user_id' => $userId,
        'user_name' => Auth::guard('web')->user()?->name
            ?? 'System Administrator',
        'module' => 'Campus Map',
        'action' => 'Updated campus location',
        'description' => sprintf(
            'Updated campus location #%s "%s". Changed fields: %s.',
            $location->number,
            $oldName,
            implode(', ', $changedFields)
        ),
    ]);
} catch (\Throwable $e) {
    report($e);
}

}



    return redirect()
        ->route('admin.campus-map')
        ->with('success', 'Campus location information updated successfully.');
}



}

