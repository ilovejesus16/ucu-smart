<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\ClassOccurrence;
use Illuminate\Support\Facades\Auth;
use App\Models\Room;
use Carbon\Carbon;
use App\Models\Building;
use App\Models\RoomUsage;
use Illuminate\Support\Facades\DB;
use App\Models\College;
use App\Models\AcademicYear;

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
    abort_if($schedule->instructor_id != auth()->id(), 403);

    $now = Carbon::now();

    if (!$this->scheduleMatchesDate($schedule->day, $now)) {
        return back()->with('error', 'This class is not scheduled for today.');
    }

    $startTime = Carbon::parse(
        $now->toDateString() . ' ' . $schedule->start_time
    );

    $endTime = Carbon::parse(
        $now->toDateString() . ' ' . $schedule->end_time
    );

    if ($now->lt($startTime->copy()->subMinutes(10))) {
        return back()->with(
            'error',
            'You can only start your class 10 minutes before its scheduled start time.'
        );
    }

    if ($now->gt($endTime->copy()->addMinutes(5))) {
        return back()->with(
            'error',
            'The scheduled class start window has already expired.'
        );
    }

    $occurrence = $this->getTodayOccurrence($schedule, $now);

    if ($occurrence->status === 'completed') {
        return back()->with('error', 'This class has already been completed.');
    }

    if ($occurrence->status === 'cancelled') {
        return back()->with('error', 'This class has been cancelled.');
    }

    if ($occurrence->status === 'in_progress') {
        return back()->with('error', 'This class is already in progress.');
    }

    $started = DB::transaction(function () use ($occurrence, $schedule, $now) {
        $lockedOccurrence = ClassOccurrence::whereKey($occurrence->id)
            ->lockForUpdate()
            ->firstOrFail();

        if ($lockedOccurrence->status !== 'scheduled') {
            return false;
        }

        $activeUsage = RoomUsage::where('schedule_id', $schedule->id)
            ->whereNull('ended_at')
            ->exists();

        if ($activeUsage) {
            return false;
        }

        $lockedOccurrence->update([
            'status' => 'in_progress',
            'started_at' => $now,
            'ended_at' => null,
        ]);

        RoomUsage::create([
            'room_id' => $schedule->room_id,
            'schedule_id' => $schedule->id,
            'instructor_id' => auth()->id(),
            'started_at' => $now,
            'ended_at' => null,
            'duration_minutes' => null,
        ]);

        return true;
    });

    if (!$started) {
        return back()->with(
            'error',
            'This class already has an active usage record or has changed status. Please refresh.'
        );
    }

    return back()->with(
        'success',
        'Class started successfully. The room is now marked as occupied.'
    );
}

public function endClass(Schedule $schedule)
{
    abort_if($schedule->instructor_id != auth()->id(), 403);

    $now = Carbon::now();

    $occurrence = $schedule->occurrences()
        ->whereDate('class_date', $now->toDateString())
        ->first();

    // Legacy fallback: do not invent a historical start time.
    if (!$occurrence && $schedule->status === 'in_progress') {
        $schedule->update(['status' => 'completed']);

        return back()->with(
            'success',
            'Previous class status updated. No usage duration was recorded because its actual start time was unavailable.'
        );
    }

    if (!$occurrence || $occurrence->status !== 'in_progress') {
        return back()->with(
            'error',
            'There is no class currently in progress to end.'
        );
    }

    $ended = DB::transaction(function () use ($occurrence, $schedule, $now) {
        $lockedOccurrence = ClassOccurrence::whereKey($occurrence->id)
            ->lockForUpdate()
            ->firstOrFail();

        if ($lockedOccurrence->status !== 'in_progress') {
            return false;
        }

        $usage = RoomUsage::where('schedule_id', $schedule->id)
            ->where('instructor_id', auth()->id())
            ->whereNull('ended_at')
            ->orderByDesc('started_at')
            ->lockForUpdate()
            ->first();

        if ($usage) {
            $startedAt = $usage->started_at;

            $usage->update([
                'ended_at' => $now,
                'duration_minutes' => max(
                    0,
                    (int) $startedAt->diffInMinutes($now)
                ),
            ]);
        }

        $lockedOccurrence->update([
            'status' => 'completed',
            'ended_at' => $now,
        ]);

        return true;
    });

    if (!$ended) {
        return back()->with(
            'error',
            'This class has already ended. Please refresh the page.'
        );
    }

    return back()->with(
        'success',
        'Class ended successfully. The room is now available.'
    );
}



    

public function buildings()
{
    $instructorId = Auth::id();

    $buildings = Building::whereHas('rooms', function ($query) use ($instructorId) {
            $query->whereHas('schedules', function ($scheduleQuery) use ($instructorId) {
                $scheduleQuery->where('instructor_id', $instructorId);
            });
        })
        ->withCount([
            'rooms' => function ($query) use ($instructorId) {
                $query->whereHas('schedules', function ($scheduleQuery) use ($instructorId) {
                    $scheduleQuery->where('instructor_id', $instructorId);
                });
            }
        ])
        ->orderBy('building_name')
        ->get();

    return view('instructor.rooms.index', compact('buildings'));
}


public function buildingRooms(Building $building)
{
    $instructorId = Auth::id();

    // Only allow buildings containing rooms assigned to this instructor.
    abort_unless(
        $building->rooms()
            ->whereHas('schedules', function ($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })
            ->exists(),
        404
    );

    $today = Carbon::today();

    $rooms = $building->rooms()
        ->whereHas('schedules', function ($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })
        ->with([
            'schedules' => function ($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId)
                    ->with('instructor');
            }
        ])
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
    $instructorId = Auth::id();

    // Only allow rooms assigned to this instructor through a schedule.
    abort_unless(
        $room->schedules()
            ->where('instructor_id', $instructorId)
            ->exists(),
        404
    );

    $room->load('building');

    $today = Carbon::today();

    $schedule = Schedule::where('room_id', $room->id)
        ->where('instructor_id', $instructorId)
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
