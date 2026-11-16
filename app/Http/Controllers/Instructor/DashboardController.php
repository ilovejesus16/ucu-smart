<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\ClassOccurrence;
use Illuminate\Support\Facades\Auth;
use App\Models\Room;
use Carbon\Carbon;
use App\Models\Building;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::now();
        $current = $today->format('H:i:s');

        $todaySchedules = Schedule::with('room')
            ->where('instructor_id', auth()->id())
            ->get()
            ->filter(fn ($schedule) =>
                $this->scheduleMatchesDate($schedule->day, $today)
            )
            ->sortBy('start_time')
            ->values();

        foreach ($todaySchedules as $schedule) {
            $schedule->todayOccurrence = $this->getTodayOccurrence($schedule, $today);
        }

        $todayClasses = $todaySchedules->count();

        $occupiedRoomCount = ClassOccurrence::whereDate(
            'class_date',
            $today->toDateString()
        )
            ->where('status', 'in_progress')
            ->whereHas('schedule')
            ->distinct()
            ->count('schedule_id');

        $availableRooms = max(0, Room::count() - $occupiedRoomCount);

        $nextClass = $todaySchedules
            ->filter(function ($schedule) use ($current) {
                $occurrence = $schedule->todayOccurrence;

                return $occurrence
                    && $occurrence->status === 'scheduled'
                    && $schedule->start_time >= $current;
            })
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
    $now = Carbon::now();
    $today = $now->copy()->startOfDay();

    $schedules = Schedule::where('instructor_id', Auth::id())
        ->orderBy('day')
        ->orderBy('start_time')
        ->paginate(10);

    foreach ($schedules as $schedule) {
        $schedule->todayOccurrence = null;
        $schedule->displayStatus = 'upcoming';

        if (!$this->scheduleMatchesDate($schedule->day, $today)) {
            continue;
        }

        $occurrence = $this->getTodayOccurrence($schedule, $today);

        $schedule->todayOccurrence = $occurrence;

        if ($occurrence->status === 'in_progress') {
            $schedule->displayStatus = 'in_progress';
            continue;
        }

        if ($occurrence->status === 'completed') {
            $schedule->displayStatus = 'completed';
            continue;
        }

        if ($occurrence->status === 'cancelled') {
            $schedule->displayStatus = 'cancelled';
            continue;
        }

        $startTime = Carbon::parse(
            $today->toDateString() . ' ' . $schedule->start_time
        );

        $endTime = Carbon::parse(
            $today->toDateString() . ' ' . $schedule->end_time
        );

        if ($now->gt($endTime->copy()->addMinutes(5))) {
            $schedule->displayStatus = 'missed';
        } elseif ($now->gte($startTime->copy()->subMinutes(10))) {
            $schedule->displayStatus = 'scheduled';
        } else {
            $schedule->displayStatus = 'upcoming';
        }
    }

    return view('instructor.schedule', compact('schedules'));
}


    public function rooms()
    {
        $today = Carbon::today();

        $rooms = Room::with(['building', 'schedules.instructor'])
            ->orderBy('building_id')
            ->orderBy('room_number')
            ->get();

        foreach ($rooms as $room) {
            $room->occupiedSchedule = $room->schedules
                ->first(function ($schedule) use ($today) {
                    if (!$this->scheduleMatchesDate($schedule->day, $today)) {
                        return false;
                    }

                    $occurrence = $this->getTodayOccurrence(
                        $schedule,
                        $today
                    );

                    return $occurrence
                        && $occurrence->status === 'in_progress';
                });
        }

        return view('instructor.rooms', compact('rooms'));
    }

    public function startClass(Schedule $schedule)
    {
        abort_if(
            $schedule->instructor_id != auth()->id(),
            403
        );

        $now = Carbon::now();

        if (!$this->scheduleMatchesDate($schedule->day, $now)) {
            return back()->with(
                'error',
                'This class is not scheduled for today.'
            );
        }

        $startTime = Carbon::parse(
            $now->toDateString() . ' ' . $schedule->start_time
        );

        $endTime = Carbon::parse(
            $now->toDateString() . ' ' . $schedule->end_time
        );

        $startAllowedAt = $startTime->copy()->subMinutes(10);
        $lastStartTime = $endTime->copy()->addMinutes(5);

        if ($now->lt($startAllowedAt)) {
            return back()->with(
                'error',
                'You can only start your class 10 minutes before its scheduled start time.'
            );
        }

        if ($now->gt($lastStartTime)) {
            return back()->with(
                'error',
                'The scheduled class start window has already expired.'
            );
        }

        $occurrence = $this->getTodayOccurrence($schedule, $now);

        if ($occurrence->status === 'completed') {
            return back()->with(
                'error',
                'This class has already been completed.'
            );
        }

        if ($occurrence->status === 'cancelled') {
            return back()->with(
                'error',
                'This class has been cancelled.'
            );
        }

        if ($occurrence->status === 'in_progress') {
            return back()->with(
                'error',
                'This class is already in progress.'
            );
        }

        $occurrence->update([
            'status' => 'in_progress',
            'started_at' => $now,
            'ended_at' => null,
        ]);

        return back()->with(
            'success',
            'Class started successfully. The room is now marked as occupied.'
        );
    }

    

public function endClass(Schedule $schedule)
{
    abort_if(
        $schedule->instructor_id != auth()->id(),
        403
    );

    $now = Carbon::now();

    $occurrence = $schedule->occurrences()
        ->whereDate('class_date', $now->toDateString())
        ->first();

    // Handle an old class that was started before occurrences existed.
    if ($schedule->status === 'in_progress') {

        if (!$occurrence) {
            $occurrence = $schedule->occurrences()->create([
                'class_date' => $now->toDateString(),
                'status' => 'completed',
                'started_at' => $now,
                'ended_at' => $now,
            ]);
        } else {
            $occurrence->update([
                'status' => 'completed',
                'ended_at' => $now,
            ]);
        }

        // Update the legacy status so the old UI no longer shows In Progress.
        $schedule->update([
            'status' => 'completed',
        ]);

        return back()->with(
            'success',
            'Previous class successfully ended. The room is now available.'
        );
    }

    // Normal occurrence-based class ending.
    if (!$occurrence || $occurrence->status !== 'in_progress') {
        return back()->with(
            'error',
            'There is no class currently in progress to end.'
        );
    }

    $occurrence->update([
        'status' => 'completed',
        'ended_at' => $now,
    ]);

    return back()->with(
        'success',
        'Class ended successfully. The room is now available.'
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

                    $occurrence = $this->getTodayOccurrence(
                        $schedule,
                        $today
                    );

                    return $occurrence
                        && $occurrence->status === 'in_progress';
                });
        }

        return view('instructor.rooms.building', compact('building', 'rooms'));
    }

    public function roomDetails(Room $room)
    {
        $room->load('building');

        $today = Carbon::today();

        $schedule = Schedule::where('room_id', $room->id)
            ->with('instructor')
            ->get()
            ->first(function ($schedule) use ($today) {
                if (!$this->scheduleMatchesDate($schedule->day, $today)) {
                    return false;
                }

                $occurrence = $this->getTodayOccurrence(
                    $schedule,
                    $today
                );

                return $occurrence
                    && $occurrence->status === 'in_progress';
            });

        return view('instructor.rooms.show', compact('room', 'schedule'));
    }

    /**
     * Retrieve or create today's occurrence for a schedule.
     */
    private function getTodayOccurrence(
        Schedule $schedule,
        Carbon $date
    ): ClassOccurrence {
        return $schedule->occurrences()->firstOrCreate(
            [
                'class_date' => $date->toDateString(),
            ],
            [
                'status' => 'scheduled',
            ]
        );
    }

    /**
     * Match imported schedule days such as MTW, ThF, MTWThF and TWThFSSu.
     * Also supports full weekday names if they exist in older records.
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
