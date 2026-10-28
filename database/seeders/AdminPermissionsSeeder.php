<?php

namespace Database\Seeders;

use App\Models\AdminPermission;
use App\Models\User;
use App\Support\AdminPermissions;
use Illuminate\Database\Seeder;

class AdminPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = AdminPermissions::defaults();

        // Registrar: manage users and schedules,
        // but only view buildings and rooms.
        $registrarPermissions = array_merge($defaults, [
            'dashboard' => 'full',
            'user_management' => 'full',
            'schedule_management' => 'full',
            'buildings' => 'view',
            'rooms' => 'view',
            'reports' => 'view',
            'activity_logs' => 'view',
        ]);

        // Facilities administrator: manage buildings,
        // rooms, and the campus map.
        $facilitiesPermissions = array_merge($defaults, [
            'dashboard' => 'full',
            'buildings' => 'full',
            'rooms' => 'full',
            'campus_map' => 'full',
        ]);

        $registrar = User::where('role', 'admin')->first();

        if ($registrar) {
            AdminPermission::updateOrCreate(
                ['user_id' => $registrar->id],
                ['permissions' => $registrarPermissions]
            );
        }

        $facilitiesAdmins = User::where('role', 'facilities_admin')->get();

        foreach ($facilitiesAdmins as $user) {
            AdminPermission::updateOrCreate(
                ['user_id' => $user->id],
                ['permissions' => $facilitiesPermissions]
            );
        }
    }
}