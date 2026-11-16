<?php

namespace App\Support;

class AdminPermissions
{
    public const LEVELS = [
        'none' => 'No access',
        'view' => 'View only',
        'full' => 'Full access',
    ];

    public const FEATURES = [
    'dashboard' => 'Dashboard',
    'user_management' => 'User Management',
    'schedule_management' => 'Schedule Management',
    'buildings' => 'Buildings',
    'rooms' => 'Rooms',
    'campus_map' => 'Campus Map',
    'room_monitoring' => 'Room Monitoring',
    'reports' => 'Reports & Activity Logs',
];

    public static function defaults(): array
    {
        return array_fill_keys(
            array_keys(self::FEATURES),
            'none'
        );
    }
}