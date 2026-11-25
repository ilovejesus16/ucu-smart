<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Room;
use App\Models\ClassOccurrence;
use Carbon\Carbon;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $totalBuildings = Building::count();
        $totalRooms = Room::count();

        // Get rooms that currently have an ongoing class.
        $occupiedRoomIds = ClassOccurrence::whereDate(
            'class_date',
            $today->toDateString()
        )
            ->where('status', 'in_progress')
            ->whereHas('schedule', function ($query) use ($today) {
                $query->whereHas('room');
            })
            ->with('schedule:id,room_id')
            ->get()
            ->pluck('schedule.room_id')
            ->filter()
            ->unique();

        $occupiedRooms = $occupiedRoomIds->count();

        $availableRooms = max(
            0,
            $totalRooms - $occupiedRooms
        );

        return response()->json([
            'dashboard' => [
                'total_buildings' => $totalBuildings,
                'total_rooms' => $totalRooms,
                'availability' => [
                    'available_rooms' => $availableRooms,
                    'occupied_rooms' => $occupiedRooms,
                    'unavailable_rooms' => 0,
                ],
            ],
        ]);
    }
}
