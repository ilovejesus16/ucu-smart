<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'username' => 'admin',
            ],
            [
                'role' => 'admin',
                'status' => 'active',

                'student_id' => null,
                'employee_id' => null,

                'first_name' => 'System',
                'last_name' => 'Administrator',

                'college' => null,
                'course' => null,
                'department' => null,

                'email' => 'admin@ucu-smart.com',

                'password' => Hash::make('admin123'),
            ]
        );
    }
}