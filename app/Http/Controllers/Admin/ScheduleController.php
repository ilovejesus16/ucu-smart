<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with([
            'room',
            'instructor'
        ])
        ->orderBy('day')
        ->orderBy('start_time')
        ->paginate(15);

        return view(
            'admin.schedules.index',
            compact('schedules')
        );
    }


    /**
     * Delete one schedule.
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()
            ->route('admin.schedules')
            ->with('success', 'Schedule deleted successfully.');
    }


    /**
     * Delete multiple schedules.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'delete_type' => 'required|in:all,semester,school_year,semester_school_year',

            'semester' => 'nullable|required_if:delete_type,semester,semester_school_year|in:1st Semester,2nd Semester',

            'school_year' => 'nullable|required_if:delete_type,school_year,semester_school_year|string|max:20',
        ]);


        $query = Schedule::query();


        /*
        |--------------------------------------------------------------------------
        | DELETE ALL
        |--------------------------------------------------------------------------
        */

        if ($request->delete_type === 'all') {

            $count = $query->count();

            $query->delete();

            return redirect()
                ->route('admin.schedules')
                ->with(
                    'success',
                    "{$count} schedule(s) deleted successfully."
                );
        }


        /*
        |--------------------------------------------------------------------------
        | DELETE BY SEMESTER
        |--------------------------------------------------------------------------
        */

        if ($request->delete_type === 'semester') {

            $query->where(
                'semester',
                $request->semester
            );

        }


        /*
        |--------------------------------------------------------------------------
        | DELETE BY SCHOOL YEAR
        |--------------------------------------------------------------------------
        */

        if ($request->delete_type === 'school_year') {

            $query->where(
                'school_year',
                $request->school_year
            );

        }


        /*
        |--------------------------------------------------------------------------
        | DELETE BY SEMESTER + SCHOOL YEAR
        |--------------------------------------------------------------------------
        */

        if ($request->delete_type === 'semester_school_year') {

            $query
                ->where(
                    'semester',
                    $request->semester
                )
                ->where(
                    'school_year',
                    $request->school_year
                );

        }


        $count = $query->count();


        if ($count === 0) {

            return redirect()
                ->route('admin.schedules')
                ->with(
                    'success',
                    'No schedules matched the selected criteria.'
                );
        }


        $query->delete();


        return redirect()
            ->route('admin.schedules')
            ->with(
                'success',
                "{$count} schedule(s) deleted successfully."
            );
    }
}