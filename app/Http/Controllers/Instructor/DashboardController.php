<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;
use App\Models\Room;
use Carbon\Carbon;
use App\Models\Building;
use App\Models\RoomUsage;

class DashboardController extends Controller
{
    public function index()
{
    $today = Carbon::now()->format('l');

    $todaySchedules = Schedule::with('room')
        ->where('instructor_id', auth()->id())
        ->where('day', $today)
        ->orderBy('start_time')
        ->get();

    $todayClasses = $todaySchedules->count();

    $availableRooms = Room::count() -
        Schedule::where('status', 'in_progress')->count();

    $nextClass = $todaySchedules
        ->where('status', 'pending')
        ->sortBy('start_time')
        ->first();

    $semester = Schedule::where('instructor_id', auth()->id())
        ->latest()
        ->value('semester');

    return view('instructor.dashboard', compact(
        'todaySchedules',
        'todayClasses',
        'availableRooms',
        'nextClass',
        'semester'
    ));
}

    public function schedule()
    {
        $schedules = Schedule::where('instructor_id', Auth::id())
            ->orderBy('day')
            ->orderBy('start_time')
            ->paginate(10);

        return view('instructor.schedule', compact('schedules'));
    }

public function rooms()
{
    $today = Carbon::now()->format('l');

    $current = Carbon::now()->format('H:i:s');

    $rooms = Room::with(['building','schedules'])
        ->orderBy('building_id')
        ->orderBy('room_number')
        ->get();

    foreach ($rooms as $room) {

        $occupied = Schedule::where('room_id', $room->id)
            ->where('day', $today)
            ->where('start_time','<=',$current)
            ->where('end_time','>=',$current)
            ->first();

        $room->occupiedSchedule = $occupied;

    }

    return view('instructor.rooms', compact('rooms'));
}

public function startClass(Schedule $schedule)
{
    abort_if(
        $schedule->instructor_id != auth()->id(),
        403
    );

    $schedule->update([
        'status' => 'in_progress'
    ]);

    return back()->with(
        'success',
        'Class started successfully.'
    );
}

public function endClass(Schedule $schedule)
{
    abort_if(
        $schedule->instructor_id != auth()->id(),
        403
    );

    $schedule->update([
        'status' => 'completed'
    ]);

    return back()->with(
        'success',
        'Class ended successfully.'
    );
}

public function buildings()
{
    $buildings = Building::withCount('rooms')
        ->orderBy('building_name')
        ->get();

    return view('instructor.rooms.index', compact('buildings'));
}

public function buildingRooms(Building $building)
{
    $rooms = $building->rooms()
        ->orderBy('floor')
        ->orderBy('room_number')
        ->get();

    return view('instructor.rooms.building', compact('building', 'rooms'));
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

    return view('instructor.rooms.show', compact('room', 'schedule'));
}

}