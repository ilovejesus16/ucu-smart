<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Facility;
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
        $now = Carbon::now();
        $today = $now->copy()->startOfDay();

        $rooms = $building->rooms()
            ->with([
                'schedules.instructor',
                'schedules.occurrences',
            ])
            ->orderBy('floor')
            ->orderBy('room_number')
            ->get();

        foreach ($rooms as $room) {

            // Default classroom availability.
            $room->occupiedSchedule = $room->schedules
                ->first(function ($schedule) use ($today) {

                    if (!$this->scheduleMatchesDate($schedule->day, $today)) {
                        return false;
                    }

                    $occurrence = $schedule->occurrences
                        ->first(function ($occurrence) use ($today) {
                            return $occurrence->class_date
                                && $occurrence->class_date->toDateString()
                                    === $today->toDateString();
                        });

                    return $occurrence
                        && $occurrence->status === 'in_progress';
                });

            // Default status for ordinary classrooms.
            $room->availabilityStatus = $room->occupiedSchedule
                ? 'occupied'
                : 'available';

            $room->currentReservation = null;
            $room->facilityClasses = collect();
            $room->facilityReservations = collect();

            // Stop here for ordinary classrooms.
            if (!$room->facility_id) {
                continue;
            }

            // Load the facility and its approved reservations.
            $facility = Facility::with([
                'activeReservations',
            ])->find($room->facility_id);

            $room->facility = $facility;

            if (!$facility) {
                continue;
            }

            if (!$facility->is_active) {
                $room->availabilityStatus = 'inactive';
                continue;
            }

            $approvedReservations = $facility->activeReservations()
                ->whereDate('reservation_date', '>=', $today->toDateString())
                ->orderBy('reservation_date')
                ->orderBy('start_time')
                ->get();

            $room->facilityReservations = $approvedReservations;

            // Find an event currently taking place.
            $currentReservation = $approvedReservations->first(
                function ($reservation) use ($now) {

                    return $reservation->reservation_date->toDateString()
                        === $now->toDateString()
                        && $reservation->start_time <= $now->format('H:i:s')
                        && $reservation->end_time > $now->format('H:i:s');
                }
            );

            // Find all facility classes scheduled for today.
            $todayClasses = $room->schedules->filter(
                function ($schedule) use ($today, $now) {

                    if (!$this->scheduleMatchesDate($schedule->day, $today)) {
                        return false;
                    }

                    return (string) $schedule->end_time > $now->format('H:i:s');
                }
            )->values();

            $room->facilityClasses = $todayClasses;

            // A class is currently occupying the facility
            // only when its occurrence is marked in_progress.
            $currentClass = $todayClasses->first(
                function ($schedule) use ($today) {

                    return $schedule->occurrences->contains(
                        function ($occurrence) use ($today) {

                            return $occurrence->class_date
                                && $occurrence->class_date->toDateString()
                                    === $today->toDateString()
                                && $occurrence->status === 'in_progress';
                        }
                    );
                }
            );

            $room->occupiedSchedule = $currentClass;
            $room->currentReservation = $currentReservation;

            if ($currentReservation || $currentClass) {
                $room->availabilityStatus = 'occupied';
            } elseif (
                $todayClasses->isNotEmpty()
                || $approvedReservations->isNotEmpty()
            ) {
                $room->availabilityStatus = 'reserved';
            } else {
                $room->availabilityStatus = 'available';
            }
        }

        return view('student.rooms.building', compact(
            'building',
            'rooms'
        ));
    }

   public function roomDetails(Room $room)
{
    $now = Carbon::now();
    $today = $now->copy()->startOfDay();

    $room->load(['building', 'facility']);

    // Keep the existing classroom behavior unchanged.
    if (!$room->facility_id || !$room->facility) {
        $schedules = Schedule::where('room_id', $room->id)
            ->with('instructor', 'occurrences')
            ->get();

        $schedule = $schedules->first(function ($schedule) use ($today) {
            if (!$this->scheduleMatchesDate($schedule->day, $today)) {
                return false;
            }

            $occurrence = $schedule->occurrences->first(function ($occurrence) use ($today) {
                return $occurrence->class_date
                    && $occurrence->class_date->toDateString() === $today->toDateString();
            });

            return $occurrence && $occurrence->status === 'in_progress';
        });

        return view('student.rooms.show', compact('room', 'schedule'));
    }

    // Facility room: load all approved upcoming event reservations.
    $facility = $room->facility;

    $facilityReservations = $facility->activeReservations()
        ->whereDate('reservation_date', '>=', $today->toDateString())
        ->orderBy('reservation_date')
        ->orderBy('start_time')
        ->get();

    $currentReservation = $facilityReservations->first(function ($reservation) use ($now) {
        return $reservation->reservation_date->toDateString() === $now->toDateString()
            && $reservation->start_time <= $now->format('H:i:s')
            && $reservation->end_time > $now->format('H:i:s');
    });

    // A facility may have multiple simultaneous PE classes.
    $facilityClasses = Schedule::where('room_id', $room->id)
        ->with('instructor', 'occurrences')
        ->get()
        ->filter(function ($schedule) use ($today, $now) {
            return $this->scheduleMatchesDate($schedule->day, $today)
                && (string) $schedule->end_time > $now->format('H:i:s');
        })
        ->values();

    $currentFacilityClasses = $facilityClasses->filter(function ($schedule) use ($today) {
        return $schedule->occurrences->contains(function ($occurrence) use ($today) {
            return $occurrence->class_date
                && $occurrence->class_date->toDateString() === $today->toDateString()
                && $occurrence->status === 'in_progress';
        });
    })->values();

    if ($currentReservation || $currentFacilityClasses->isNotEmpty()) {
        $availabilityStatus = 'occupied';
    } elseif ($facilityClasses->isNotEmpty() || $facilityReservations->isNotEmpty()) {
        $availabilityStatus = 'reserved';
    } elseif (!$facility->is_active) {
        $availabilityStatus = 'inactive';
    } else {
        $availabilityStatus = 'available';
    }

    return view('student.rooms.show', [
        'room' => $room,
        'schedule' => null,
        'facility' => $facility,
        'facilityReservations' => $facilityReservations,
        'currentReservation' => $currentReservation,
        'facilityClasses' => $facilityClasses,
        'currentFacilityClasses' => $currentFacilityClasses,
        'availabilityStatus' => $availabilityStatus,
        'now' => $now,
    ]);
}
}
