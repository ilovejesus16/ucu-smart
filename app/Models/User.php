<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'role',
        'status',
        'student_id',
        'employee_id',
        'first_name',
        'last_name',
        'college',
        'course',
        'department',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Admin Permission Relationship
    |--------------------------------------------------------------------------
    */

    public function adminPermission()
    {
        return $this->hasOne(\App\Models\AdminPermission::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Check Admin Permission
    |--------------------------------------------------------------------------
    */

    public function hasAdminPermission(
        string $feature,
        string $minimumLevel = 'view'
    ): bool {

        // Validate requested feature.
        if (!array_key_exists(
            $feature,
            \App\Support\AdminPermissions::FEATURES
        )) {
            return false;
        }

        $levels = [
            'none' => 0,
            'view' => 1,
            'full' => 2,
        ];

        if (!isset($levels[$minimumLevel])) {
            return false;
        }

        // Super Admin has unrestricted access.
        if ($this->role === 'super_admin') {
            return true;
        }

        $permissions = $this->adminPermission?->permissions ?? [];

        $currentLevel = $permissions[$feature] ?? 'none';

        return isset($levels[$currentLevel]) &&
            $levels[$currentLevel] >= $levels[$minimumLevel];
    }

    /*
    |--------------------------------------------------------------------------
    | First Accessible Admin Route
    |--------------------------------------------------------------------------
    */

    public function firstAccessibleAdminRoute(): ?string
    {
        $pages = [
            'dashboard' => 'admin.dashboard',
            'user_management' => 'admin.users',
            'buildings' => 'buildings.index',
            'rooms' => 'admin.rooms.index',
            'schedule_management' => 'admin.schedules',
            'campus_map' => 'admin.campus-map',
            'room_monitoring' => 'admin.room-monitoring',
            'reports' => 'admin.reports',
        ];

        foreach ($pages as $feature => $route) {

            if (
                $this->hasAdminPermission($feature, 'view') &&
                \Illuminate\Support\Facades\Route::has($route)
            ) {
                return $route;
            }
        }

        return null;
    }
}
