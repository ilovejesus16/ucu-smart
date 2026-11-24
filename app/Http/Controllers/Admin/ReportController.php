<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Building;
use App\Models\Room;
use App\Models\RoomUsage;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\ClassOccurrence;


class ReportController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Basic Statistics
        |--------------------------------------------------------------------------
        */

       $totalSchedules = ClassOccurrence::count();

$scheduled = ClassOccurrence::where('status', 'scheduled')->count();

$inProgress = ClassOccurrence::where('status', 'in_progress')->count();

$completed = ClassOccurrence::where('status', 'completed')->count();

$cancelled = ClassOccurrence::where('status', 'cancelled')->count();

        /*
        |--------------------------------------------------------------------------
        | Campus Statistics
        |--------------------------------------------------------------------------
        */

        $totalRooms = Room::count();

        $totalBuildings = Building::count();

        $totalInstructors = User::where('role', 'instructor')
            ->where('status', 'active')
            ->count();

        $subjectCount = Schedule::select('subject_code')
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

$roomUsageQuery = Room::query()
    ->with('building')
    ->withCount([
        'usages as total_usage'
    ])
    ->withSum(
        'usages as total_minutes',
        'duration_minutes'
    );

if ($roomSort === 'least') {
    $roomUsageQuery->orderBy('total_usage', 'asc');
} else {
    $roomUsageQuery->orderBy('total_usage', 'desc');
}

$roomUsage = $roomUsageQuery
    ->orderBy('room_number')
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
        | Activity Logs / Audit Trail
        |--------------------------------------------------------------------------
        */

        // Mark the Activity Log as read for this administrator only.
        // Each admin has an independent last-viewed timestamp.
        $admin = $request->user();
        $admin->forceFill([
            'activity_logs_viewed_at' => now(),
        ])->save();

        $activityLogs = ActivityLog::with(['user', 'subject'])
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        // Only logs tied to an existing record receive a destination.
        // Deleted records and aggregate/bulk actions remain non-clickable.
        foreach ($activityLogs as $log) {
            $subject = $log->subject;

            if (!$subject) {
                $log->activity_url = null;
                continue;
            }

            $subjectType = class_basename($subject);

            $log->activity_url = match ($subjectType) {
                'Building' => route('buildings.index', [
                    'highlight_building' => $subject->getKey(),
                ]),
                'Room' => route('admin.rooms.index', [
                    'highlight_room' => $subject->getKey(),
                ]),
                'User' => route('admin.users', [
                    'highlight_user' => $subject->getKey(),
                ]),
                'CampusLocation' => route('admin.campus-map', [
                    'highlight_location' => $subject->getKey(),
                ]),
                default => null,
            };
        }


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
                'buildingSort',

                'activityLogs'
            )
        );
    }
}
