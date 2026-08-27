<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Room;
use App\Models\RoomUsage;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Basic Statistics
        |--------------------------------------------------------------------------
        */

        $totalSchedules = Schedule::count();

        $scheduled = Schedule::where(
            'status',
            'scheduled'
        )->count();

        $inProgress = Schedule::where(
            'status',
            'in_progress'
        )->count();

        $completed = Schedule::where(
            'status',
            'completed'
        )->count();

        $cancelled = Schedule::where(
            'status',
            'cancelled'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Campus Statistics
        |--------------------------------------------------------------------------
        */

        $totalRooms = Room::count();

        $totalBuildings = Building::count();

        $totalInstructors = User::where(
            'role',
            'instructor'
        )
            ->where(
                'status',
                'active'
            )
            ->count();

        $subjectCount = Schedule::select(
            'subject_code'
        )
            ->distinct()
            ->count('subject_code');


        /*
        |--------------------------------------------------------------------------
        | Actual Room Usage - Number of Sessions
        |--------------------------------------------------------------------------
        */

        $todayUsage = RoomUsage::whereDate(
            'started_at',
            Carbon::today()
        )->count();

        $weeklyUsage = RoomUsage::whereBetween(
            'started_at',
            [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek(),
            ]
        )->count();

        $monthlyUsage = RoomUsage::whereBetween(
            'started_at',
            [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth(),
            ]
        )->count();

        $yearlyUsage = RoomUsage::whereBetween(
            'started_at',
            [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear(),
            ]
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Actual Room Usage - Hours
        |--------------------------------------------------------------------------
        */

        $todayHours = round(
            RoomUsage::whereDate(
                'started_at',
                Carbon::today()
            )->sum('duration_minutes') / 60,
            1
        );

        $weeklyHours = round(
            RoomUsage::whereBetween(
                'started_at',
                [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek(),
                ]
            )->sum('duration_minutes') / 60,
            1
        );

        $monthlyHours = round(
            RoomUsage::whereBetween(
                'started_at',
                [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth(),
                ]
            )->sum('duration_minutes') / 60,
            1
        );

        $yearlyHours = round(
            RoomUsage::whereBetween(
                'started_at',
                [
                    Carbon::now()->startOfYear(),
                    Carbon::now()->endOfYear(),
                ]
            )->sum('duration_minutes') / 60,
            1
        );


        /*
        |--------------------------------------------------------------------------
        | Most Used Rooms
        |--------------------------------------------------------------------------
        */

        $roomSort = $request->get('room_sort', 'most');

$roomUsageQuery = RoomUsage::selectRaw(
    'room_id, COUNT(*) as total_usage, SUM(duration_minutes) as total_minutes'
)
    ->with('room.building')
    ->groupBy('room_id');

if ($roomSort === 'least') {
    $roomUsageQuery->orderBy('total_usage', 'asc');
} else {
    $roomUsageQuery->orderBy('total_usage', 'desc');
}

$roomUsage = $roomUsageQuery
    ->limit(10)
    ->get();


        /*
        |--------------------------------------------------------------------------
        | Usage By Building
        |--------------------------------------------------------------------------
        */

     $buildingSort = $request->get('building_sort', 'most');

$buildingUsageQuery = RoomUsage::selectRaw(
    'rooms.building_id,
     COUNT(room_usages.id) as total_usage,
     SUM(room_usages.duration_minutes) as total_minutes'
)
    ->join(
        'rooms',
        'room_usages.room_id',
        '=',
        'rooms.id'
    )
    ->with('room.building')
    ->groupBy('rooms.building_id');

if ($buildingSort === 'least') {
    $buildingUsageQuery->orderBy('total_usage', 'asc');
} else {
    $buildingUsageQuery->orderBy('total_usage', 'desc');
}

$buildingUsage = $buildingUsageQuery->get();

        /*
        |--------------------------------------------------------------------------
        | Usage By Day - Current Week
        |--------------------------------------------------------------------------
        */

        $usageByDay = RoomUsage::selectRaw(
            'DAYOFWEEK(started_at) as day_number,
             COUNT(*) as total_usage,
             SUM(duration_minutes) as total_minutes'
        )
            ->whereBetween(
                'started_at',
                [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek(),
                ]
            )
            ->groupBy('day_number')
            ->orderBy('day_number')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Usage By Month - Current Year
        |--------------------------------------------------------------------------
        */

        $usageByMonth = RoomUsage::selectRaw(
            'MONTH(started_at) as month_number,
             COUNT(*) as total_usage,
             SUM(duration_minutes) as total_minutes'
        )
            ->whereYear(
                'started_at',
                Carbon::now()->year
            )
            ->groupBy('month_number')
            ->orderBy('month_number')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Classes By Day
        |--------------------------------------------------------------------------
        */

        $classesByDay = Schedule::selectRaw(
            'day, COUNT(*) as total'
        )
            ->groupBy('day')
            ->orderByRaw(
                "
                CASE day
                    WHEN 'Monday' THEN 1
                    WHEN 'Tuesday' THEN 2
                    WHEN 'Wednesday' THEN 3
                    WHEN 'Thursday' THEN 4
                    WHEN 'Friday' THEN 5
                    WHEN 'Saturday' THEN 6
                    WHEN 'Sunday' THEN 7
                    ELSE 8
                END
                "
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Instructor Load
        |--------------------------------------------------------------------------
        */

        $instructorLoad = Schedule::selectRaw(
            'instructor_id, COUNT(*) as total'
        )
            ->with('instructor')
            ->groupBy('instructor_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Return Reports View
        |--------------------------------------------------------------------------
        */

        return view(
    'admin.reports.index',
    compact(
        'totalSchedules',
        'scheduled',
        'inProgress',
        'completed',
        'cancelled',

        'totalRooms',
        'totalBuildings',
        'totalInstructors',
        'subjectCount',

        'todayUsage',
        'weeklyUsage',
        'monthlyUsage',
        'yearlyUsage',

        'todayHours',
        'weeklyHours',
        'monthlyHours',
        'yearlyHours',

        'roomUsage',
        'buildingUsage',
        'usageByDay',
        'usageByMonth',
        'classesByDay',
        'instructorLoad',

        'roomSort',
        'buildingSort'
    )
);
    }
}