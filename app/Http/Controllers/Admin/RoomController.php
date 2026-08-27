<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Building;
use App\Models\Room;
use Illuminate\Http\Request;
use App\Imports\RoomsImport;
use App\Exports\RoomsExport;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

class RoomController extends Controller
{
    /**
     * Room Management
     */
    public function index(Request $request)
    {
        $query = Room::with('building');

        // Search

        if ($request->filled('search')) {

            $query->where(function ($q) use ($request) {

                $q->where('room_name', 'like', '%' . $request->search . '%')
                  ->orWhere('room_number', 'like', '%' . $request->search . '%');

            });

        }

        // Building Filter

        if ($request->filled('building')) {

            $query->where('building_id', $request->building);

        }

        // Floor Filter

        if ($request->filled('floor')) {

            $query->where('floor', $request->floor);

        }

        $rooms = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.rooms.index', [

            'rooms' => $rooms,

            'buildings' => Building::orderBy('building_name')->get(),

            'roomCount' => Room::count(),

            'buildingCount' => Building::count(),

            'capacityCount' => Room::sum('capacity'),

            'highestFloor' => Room::max('floor'),

        ]);
    }

    /**
     * Store Room
     */
    public function store(StoreRoomRequest $request)
    {
        Room::create($request->validated());

        return back()->with(
            'success',
            'Room added successfully.'
        );
    }

    /**
     * View Room (AJAX)
     */
    public function show(Room $room)
    {
        return response()->json([

            'id' => $room->id,

            'room_name' => $room->room_name,

            'room_number' => $room->room_number,

            'building' => $room->building->building_name,

            'capacity' => $room->capacity,

            'floor' => $room->floor,

            'created_at' => $room->created_at->format('F d, Y'),

            'updated_at' => $room->updated_at->format('F d, Y'),

        ]);
    }

    /**
     * Edit Room (AJAX)
     */
    public function edit(Room $room)
    {
        return response()->json($room);
    }

    /**
     * Update Room
     */
    public function update(UpdateRoomRequest $request, Room $room)
    {
        $room->update($request->validated());

        return back()->with(
            'success',
            'Room updated successfully.'
        );
    }

    /**
     * Delete Room
     */
    public function destroy(Room $room)
    {
        $room->delete();

        return back()->with(
            'success',
            'Room deleted successfully.'
        );
    }

    /**
 * Import Page
 */
public function importForm()
{
    return view('admin.rooms.import');
}

/**
 * Import Rooms
 */
public function importRooms(Request $request)
{
    $request->validate([
    'file' => 'required|file|extensions:xlsx,xls,csv|max:10240',
]);

    $import = new RoomsImport();

    Excel::import($import, $request->file('file'));

    session([
        'room_import_preview' => $import->rooms,
    ]);

    return redirect()->route('rooms.preview');
}

/**
 * Preview
 */
public function preview()
{
    $rooms = session('room_import_preview', []);

    $total = count($rooms);

    $ready = collect($rooms)
        ->where('status', 'new')
        ->count();

    $duplicates = collect($rooms)
        ->where('status', 'duplicate')
        ->count();

    $invalid = collect($rooms)
        ->where('status', 'invalid')
        ->count();

    return view(
    'admin.rooms.partials.import-preview',
    compact(
        'rooms',
        'total',
        'ready',
        'duplicates',
        'invalid'
    )
);
}

/**
 * Save Imported Rooms
 */
public function storeImportedRooms()
{
    $rooms = session('room_import_preview', []);

    foreach ($rooms as $room) {

        if ($room['status'] !== 'new') {
            continue;
        }

        Room::create([

            'building_id' => $room['building_id'],

            'room_number' => $room['room_number'],

            'room_name' => $room['room_name'],

            'capacity' => $room['capacity'],

            'floor' => $room['floor'],

        ]);
    }

    session()->forget('room_import_preview');

    return redirect()
        ->route('rooms.index')
        ->with(
            'success',
            'Rooms imported successfully.'
        );
}

/**
 * Download Template
 */
public function template()
{
    $headers = [

        "Building Name",

        "Room Number",

        "Room Name",

        "Capacity",

        "Floor"

    ];

    $callback = function () use ($headers) {

        $file = fopen('php://output', 'w');

        fputcsv($file, $headers);

        fclose($file);

    };

    return Response::stream(

        $callback,

        200,

        [

            "Content-Type" => "text/csv",

            "Content-Disposition" =>
                "attachment; filename=room_template.csv",

        ]

    );
}

/**
 * Export Rooms
 */
public function export()
{
    return Excel::download(

        new RoomsExport,

        'rooms.xlsx'

    );
}
}