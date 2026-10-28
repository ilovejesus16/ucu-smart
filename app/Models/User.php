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

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
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

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function adminPermission()
{
    return $this->hasOne(\App\Models\AdminPermission::class);
}


public function hasAdminPermission(
    string $feature,
    string $minimumLevel = 'view'
): bool {
    $permissions = $this->adminPermission?->permissions ?? [];

    $levels = [
        'none' => 0,
        'view' => 1,
        'full' => 2,
    ];

    if (
        !array_key_exists($feature, \App\Support\AdminPermissions::FEATURES) ||
        !isset($levels[$minimumLevel])
    ) {
        return false;
    }

    $currentLevel = $permissions[$feature] ?? 'none';

    return isset($levels[$currentLevel]) &&
        $levels[$currentLevel] >= $levels[$minimumLevel];
}

/**
 * Get the first admin page this user can access.
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
        'reports' => 'admin.reports',
    ];

    foreach ($pages as $feature => $route) {
        if (
            $this->hasAdminPermission($feature, 'view')
            && \Illuminate\Support\Facades\Route::has($route)
        ) {
            return $route;
        }
    }

    return null;
}

}
