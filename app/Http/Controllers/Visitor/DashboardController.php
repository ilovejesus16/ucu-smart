<?php

namespace App\Http\Controllers\Visitor;

use App\Http\Controllers\Controller;
use App\Models\CampusLocation;
use App\Models\Building;
use App\Models\College;
use App\Models\AcademicYear;

class DashboardController extends Controller
{
    public function index()
    {
        // Get the active academic year
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();

        // Get colleges and their saved colors
        $colleges = College::orderBy('college_code')->get();

        // Get campus locations and linked buildings
        $locations = CampusLocation::with('building')
            ->orderBy('number')
            ->get();

        // Get buildings, rooms, and college assignments
        $buildings = Building::with([
            'rooms' => function ($query) use ($activeAcademicYear) {

                $query->orderBy('floor')
                      ->orderBy('room_number');

                $query->with([
                    'collegeAssignments' => function ($assignmentQuery) use ($activeAcademicYear) {

                        if ($activeAcademicYear) {
                            $assignmentQuery->where(
                                'academic_year_id',
                                $activeAcademicYear->id
                            );
                        } else {
                            $assignmentQuery->whereRaw('1 = 0');
                        }

                        $assignmentQuery->with('college');
                    }
                ]);
            }
        ])
        ->orderBy('building_code')
        ->get();

        return view('visitor.dashboard', compact(
            'locations',
            'buildings',
            'activeAcademicYear',
            'colleges'
        ));
    }
}
