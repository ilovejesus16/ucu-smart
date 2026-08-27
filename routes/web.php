<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BuildingController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\ScheduleImportController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Instructor\DashboardController as InstructorDashboardController;
use App\Http\Controllers\Admin\ReportController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;


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
    return view('visitor.dashboard');
})->name('visitor.dashboard');


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {


    /*
    |--------------------------------------------------------------------------
    | Admin Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');


    /*
    |--------------------------------------------------------------------------
    | User Management
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/users', [UserController::class, 'index'])
        ->name('admin.users');

    Route::post('/admin/users', [UserController::class, 'store'])
        ->name('admin.users.store');

    Route::get('/admin/users/{user}', [UserController::class, 'show'])
        ->name('admin.users.show');

    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])
        ->name('admin.users.edit');

    Route::put('/admin/users/{user}', [UserController::class, 'update'])
        ->name('admin.users.update');

    Route::patch('/admin/users/{user}/approve', [UserController::class, 'approve'])
        ->name('admin.users.approve');

    Route::patch('/admin/users/{user}/reject', [UserController::class, 'reject'])
        ->name('admin.users.reject');

    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])
        ->name('admin.users.destroy');


    /*
    |--------------------------------------------------------------------------
    | User Import
    |--------------------------------------------------------------------------
    */

    // Students

    Route::get('/admin/users/import/students', [UserController::class, 'studentImportForm'])
        ->name('admin.users.import.students');

    Route::post('/admin/users/import/students', [UserController::class, 'importStudents'])
        ->name('admin.users.import.students.store');

    Route::get('/admin/users/import/students/preview', [UserController::class, 'studentPreview'])
        ->name('admin.users.student.preview');

    Route::post('/admin/users/import/students/store', [UserController::class, 'storeImportedStudents'])
        ->name('admin.users.student.store');


    // Instructors

    Route::get('/admin/users/import/instructors', [UserController::class, 'instructorImportForm'])
        ->name('admin.users.import.instructors');

    Route::post('/admin/users/import/instructors', [UserController::class, 'importInstructors'])
        ->name('admin.users.import.instructors.store');

    Route::get('/admin/users/import/instructors/preview', [UserController::class, 'instructorPreview'])
        ->name('admin.users.instructor.preview');

    Route::post('/admin/users/import/instructors/store', [UserController::class, 'storeImportedInstructors'])
        ->name('admin.users.instructor.store');


    /*
    |--------------------------------------------------------------------------
    | User Templates
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/users/template/students', [UserController::class, 'studentTemplate'])
        ->name('admin.users.template.students');

    Route::get('/admin/users/template/instructors', [UserController::class, 'instructorTemplate'])
        ->name('admin.users.template.instructors');


    /*
    |--------------------------------------------------------------------------
    | Buildings
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/buildings', [BuildingController::class, 'index'])
        ->name('buildings.index');

    Route::post('/admin/buildings', [BuildingController::class, 'store'])
        ->name('buildings.store');

    Route::get('/admin/buildings/{building}', [BuildingController::class, 'show'])
        ->name('buildings.show');

    Route::get('/admin/buildings/{building}/edit', [BuildingController::class, 'edit'])
        ->name('buildings.edit');

    Route::put('/admin/buildings/{building}', [BuildingController::class, 'update'])
        ->name('buildings.update');

    Route::delete('/admin/buildings/{building}', [BuildingController::class, 'destroy'])
        ->name('buildings.destroy');


    /*
    |--------------------------------------------------------------------------
    | Building Import / Export
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/buildings/import', [BuildingController::class, 'importForm'])
        ->name('buildings.import');

    Route::post('/admin/buildings/import', [BuildingController::class, 'importBuildings'])
        ->name('buildings.import.store');

    Route::get('/admin/buildings/import/preview', [BuildingController::class, 'preview'])
        ->name('buildings.preview');

    Route::post('/admin/buildings/import/store', [BuildingController::class, 'storeImportedBuildings'])
        ->name('buildings.store.import');

    Route::get('/admin/buildings/template', [BuildingController::class, 'template'])
        ->name('buildings.template');

    Route::get('/admin/buildings/export', [BuildingController::class, 'export'])
        ->name('buildings.export');


   /*
|--------------------------------------------------------------------------
| Rooms
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Room Import / Export
|--------------------------------------------------------------------------
*/

Route::get('/admin/rooms/import', [RoomController::class, 'importForm'])
    ->name('rooms.import');

Route::post('/admin/rooms/import', [RoomController::class, 'importRooms'])
    ->name('rooms.import.store');

Route::get('/admin/rooms/import/preview', [RoomController::class, 'preview'])
    ->name('rooms.preview');

Route::post('/admin/rooms/import/store', [RoomController::class, 'storeImportedRooms'])
    ->name('rooms.store.import');

Route::get('/admin/rooms/template', [RoomController::class, 'template'])
    ->name('rooms.template');

Route::get('/admin/rooms/export', [RoomController::class, 'export'])
    ->name('rooms.export');


/*
|--------------------------------------------------------------------------
| Room CRUD
|--------------------------------------------------------------------------
*/

Route::resource('/admin/rooms', RoomController::class);


/*
|--------------------------------------------------------------------------
| Schedules
|--------------------------------------------------------------------------
*/

Route::get(
    '/admin/schedules',
    [ScheduleController::class, 'index']
)->name('admin.schedules');


Route::post(
    '/admin/schedules/import',
    [ScheduleImportController::class, 'store']
)->name('admin.schedules.import');


Route::get(
    '/admin/schedules/import/preview',
    [ScheduleImportController::class, 'preview']
)->name('admin.schedules.preview');


Route::post(
    '/admin/schedules/import/store',
    [ScheduleImportController::class, 'storeImportedSchedules']
)->name('admin.schedules.store');

Route::delete(
    '/admin/schedules/bulk-delete',
    [ScheduleController::class, 'bulkDelete']
)->name('admin.schedules.bulk-delete');

Route::delete(
    '/admin/schedules/{schedule}',
    [ScheduleController::class, 'destroy']
)->name('admin.schedules.destroy');


Route::post(
    '/admin/schedules/import/cancel',
    [ScheduleImportController::class, 'cancel']
)->name('admin.schedules.cancel');


/*
|--------------------------------------------------------------------------
| Reports
|--------------------------------------------------------------------------
*/

Route::get(
    '/admin/reports',
    [ReportController::class, 'index']
)->name('admin.reports');

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