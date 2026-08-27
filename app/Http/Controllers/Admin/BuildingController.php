<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBuildingRequest;
use App\Http\Requests\UpdateBuildingRequest;
use App\Models\Building;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Imports\BuildingsImport;
use App\Exports\BuildingsExport;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

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

        /*
        |--------------------------------------------------------------------------
        | Automatically generate Building Code
        |--------------------------------------------------------------------------
        */

        $data['building_code'] = $this->generateBuildingCode();

        Building::create($data);

        return back()->with(
            'success',
            'Building added successfully.'
        );
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

        /*
        |--------------------------------------------------------------------------
        | Keep existing building code
        |--------------------------------------------------------------------------
        */

        $data['building_code'] = $building->building_code;

        if ($request->hasFile('image')) {

            if ($building->image) {
                Storage::disk('public')
                    ->delete($building->image);
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
        if ($building->image) {
            Storage::disk('public')
                ->delete($building->image);
        }

        $building->delete();

        return back()->with(
            'success',
            'Building deleted successfully.'
        );
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

        Excel::import(
            $import,
            $request->file('file')
        );

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
        $buildings = session(
            'building_import_preview',
            []
        );

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
            compact(
                'buildings',
                'total',
                'ready',
                'duplicates',
                'invalid'
            )
        );
    }

    /**
     * Store Imported Buildings
     */
    public function storeImportedBuildings()
    {
        $buildings = session(
            'building_import_preview',
            []
        );

        foreach ($buildings as $building) {

            if (($building['status'] ?? null) !== 'new') {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Use the code from the Excel importer.
            | If there isn't one, generate one automatically.
            |--------------------------------------------------------------------------
            */

            $buildingCode =
                $building['building_code']
                ?? $this->generateBuildingCode();

            Building::create([
                'building_code' => $buildingCode,
                'building_name' => $building['building_name'],
            ]);
        }

        session()->forget(
            'building_import_preview'
        );

        return redirect()
            ->route('buildings.index')
            ->with(
                'success',
                'Buildings imported successfully.'
            );
    }

    /**
     * Download Building Template
     */
    public function template()
    {
        $headers = [
            'Building Name',
        ];

        $callback = function () use ($headers) {

            $file = fopen(
                'php://output',
                'w'
            );

            fputcsv(
                $file,
                $headers
            );

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
        return Excel::download(
            new BuildingsExport,
            'buildings.xlsx'
        );
    }

    /**
     * Generate the next Building Code
     *
     * Example:
     * B01
     * B02
     * B03
     */
    private function generateBuildingCode(): string
    {
        $lastBuilding = Building::orderByRaw(
            "CAST(SUBSTRING(building_code, 2) AS UNSIGNED) DESC"
        )->first();

        if (!$lastBuilding || !$lastBuilding->building_code) {
            return 'B01';
        }

        $number = (int) substr(
            $lastBuilding->building_code,
            1
        );

        return 'B' . str_pad(
            $number + 1,
            2,
            '0',
            STR_PAD_LEFT
        );
    }
}