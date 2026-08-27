<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Room;
use App\Models\Schedule;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('student.dashboard');
    }


   public function rooms()
{
    $buildings = Building::withCount('rooms')
        ->with('rooms')
        ->orderBy('building_name')
        ->get();

    return view('student.rooms.index', compact('buildings'));
}


    public function buildingRooms(Building $building)
    {
        $rooms = $building->rooms()
            ->orderBy('floor')
            ->orderBy('room_number')
            ->get();

        return view('student.rooms.building', compact(
            'building',
            'rooms'
        ));
    }


    public function roomDetails(Room $room)
    {
        $today = Carbon::now()->format('l');
        $current = Carbon::now()->format('H:i:s');

        $schedule = Schedule::where('room_id', $room->id)
            ->where('day', $today)
            ->where('start_time', '<=', $current)
            ->where('end_time', '>=', $current)
            ->with('instructor')
            ->first();

        return view('student.rooms.show', compact(
            'room',
            'schedule'
        ));
    }
}