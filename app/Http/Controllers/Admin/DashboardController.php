<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::now()->format('l');

        $students = User::where('role', 'student')->count();
        $instructors = User::where('role', 'instructor')->count();
        $admins = User::where('role', 'admin')->count();

        $pendingUsers = User::where('status', 'pending')->count();

        $buildings = Building::count();
        $rooms = Room::count();

        $todayClasses = Schedule::where('day', $today)->count();

        $occupiedRooms = Schedule::where('day', $today)
            ->where('status', 'in_progress')
            ->count();

        $availableRooms = max($rooms - $occupiedRooms, 0);

        $todaySchedules = Schedule::with(['room','instructor'])
            ->where('day',$today)
            ->orderBy('start_time')
            ->take(5)
            ->get();

        $pendingList = User::where('status','pending')
            ->latest()
            ->take(5)
            ->get();

        $recentUsers = User::latest()
            ->take(5)
            ->get();

        $semester = Schedule::latest()->value('semester');
        $schoolYear = Schedule::latest()->value('school_year');

        return view('admin.dashboard', compact(
            'students',
            'instructors',
            'admins',
            'pendingUsers',
            'buildings',
            'rooms',
            'todayClasses',
            'availableRooms',
            'todaySchedules',
            'pendingList',
            'recentUsers',
            'semester',
            'schoolYear'
        ));
    }
}