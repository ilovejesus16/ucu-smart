<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\ClassOccurrence;
use App\Models\RoomMonitoring;
use App\Models\RoomMonitoringHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RoomMonitoringController extends Controller
{
    /**
     * Display all buildings for HR room monitoring.
     */
    public function index()
    {
        $buildings = Building::withCount('rooms')
            ->orderBy('building_name')
            ->get();

        return view('admin.room-monitoring.index', compact('buildings'));
    }

    /**
     * Display all rooms belonging to a selected building.
     */
    public function buildingRooms(Building $building)
    {
        $rooms = $building->rooms()
            ->orderBy('room_number')
            ->get();

        return view(
            'admin.room-monitoring.building',
            compact('building', 'rooms')
        );
    }

    /**
     * Display scheduled classes and attendance records for a room.
     */
    public function roomDetails(Request $request, Room $room)
    {
        $validated = $request->validate([
            'date' => ['nullable', 'date', 'before_or_equal:today'],
        ]);

        $selectedDate = Carbon::parse(
            $validated['date'] ?? now()->toDateString()
        );

        $date = $selectedDate->toDateString();

        // Get all schedules assigned to this room on the selected date.
        $schedules = Schedule::with('instructor')
            ->where('room_id', $room->id)
            ->get()
            ->filter(function ($schedule) use ($selectedDate) {
                return $this->scheduleMatchesDate(
                    $schedule->day,
                    $selectedDate
                );
            })
            ->sortBy('start_time')
            ->values();

        // Retrieve occurrence records for the selected date.
        $occurrences = ClassOccurrence::whereIn(
            'schedule_id',
            $schedules->pluck('id')
        )
            ->whereDate('class_date', $date)
            ->get()
            ->keyBy('schedule_id');

        // Attach class occurrence and display status to each schedule.
        foreach ($schedules as $schedule) {
            $occurrence = $occurrences->get($schedule->id);

            $schedule->classOccurrence = $occurrence;

            $schedule->classDisplayStatus = $this->getClassDisplayStatus(
                $schedule,
                $occurrence,
                $selectedDate
            );
        }

        // Get attendance records for the selected date.
        $monitorings = RoomMonitoring::with([
            'instructor',
            'verifier',
            'histories.changedBy',
        ])
            ->where('room_id', $room->id)
            ->whereDate('monitoring_date', $date)
            ->get()
            ->keyBy('schedule_id');

        // Get dates that already have monitoring records.
        $recordedDates = RoomMonitoring::where('room_id', $room->id)
            ->select('monitoring_date')
            ->distinct()
            ->orderByDesc('monitoring_date')
            ->pluck('monitoring_date');

        $room->load('building');

        return view('admin.room-monitoring.show', compact(
            'room',
            'schedules',
            'monitorings',
            'selectedDate',
            'date',
            'recordedDates'
        ));
    }

    /**
     * Determine the class status displayed to HR.
     */
    private function getClassDisplayStatus(
        Schedule $schedule,
        ?ClassOccurrence $occurrence,
        Carbon $selectedDate
    ): string {
        // No occurrence record exists.
        if (!$occurrence) {
            // Do not invent historical attendance or class activity.
            if (!$selectedDate->isToday()) {
                return 'no_record';
            }

            return $this->getTimeBasedStatus($schedule, $selectedDate);
        }

        // Use the actual occurrence status when available.
        if ($occurrence->status === 'in_progress') {
            return 'in_progress';
        }

        if ($occurrence->status === 'completed') {
            return 'completed';
        }

        if ($occurrence->status === 'cancelled') {
            return 'cancelled';
        }

        // A scheduled occurrence may become Missed after its allowed window.
        return $this->getTimeBasedStatus($schedule, $selectedDate);
    }

    /**
     * Determine Upcoming, Scheduled, or Missed from the class time.
     */
    private function getTimeBasedStatus(
        Schedule $schedule,
        Carbon $selectedDate
    ): string {
        $now = Carbon::now();

        $startTime = Carbon::parse(
            $selectedDate->toDateString() . ' ' . $schedule->start_time
        );

        $endTime = Carbon::parse(
            $selectedDate->toDateString() . ' ' . $schedule->end_time
        );

        // Past classes that were never started.
        if ($now->greaterThan($endTime->copy()->addMinutes(5))) {
            return 'missed';
        }

        // Class can be started 10 minutes before its scheduled start.
        if ($now->greaterThanOrEqualTo(
            $startTime->copy()->subMinutes(10)
        )) {
            return 'scheduled';
        }

        return 'upcoming';
    }

    /**
     * Record or update attendance for a scheduled class.
     */
    public function storeAttendance(Request $request, Room $room)
    {
        $validated = $request->validate([
            'schedule_id' => [
                'required',
                'integer',
                'exists:schedules,id',
            ],
            'date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
            'attendance_status' => [
                'required',
                'in:present,absent',
            ],
            'remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $selectedDate = Carbon::parse($validated['date']);

        // Ensure the schedule belongs to the selected room.
        $schedule = Schedule::where('room_id', $room->id)
            ->findOrFail($validated['schedule_id']);

        // Ensure the schedule actually takes place on this date.
        if (! $this->scheduleMatchesDate($schedule->day, $selectedDate)) {
            throw ValidationException::withMessages([
                'schedule_id' => 'This class is not scheduled for the selected date.',
            ]);
        }

        DB::transaction(function () use (
            $validated,
            $schedule,
            $room,
            $selectedDate
        ) {
            $monitoring = RoomMonitoring::where(
                'schedule_id',
                $schedule->id
            )
                ->whereDate(
                    'monitoring_date',
                    $selectedDate->toDateString()
                )
                ->first();

            $newStatus = $validated['attendance_status'];
            $newRemarks = $validated['remarks'] ?? null;

            // Create a new attendance record.
            if (! $monitoring) {
                RoomMonitoring::create([
                    'schedule_id' => $schedule->id,
                    'room_id' => $room->id,
                    'instructor_id' => $schedule->instructor_id,
                    'verified_by' => auth()->id(),
                    'monitoring_date' => $selectedDate->toDateString(),
                    'attendance_status' => $newStatus,
                    'remarks' => $newRemarks,
                    'verified_at' => now(),
                ]);

                return;
            }

            // Save an audit entry if an existing record is changed.
            if (
                $monitoring->attendance_status !== $newStatus ||
                $monitoring->remarks !== $newRemarks
            ) {
                RoomMonitoringHistory::create([
                    'room_monitoring_id' => $monitoring->id,
                    'changed_by' => auth()->id(),
                    'old_status' => $monitoring->attendance_status,
                    'new_status' => $newStatus,
                    'old_remarks' => $monitoring->remarks,
                    'new_remarks' => $newRemarks,
                    'changed_at' => now(),
                ]);
            }

            // Update the attendance record and latest verifier.
            $monitoring->update([
                'verified_by' => auth()->id(),
                'attendance_status' => $newStatus,
                'remarks' => $newRemarks,
                'verified_at' => now(),
            ]);
        });

        return redirect()
            ->route('admin.room-monitoring.rooms.show', [
                'room' => $room->id,
                'date' => $selectedDate->toDateString(),
            ])
            ->with('success', 'Attendance has been saved successfully.');
    }

    /**
     * Display attendance records and their change history for a room.
     */
    public function history(Room $room)
    {
        $room->load('building');

        $monitorings = RoomMonitoring::with([
            'schedule',
            'instructor',
            'verifier',
            'histories.changedBy',
        ])
            ->where('room_id', $room->id)
            ->orderByDesc('monitoring_date')
            ->orderByDesc('verified_at')
            ->get();

        return view('admin.room-monitoring.history', compact(
            'room',
            'monitorings'
        ));
    }

    /**
     * Check whether a stored schedule day matches a selected date.
     */
    private function scheduleMatchesDate(
        ?string $scheduleDays,
        Carbon $date
    ): bool {
        if (! $scheduleDays) {
            return false;
        }

        $dayTokens = $this->extractDayTokens($scheduleDays);

        $selectedDay = match ($date->dayOfWeekIso) {
            1 => 'M',
            2 => 'T',
            3 => 'W',
            4 => 'Th',
            5 => 'F',
            6 => 'S',
            7 => 'Su',
        };

        return in_array(
            strtolower($selectedDay),
            array_map('strtolower', $dayTokens),
            true
        );
    }

    /**
     * Convert concatenated schedule days into individual day tokens.
     */
    private function extractDayTokens(string $days): array
    {
        $days = preg_replace('/[\s,\/-]+/', '', $days);

        preg_match_all('/Th|Su|[MTWFS]/i', $days, $matches);

        return $matches[0] ?? [];
    }
}
