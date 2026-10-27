<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBuildingRequest;
use App\Http\Requests\UpdateBuildingRequest;
use App\Models\ActivityLog;
use App\Models\Building;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Imports\BuildingsImport;
use App\Exports\BuildingsExport;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\CampusLocation;

class BuildingController extends Controller
{
    /**
     * Building Management
     */
    public function index(Request $request)
    {
        $query = Building::query();

        if ($request->filled('search')) {
            $query->where(
                'building_name',
                'like',
                '%' . $request->search . '%'
            );
        }

        $buildings = $query
            ->withCount('rooms')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.buildings.index', [
            'buildings' => $buildings,
            'buildingCount' => Building::count(),
            'roomCount' => Room::count(),
            'imageCount' => Building::whereNotNull('image')->count(),
        ]);
    }

    
/**
 * Store Building
 */
public function store(StoreBuildingRequest $request)
{
    $data = $request->validated();

    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')
            ->store('buildings', 'public');
    }

    $data['building_code'] = $this->generateBuildingCode();

    \Illuminate\Support\Facades\DB::transaction(function () use ($data, &$building) {
        $building = Building::create($data);

        $this->createCampusLocationForBuilding($building);

        $this->logActivity(
            'Created',
            'Buildings',
            "Added building: {$building->building_name} ({$building->building_code})"
        );
    });

    return back()->with('success', 'Building added successfully.');
}


    /**
     * View Building (AJAX)
     */
    public function show(Building $building)
    {
        return response()->json([
            'id' => $building->id,
            'building_code' => $building->building_code,
            'building_name' => $building->building_name,
            'description' => $building->description,
            'image' => $building->image
                ? asset('storage/' . $building->image)
                : null,
            'rooms' => $building->rooms()->count(),
            'created_at' => $building->created_at->format('F d, Y'),
            'updated_at' => $building->updated_at->format('F d, Y'),
        ]);
    }

    /**
     * Edit Building (AJAX)
     */
    public function edit(Building $building)
    {
        return response()->json([
            'id' => $building->id,
            'building_code' => $building->building_code,
            'building_name' => $building->building_name,
            'description' => $building->description,
            'image' => $building->image
                ? asset('storage/' . $building->image)
                : null,
        ]);
    }

    /**
     * Update Building
     */
    public function update(
        UpdateBuildingRequest $request,
        Building $building
    ) {
        $data = $request->validated();

        // Keep existing building code.
        $data['building_code'] = $building->building_code;

        if ($request->hasFile('image')) {
            if ($building->image) {
                Storage::disk('public')->delete($building->image);
            }

            $data['image'] = $request->file('image')
                ->store('buildings', 'public');
        }

        $building->update($data);

        return back()->with(
            'success',
            'Building updated successfully.'
        );
    }

    
/**
 * Delete Building
 */
public function destroy(Building $building)
{
    \Illuminate\Support\Facades\DB::transaction(function () use ($building) {
        // Remove the linked campus map location first.
        CampusLocation::where('building_id', $building->id)->delete();

        // Delete the building image if one exists.
        if ($building->image) {
            Storage::disk('public')->delete($building->image);
        }

        // Delete the building record.
        $building->delete();

        $this->logActivity(
            'Deleted',
            'Buildings',
            "Deleted building: {$building->building_name} ({$building->building_code})"
        );
    });

    return back()->with(
        'success',
        'Building and its campus map marker deleted successfully.'
    );
}


  
/**
 * Delete All Buildings
 */
public function deleteAll()
{
    \Illuminate\Support\Facades\DB::transaction(function () {
        $buildings = Building::all();

        foreach ($buildings as $building) {
            CampusLocation::where('building_id', $building->id)->delete();

            if ($building->image) {
                Storage::disk('public')->delete($building->image);
            }

            $building->delete();
        }

        $this->logActivity(
            'Deleted',
            'Buildings',
            'Deleted all buildings and their linked campus map markers.'
        );
    });

    return redirect()
        ->route('buildings.index')
        ->with('success', 'All buildings and their linked campus map markers deleted successfully.');
}


    /**
     * Building Import Form
     */
    public function importForm()
    {
        return view('admin.buildings.import');
    }

    /**
     * Import Buildings
     */
    public function importBuildings(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $import = new BuildingsImport();

        Excel::import($import, $request->file('file'));

        session([
            'building_import_preview' => $import->buildings,
        ]);

        return redirect()->route('buildings.preview');
    }

    /**
     * Building Import Preview
     */
    public function preview()
    {
        $buildings = session('building_import_preview', []);

        $total = count($buildings);

        $ready = collect($buildings)
            ->where('status', 'new')
            ->count();

        $duplicates = collect($buildings)
            ->where('status', 'duplicate')
            ->count();

        $invalid = collect($buildings)
            ->where('status', 'invalid')
            ->count();

        return view(
            'admin.buildings.import-preview',
            compact('buildings', 'total', 'ready', 'duplicates', 'invalid')
        );
    }

   
/**
 * Store Imported Buildings
 */
public function storeImportedBuildings()
{
    $buildings = session('building_import_preview', []);

    foreach ($buildings as $building) {
        if (($building['status'] ?? null) !== 'new') {
            continue;
        }

        $buildingCode = $building['building_code']
            ?? $this->generateBuildingCode();

        $createdBuilding = Building::create([
            'building_code' => $buildingCode,
            'building_name' => $building['building_name'],
            'description' => $building['description'] ?? null,
        ]);
        $this->createCampusLocationForBuilding($createdBuilding);

        // Automatically add the building to the campus map.
        $this->createCampusLocationForBuilding($createdBuilding);

        $this->logActivity(
            'Created',
            'Buildings',
            "Imported building: {$createdBuilding->building_name} ({$createdBuilding->building_code})"
        );
    }

    session()->forget('building_import_preview');

    return redirect()
        ->route('buildings.index')
        ->with('success', 'Buildings imported successfully.');
}


    /**
     * Download Building Template
     */
    public function template()
    {
        $headers = ['Building Name'];

        $callback = function () use ($headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            fclose($file);
        };

        return Response::stream(
            $callback,
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' =>
                    'attachment; filename=building_template.csv',
            ]
        );
    }

    /**
     * Export Buildings
     */
    public function export()
    {
        return Excel::download(new BuildingsExport, 'buildings.xlsx');
    }

    /**
     * Generate the next Building Code (B01, B02, B03...)
     */
    
/**
 * Automatically create an unplaced campus map location for a building.
 */
private function createCampusLocationForBuilding(Building $building): CampusLocation
{
    // Avoid duplicate locations for the same building.
    $existingLocation = CampusLocation::where(
        'building_id',
        $building->id
    )->first();

    if ($existingLocation) {
        return $existingLocation;
    }

    // Assign the next available location number.
    $nextNumber = ((int) CampusLocation::max('number')) + 1;

    return CampusLocation::create([
        'number' => $nextNumber,
        'name' => $building->building_name,
        'type' => 'Building',
        'building_id' => $building->id,
        'map_x' => null,
        'map_y' => null,
        'marker_size' => 28,
    ]);
}

    private function generateBuildingCode(): string
    {
        $lastBuilding = Building::orderByRaw(
            "CAST(SUBSTRING(building_code, 2) AS UNSIGNED) DESC"
        )->first();

        if (!$lastBuilding || !$lastBuilding->building_code) {
            return 'B01';
        }

        $number = (int) substr($lastBuilding->building_code, 1);

        return 'B' . str_pad(
            $number + 1,
            2,
            '0',
            STR_PAD_LEFT
        );
    }

    /**
     * Record activity in the activity log.
     */
    private function logActivity(
        string $action,
        string $module,
        string $description
    ): void {
        $user = auth()->user();

        ActivityLog::create([
            'user_id' => $user?->id,
            'user_name' => $user
                ? trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''))
                : 'Unknown User',
            'action' => $action,
            'module' => $module,
            'description' => $description,
        ]);
    }
}
