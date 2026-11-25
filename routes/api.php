<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StudentAuthController;
use App\Http\Controllers\Api\StudentDashboardController;
use App\Http\Controllers\Api\StudentRoomController;
use App\Http\Controllers\Api\PublicBuildingController;
use App\Http\Controllers\Api\BuildingAnnouncementController;

// Public campus map buildings endpoint.
Route::get('/buildings', [PublicBuildingController::class, 'index']);

// Public building announcements.
Route::get('/announcements', [BuildingAnnouncementController::class, 'index']);

Route::post('/announcements/{announcement}/read', [
    BuildingAnnouncementController::class,
    'markAsRead'
]);

Route::post('/announcements/read-all', [
    BuildingAnnouncementController::class,
    'markAllAsRead'
]);

// Student API routes.
Route::prefix('student')->group(function () {
    Route::post('/login', [StudentAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [StudentAuthController::class, 'me']);
        Route::post('/logout', [StudentAuthController::class, 'logout']);
        Route::get('/dashboard', [StudentDashboardController::class, 'index']);

        Route::get('/buildings', [StudentRoomController::class, 'buildings']);

        Route::get('/buildings/{building}/rooms', [
            StudentRoomController::class,
            'buildingRooms'
        ]);

        Route::get('/rooms/{room}', [
            StudentRoomController::class,
            'roomDetails'
        ]);
    });
});
