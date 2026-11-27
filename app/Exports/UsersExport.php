<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    protected string $role;

    public function __construct(string $role)
    {
        $this->role = $role;
    }

    public function collection()
    {
        if ($this->role === 'student') {
            return User::where('role', 'student')
                ->select(
                    'student_id',
                    'first_name',
                    'last_name',
                    'course',
                    'email',
                    'status'
                )
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->get();
        }

        if ($this->role === 'instructor') {
            return User::where('role', 'instructor')
                ->select(
                    'employee_id',
                    'first_name',
                    'last_name',
                    'department',
                    'email',
                    'status'
                )
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->get();
        }

        return collect();
    }

    public function headings(): array
    {
        if ($this->role === 'student') {
            return [
                'Student ID',
                'First Name',
                'Last Name',
                'Course',
                'Email',
                'Status',
            ];
        }

        return [
            'Employee ID',
            'First Name',
            'Last Name',
            'Department',
            'Email',
            'Status',
        ];
    }
}
