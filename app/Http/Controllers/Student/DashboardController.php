<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\ClassOccurrence;
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
        $today = Carbon::today();

        $rooms = $building->rooms()
            ->with('schedules.instructor')
            ->orderBy('floor')
            ->orderBy('room_number')
            ->get();

        foreach ($rooms as $room) {
            $room->occupiedSchedule = $room->schedules
                ->first(function ($schedule) use ($today) {
                    if (!$this->scheduleMatchesDate($schedule->day, $today)) {
                        return false;
                    }

                    $occurrence = $schedule->occurrences()
                        ->whereDate('class_date', $today->toDateString())
                        ->first();

                    return $occurrence
                        && $occurrence->status === 'in_progress';
                });
        }

        return view('student.rooms.building', compact(
            'building',
            'rooms'
        ));
    }

    public function roomDetails(Room $room)
    {
        $room->load('building');

        $today = Carbon::today();

        $schedules = Schedule::where('room_id', $room->id)
            ->with('instructor')
            ->get();

        $schedule = $schedules->first(function ($schedule) use ($today) {
            if (!$this->scheduleMatchesDate($schedule->day, $today)) {
                return false;
            }

            $occurrence = $schedule->occurrences()
                ->whereDate('class_date', $today->toDateString())
                ->first();

            return $occurrence
                && $occurrence->status === 'in_progress';
        });

        return view('student.rooms.show', compact(
            'room',
            'schedule'
        ));
    }

    /**
     * Match imported schedule days such as MTW, ThF,
     * MTWThF and TWThFSSu.
     */
    private function scheduleMatchesDate(
        ?string $scheduleDays,
        Carbon $date
    ): bool {
        if (!$scheduleDays) {
            return false;
        }

        $fullDays = [
            'monday' => 'M',
            'tuesday' => 'T',
            'wednesday' => 'W',
            'thursday' => 'TH',
            'friday' => 'F',
            'saturday' => 'S',
            'sunday' => 'SU',
        ];

        $selectedDay = $fullDays[strtolower($date->format('l'))];

        $storedDays = strtoupper(
            preg_replace('/[\s,\/-]+/', '', $scheduleDays)
        );

        preg_match_all('/TH|SU|[MTWFS]/', $storedDays, $matches);

        return in_array($selectedDay, $matches[0] ?? [], true);
    }
}
