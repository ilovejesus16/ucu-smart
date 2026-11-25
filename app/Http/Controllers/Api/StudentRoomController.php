<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Room;
use Carbon\Carbon;

class StudentRoomController extends Controller
{
    public function buildings()
    {
        $buildings = Building::withCount('rooms')
            ->orderBy('building_name')
            ->get()
            ->map(function ($building) {
                return [
                    'id' => $building->id,
                    'code' => $building->building_code,
                    'name' => $building->building_name,
                    'rooms_count' => $building->rooms_count,
                    'description' => $building->description,
                    'image' => $building->image,
                ];
            });

        return response()->json([
            'buildings' => $buildings,
        ]);
    }

    public function buildingRooms(Building $building)
    {
        $today = Carbon::today();

        $rooms = $building->rooms()
            ->with('schedules.instructor')
            ->orderBy('floor')
            ->orderBy('room_number')
            ->get()
            ->map(function ($room) use ($today, $building) {

                $occupiedSchedule = $room->schedules->first(
                    function ($schedule) use ($today) {

                        if (!$this->scheduleMatchesDate(
                            $schedule->day,
                            $today
                        )) {
                            return false;
                        }

                        return $schedule->occurrences()
                            ->whereDate(
                                'class_date',
                                $today->toDateString()
                            )
                            ->where('status', 'in_progress')
                            ->exists();
                    }
                );

                return [
                    'id' => $room->id,
                    'room_number' => $room->room_number,
                    'room_name' => $room->room_name,
                    'floor' => $room->floor,
                    'capacity' => $room->capacity,

                    'building' => [
                        'id' => $building->id,
                        'code' => $building->building_code,
                        'name' => $building->building_name,
                    ],

                    'availability' => [
                        'status' => $occupiedSchedule
                            ? 'occupied'
                            : 'available',

                        'current_schedule' => $occupiedSchedule
                            ? [
                                'subject_code' =>
                                    $occupiedSchedule->subject_code,
                                'subject_name' =>
                                    $occupiedSchedule->subject_name,
                                'day' => $occupiedSchedule->day,
                                'start_time' =>
                                    $occupiedSchedule->start_time,
                                'end_time' =>
                                    $occupiedSchedule->end_time,
                                'instructor_name' => $occupiedSchedule->instructor
                                    ? trim(
                                        $occupiedSchedule->instructor->first_name
                                        . ' ' .
                                        $occupiedSchedule->instructor->last_name
                                    )
                                    : null,
                            ]
                            : null,
                    ],
                ];
            });

        return response()->json([
            'rooms' => $rooms,
        ]);
    }

    public function roomDetails(Room $room)
    {
        $room->load('building', 'schedules.instructor');

        $today = Carbon::today();

        $occupiedSchedule = $room->schedules->first(
            function ($schedule) use ($today) {

                if (!$this->scheduleMatchesDate(
                    $schedule->day,
                    $today
                )) {
                    return false;
                }

                return $schedule->occurrences()
                    ->whereDate(
                        'class_date',
                        $today->toDateString()
                    )
                    ->where('status', 'in_progress')
                    ->exists();
            }
        );

        return response()->json([
            'id' => $room->id,
            'room_number' => $room->room_number,
            'room_name' => $room->room_name,
            'floor' => $room->floor,
            'capacity' => $room->capacity,

            'building' => [
                'id' => $room->building->id,
                'code' => $room->building->building_code,
                'name' => $room->building->building_name,
            ],

            'availability' => [
                'status' => $occupiedSchedule
                    ? 'occupied'
                    : 'available',

                'current_schedule' => $occupiedSchedule
                    ? [
                        'subject_code' => $occupiedSchedule->subject_code,
                        'subject_name' => $occupiedSchedule->subject_name,
                        'day' => $occupiedSchedule->day,
                        'start_time' => $occupiedSchedule->start_time,
                        'end_time' => $occupiedSchedule->end_time,
                        'instructor_name' => $occupiedSchedule->instructor
                            ? trim(
                                $occupiedSchedule->instructor->first_name
                                . ' ' .
                                $occupiedSchedule->instructor->last_name
                            )
                            : null,
                    ]
                    : null,
            ],
        ]);
    }

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
