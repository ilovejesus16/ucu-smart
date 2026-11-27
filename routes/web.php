<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BuildingController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\ScheduleImportController;
use App\Http\Controllers\Admin\RoomMonitoringController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\FacilityReservationController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Instructor\DashboardController as InstructorDashboardController;
use App\Http\Controllers\Admin\ReportController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Admin\CampusMapController;
use App\Models\CampusLocation;
use App\Models\Building;
use App\Models\College;
use App\Models\AcademicYear;



/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});


//Email
Route::get('/verify-email/{id}/{hash}', function (
    Request $request,
    $id,
    $hash
) {

    // Make sure the verification URL is valid
    if (! URL::hasValidSignature($request)) {
        abort(403, 'This verification link is invalid or has expired.');
    }

    // Find the user
    $user = User::findOrFail($id);

    // Make sure the hash matches the user's email
    if (! hash_equals(
        sha1($user->getEmailForVerification()),
        $hash
    )) {
        abort(403, 'This verification link is invalid.');
    }

    // Already verified
    if ($user->hasVerifiedEmail()) {

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Your email address is already verified. You can now log in.'
            );
    }

    // VERIFY THE EMAIL
    $user->markEmailAsVerified();

    return redirect()
        ->route('login')
        ->with(
            'success',
            'Your email has been verified successfully. You can now log in.'
        );

})->name('verification.verify');


/*
|--------------------------------------------------------------------------
| Visitor
|--------------------------------------------------------------------------
*/


Route::get('/visitor', function () {

    $activeAcademicYear = AcademicYear::where('is_active', true)->first();

    $colleges = College::orderBy('college_code')->get();

    $locations = CampusLocation::with('building')
        ->orderBy('number')
        ->get();

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
        'colleges',
        'activeAcademicYear'
    ));

})->name('visitor.dashboard');


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {


/*
|--------------------------------------------------------------------------
| Facility Reservations
|--------------------------------------------------------------------------
*/

// Reservation management
Route::get('/admin/facility-reservations', [FacilityReservationController::class, 'index'])
    ->middleware('admin.permission:facility_reservations,view')
    ->name('admin.facility-reservations.index');

Route::post('/admin/facility-reservations', [FacilityReservationController::class, 'store'])
    ->middleware('admin.permission:facility_reservations,full')
    ->name('admin.facility-reservations.store');

Route::put('/admin/facility-reservations/{reservation}', [FacilityReservationController::class, 'update'])
    ->middleware('admin.permission:facility_reservations,full')
    ->name('admin.facility-reservations.update');

Route::patch('/admin/facility-reservations/{reservation}/cancel', [FacilityReservationController::class, 'cancel'])
    ->middleware('admin.permission:facility_reservations,full')
    ->name('admin.facility-reservations.cancel');

// Facility management
Route::get('/admin/facility-reservations/facilities', [FacilityController::class, 'index'])
    ->middleware('admin.permission:facility_reservations,view')
    ->name('admin.facility-reservations.facilities');

Route::post('/admin/facility-reservations/facilities', [FacilityController::class, 'store'])
    ->middleware('admin.permission:facility_reservations,full')
    ->name('admin.facility-reservations.facilities.store');

Route::put('/admin/facility-reservations/facilities/{facility}', [FacilityController::class, 'update'])
    ->middleware('admin.permission:facility_reservations,full')
    ->name('admin.facility-reservations.facilities.update');

Route::patch('/admin/facility-reservations/facilities/{facility}/toggle-status', [FacilityController::class, 'toggleStatus'])
    ->middleware('admin.permission:facility_reservations,full')
    ->name('admin.facility-reservations.facilities.toggle-status');


    /*
    |--------------------------------------------------------------------------
    | Admin Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
    ->middleware('admin.permission:dashboard,view')
    ->name('admin.dashboard');


   
/*
|--------------------------------------------------------------------------
| User Management
|--------------------------------------------------------------------------
*/

Route::get('/admin/users', [UserController::class, 'index'])
    ->middleware('admin.permission:user_management,view')
    ->name('admin.users');

// User Export
Route::get('/admin/users/export/students', [UserController::class, 'exportStudents'])
    ->middleware('admin.permission:user_management,view')
    ->name('admin.users.export.students');

Route::get('/admin/users/export/instructors', [UserController::class, 'exportInstructors'])
    ->middleware('admin.permission:user_management,view')
    ->name('admin.users.export.instructors');

Route::post('/admin/users', [UserController::class, 'store'])
    ->middleware('admin.permission:user_management,full')
    ->name('admin.users.store');

Route::get('/admin/users/{user}', [UserController::class, 'show'])
    ->middleware('admin.permission:user_management,view')
    ->name('admin.users.show');

Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])
    ->middleware('admin.permission:user_management,view')
    ->name('admin.users.edit');

Route::put('/admin/users/{user}', [UserController::class, 'update'])
    ->middleware('admin.permission:user_management,full')
    ->name('admin.users.update');

Route::patch('/admin/users/{user}/approve', [UserController::class, 'approve'])
    ->middleware('admin.permission:user_management,full')
    ->name('admin.users.approve');

Route::patch('/admin/users/{user}/reject', [UserController::class, 'reject'])
    ->middleware('admin.permission:user_management,full')
    ->name('admin.users.reject');

Route::delete('/admin/users/delete-all', [UserController::class, 'deleteAll'])
    ->middleware('admin.permission:user_management,full')
    ->name('admin.users.deleteAll');

Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])
    ->middleware('admin.permission:user_management,full')
    ->name('admin.users.destroy');


    /*
    |--------------------------------------------------------------------------
    | User Import
    |--------------------------------------------------------------------------
    */

    // Students

    Route::get('/admin/users/import/students', [UserController::class, 'studentImportForm'])
        ->middleware('admin.permission:user_management,view')
        ->name('admin.users.import.students');

    Route::post('/admin/users/import/students', [UserController::class, 'importStudents'])
        ->middleware('admin.permission:user_management,full')
        ->name('admin.users.import.students.store');

    Route::get('/admin/users/import/students/preview', [UserController::class, 'studentPreview'])
        ->middleware('admin.permission:user_management,view')
        ->name('admin.users.student.preview');

    Route::post('/admin/users/import/students/store', [UserController::class, 'storeImportedStudents'])
        ->middleware('admin.permission:user_management,full')
        ->name('admin.users.student.store');


    // Instructors

    Route::get('/admin/users/import/instructors', [UserController::class, 'instructorImportForm'])
        ->middleware('admin.permission:user_management,view')
        ->name('admin.users.import.instructors');

    Route::post('/admin/users/import/instructors', [UserController::class, 'importInstructors'])
        ->middleware('admin.permission:user_management,full')
        ->name('admin.users.import.instructors.store');

    Route::get('/admin/users/import/instructors/preview', [UserController::class, 'instructorPreview'])
        ->middleware('admin.permission:user_management,view')
        ->name('admin.users.instructor.preview');

    Route::post('/admin/users/import/instructors/store', [UserController::class, 'storeImportedInstructors'])
        ->middleware('admin.permission:user_management,full')
        ->name('admin.users.instructor.store');





    /*
    |--------------------------------------------------------------------------
    | User Templates
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/users/template/students', [UserController::class, 'studentTemplate'])
        ->middleware('admin.permission:user_management,view')
        ->name('admin.users.template.students');

    Route::get('/admin/users/template/instructors', [UserController::class, 'instructorTemplate'])
        ->middleware('admin.permission:user_management,view')
        ->name('admin.users.template.instructors');


    /*
    |--------------------------------------------------------------------------
    | Buildings
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/buildings', [BuildingController::class, 'index'])
        ->middleware('admin.permission:buildings,view')
        ->name('buildings.index');

    Route::post('/admin/buildings', [BuildingController::class, 'store'])
        ->middleware('admin.permission:buildings,full')
        ->name('buildings.store');

    Route::delete('/admin/buildings/delete-all', [BuildingController::class, 'deleteAll'])
        ->middleware('admin.permission:buildings,full')
        ->name('buildings.deleteAll');

    /*
    |--------------------------------------------------------------------------
    | Building Import / Export
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/buildings/import', [BuildingController::class, 'importForm'])
        ->middleware('admin.permission:buildings,view')
        ->name('buildings.import');

    Route::post('/admin/buildings/import', [BuildingController::class, 'importBuildings'])
        ->middleware('admin.permission:buildings,full')
        ->name('buildings.import.store');

    Route::get('/admin/buildings/import/preview', [BuildingController::class, 'preview'])
        ->middleware('admin.permission:buildings,view')
        ->name('buildings.preview');

    Route::post('/admin/buildings/import/store', [BuildingController::class, 'storeImportedBuildings'])
        ->middleware('admin.permission:buildings,full')
        ->name('buildings.store.import');

    Route::get('/admin/buildings/template', [BuildingController::class, 'template'])
        ->middleware('admin.permission:buildings,view')
        ->name('buildings.template');

    Route::get('/admin/buildings/export', [BuildingController::class, 'export'])
        ->middleware('admin.permission:buildings,view')
        ->name('buildings.export');

    Route::get('/admin/buildings/{building}', [BuildingController::class, 'show'])
        ->middleware('admin.permission:buildings,view')
        ->name('buildings.show');

    Route::get('/admin/buildings/{building}/edit', [BuildingController::class, 'edit'])
    ->middleware('admin.permission:buildings,view')
    ->name('buildings.edit');  

    Route::put('/admin/buildings/{building}', [BuildingController::class, 'update'])
        ->middleware('admin.permission:buildings,full')
        ->name('buildings.update');

    Route::delete('/admin/buildings/{building}', [BuildingController::class, 'destroy'])
        ->middleware('admin.permission:buildings,full')
        ->name('buildings.destroy');


    /*
    |--------------------------------------------------------------------------
    | Campusmap
    |--------------------------------------------------------------------------
    */

    Route::get('/campus-map', [CampusMapController::class, 'index'])
        ->middleware('admin.permission:campus_map,view')
        ->name('admin.campus-map');

    Route::post('/campus-map/positions', [CampusMapController::class, 'savePositions'])
        ->middleware('admin.permission:campus_map,full')
        ->name('admin.campus-map.positions');

    Route::post('/campus-map/reset-positions', [CampusMapController::class, 'resetPositions'])
        ->middleware('admin.permission:campus_map,full')
        ->name('admin.campus-map.reset');
    
    Route::post(
    '/campus-map/locations/{location}/remove-position',
    [\App\Http\Controllers\Admin\CampusMapController::class, 'removePosition']
)
        ->middleware('admin.permission:campus_map,full')
        ->name('admin.campus-map.remove-position');

Route::patch(
    '/campus-map/buildings/{building}',
    [CampusMapController::class, 'updateBuilding']
)
        ->middleware('admin.permission:campus_map,full')
        ->name('admin.campus-map.buildings.update');

Route::patch(
    '/campus-map/locations/{location}/link-building',
    [CampusMapController::class, 'linkBuilding']
)
        ->middleware('admin.permission:campus_map,full')
        ->name('admin.campus-map.locations.link-building');

Route::patch(
    '/campus-map/locations/{location}/update',
    [\App\Http\Controllers\Admin\CampusMapController::class, 'updateLocation']
)
        ->middleware('admin.permission:campus_map,full')
        ->name('admin.campus-map.locations.update');


    /*
    |--------------------------------------------------------------------------
    | Room Import / Export
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/rooms/import', [RoomController::class, 'importForm'])
        ->middleware('admin.permission:rooms,view')
        ->name('rooms.import');

    Route::post('/admin/rooms/import', [RoomController::class, 'importRooms'])
        ->middleware('admin.permission:rooms,full')
        ->name('rooms.import.store');

    Route::get('/admin/rooms/import/preview', [RoomController::class, 'preview'])
        ->middleware('admin.permission:rooms,view')
        ->name('rooms.preview');

    Route::post('/admin/rooms/import/store', [RoomController::class, 'storeImportedRooms'])
        ->middleware('admin.permission:rooms,full')
        ->name('rooms.store.import');

    Route::get('/admin/rooms/template', [RoomController::class, 'template'])
        ->middleware('admin.permission:rooms,view')
        ->name('rooms.template');

    Route::get('/admin/rooms/export', [RoomController::class, 'export'])
        ->middleware('admin.permission:rooms,view')
        ->name('rooms.export');


/*
|--------------------------------------------------------------------------
| Delete All Rooms
|--------------------------------------------------------------------------
*/

Route::delete('/admin/rooms/delete-all', [RoomController::class, 'deleteAll'])
        ->middleware('admin.permission:rooms,full')
        ->name('rooms.deleteAll');

    /*
    |--------------------------------------------------------------------------
    | Room CRUD
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/rooms', [RoomController::class, 'index'])
        ->middleware('admin.permission:rooms,view')
        ->name('admin.rooms.index');

    Route::get('/admin/rooms/create', [RoomController::class, 'create'])
        ->middleware('admin.permission:rooms,full')
        ->name('admin.rooms.create');

    Route::post('/admin/rooms', [RoomController::class, 'store'])
        ->middleware('admin.permission:rooms,full')
        ->name('admin.rooms.store');

    Route::get('/admin/rooms/{room}', [RoomController::class, 'show'])
        ->middleware('admin.permission:rooms,view')
        ->name('admin.rooms.show');

   Route::get('/admin/rooms/{room}/edit', [RoomController::class, 'edit'])
    ->middleware('admin.permission:rooms,full')
    ->name('admin.rooms.edit');

Route::match(['put', 'patch'], '/admin/rooms/{room}', [RoomController::class, 'update'])
    ->middleware('admin.permission:rooms,full')
    ->name('admin.rooms.update');

Route::delete('/admin/rooms/{room}', [RoomController::class, 'destroy'])
    ->middleware('admin.permission:rooms,full')
    ->name('admin.rooms.destroy');


    /*
    |--------------------------------------------------------------------------
    | Schedules
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/schedules',
        [ScheduleController::class, 'index']
    )
        ->middleware('admin.permission:schedule_management,view')
        ->name('admin.schedules');


    Route::post(
        '/admin/schedules/import',
        [ScheduleImportController::class, 'store']
    )
        ->middleware('admin.permission:schedule_management,full')
        ->name('admin.schedules.import');


    Route::get(
        '/admin/schedules/import/preview',
        [ScheduleImportController::class, 'preview']
    )
        ->middleware('admin.permission:schedule_management,view')
        ->name('admin.schedules.preview');


    Route::post(
        '/admin/schedules/import/store',
        [ScheduleImportController::class, 'storeImportedSchedules']
    )
        ->middleware('admin.permission:schedule_management,full')
        ->name('admin.schedules.store');

    Route::delete(
        '/admin/schedules/bulk-delete',
        [ScheduleController::class, 'bulkDelete']
    )
        ->middleware('admin.permission:schedule_management,full')
        ->name('admin.schedules.bulk-delete');

    Route::delete(
        '/admin/schedules/{schedule}',
        [ScheduleController::class, 'destroy']
    )
        ->middleware('admin.permission:schedule_management,full')
        ->name('admin.schedules.destroy');


    Route::post(
        '/admin/schedules/import/cancel',
        [ScheduleImportController::class, 'cancel']
    )
        ->middleware('admin.permission:schedule_management,full')
        ->name('admin.schedules.cancel');


    /*
    |--------------------------------------------------------------------------
    | Reports
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/reports',
        [ReportController::class, 'index']
    )
        ->middleware('admin.permission:reports,view')
        ->name('admin.reports');


            /*
    |--------------------------------------------------------------------------
    | HR Room Monitoring
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/room-monitoring',
        [RoomMonitoringController::class, 'index']
    )
        ->middleware('admin.permission:room_monitoring,view')
        ->name('admin.room-monitoring');

    Route::get(
        '/admin/room-monitoring/buildings/{building}',
        [RoomMonitoringController::class, 'buildingRooms']
    )
        ->middleware('admin.permission:room_monitoring,view')
        ->name('admin.room-monitoring.buildings');

    Route::get(
        '/admin/room-monitoring/rooms/{room}',
        [RoomMonitoringController::class, 'roomDetails']
    )
        ->middleware('admin.permission:room_monitoring,view')
        ->name('admin.room-monitoring.rooms.show');

    Route::post(
        '/admin/room-monitoring/rooms/{room}/attendance',
        [RoomMonitoringController::class, 'storeAttendance']
    )
        ->middleware('admin.permission:room_monitoring,full')
        ->name('admin.room-monitoring.attendance.store');

    Route::get(
        '/admin/room-monitoring/rooms/{room}/history',
        [RoomMonitoringController::class, 'history']
    )
        ->middleware('admin.permission:room_monitoring,view')
        ->name('admin.room-monitoring.rooms.history');

    /*
    |--------------------------------------------------------------------------
    | Student
    |--------------------------------------------------------------------------
    */

    Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])
        ->name('student.dashboard');

    Route::get('/student/rooms', [StudentDashboardController::class, 'rooms'])
        ->name('student.rooms');

    Route::get('/student/rooms/{building}', [StudentDashboardController::class, 'buildingRooms'])
        ->name('student.rooms.building');

    Route::get('/student/room/{room}', [StudentDashboardController::class, 'roomDetails'])
        ->name('student.rooms.show');

   Route::get('/student/campus-navigation', function () {

    $activeAcademicYear = AcademicYear::where('is_active', true)->first();

    $colleges = College::orderBy('college_code')->get();

    $locations = CampusLocation::with('building')
        ->orderBy('number')
        ->get();

    $buildings = Building::with([
        'rooms' => function ($query) use ($activeAcademicYear) {
            $query->orderBy('floor')->orderBy('room_number');

            $query->with([
                'collegeAssignments' => function ($assignmentQuery) use ($activeAcademicYear) {
                    if ($activeAcademicYear) {
                        $assignmentQuery->where('academic_year_id', $activeAcademicYear->id);
                    } else {
                        $assignmentQuery->whereRaw('1 = 0');
                    }

                    $assignmentQuery->with('college');
                }
            ]);
        }
    ])->orderBy('building_code')->get();

    return view('visitor.dashboard', compact(
        'locations',
        'buildings',
        'colleges',
        'activeAcademicYear'
    ));

})->name('student.campus-navigation');

    Route::get('/student/profile', function () {
        return view('student.profile');
    })->name('student.profile');

    Route::post('/student/profile/password', function (\Illuminate\Http\Request $request) {

        $request->validate([
            'current_password' => ['required', 'current_password:web'],
            'password' => ['required', 'min:8', 'confirmed'],
        ], [
            'current_password.current_password' => 'Your current password is incorrect.',
            'password.confirmed' => 'The new passwords do not match.',
            'password.min' => 'The new password must be at least 8 characters.',
        ]);

        auth()->user()->update([
            'password' => \Illuminate\Support\Facades\Hash::make(
                $request->password
            ),
        ]);

        return back()->with(
            'password_success',
            'Your password has been changed successfully.'
        );

    })->name('student.profile.password');


    /*
    |--------------------------------------------------------------------------
    | Instructor
    |--------------------------------------------------------------------------
    */

    Route::get('/instructor/dashboard', [InstructorDashboardController::class, 'index'])
        ->name('instructor.dashboard');

    Route::get('/instructor/schedule', [InstructorDashboardController::class, 'schedule'])
        ->name('instructor.schedule');

    Route::get('/instructor/rooms', [InstructorDashboardController::class, 'buildings'])
        ->name('instructor.rooms');

    Route::get('/instructor/rooms/{building}', [InstructorDashboardController::class, 'buildingRooms'])
        ->name('instructor.rooms.building');

    Route::get('/instructor/room/{room}', [InstructorDashboardController::class, 'roomDetails'])
        ->name('instructor.rooms.show');

    Route::patch('/instructor/schedule/{schedule}/start', [InstructorDashboardController::class, 'startClass'])
        ->name('instructor.schedule.start');

    Route::patch(
        '/instructor/schedule/{schedule}/end',
        [InstructorDashboardController::class, 'endClass']
    )->name('instructor.schedule.end');

    Route::get('/instructor/campus-navigation', function () {

    $activeAcademicYear = AcademicYear::where('is_active', true)->first();

    $colleges = College::orderBy('college_code')->get();

    $locations = CampusLocation::with('building')
        ->orderBy('number')
        ->get();

    $buildings = Building::with([
        'rooms' => function ($query) use ($activeAcademicYear) {
            $query->orderBy('floor')->orderBy('room_number');

            $query->with([
                'collegeAssignments' => function ($assignmentQuery) use ($activeAcademicYear) {
                    if ($activeAcademicYear) {
                        $assignmentQuery->where('academic_year_id', $activeAcademicYear->id);
                    } else {
                        $assignmentQuery->whereRaw('1 = 0');
                    }

                    $assignmentQuery->with('college');
                }
            ]);
        }
    ])->orderBy('building_code')->get();

    return view('visitor.dashboard', compact(
        'locations',
        'buildings',
        'colleges',
        'activeAcademicYear'
    ));

})->name('instructor.campus-navigation');

    Route::get('/instructor/profile', function () {
        return view('instructor.profile');
    })->name('instructor.profile');


    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});


require __DIR__.'/auth.php';