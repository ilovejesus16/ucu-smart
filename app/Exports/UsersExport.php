<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection
{
    public function collection()
    {
        return User::select(
            'student_id',
            'employee_id',
            'first_name',
            'last_name',
            'role',
            'course',
            'department',
            'email',
            'status'
        )->get();
    }
}