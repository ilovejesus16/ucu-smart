<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToCollection, WithHeadingRow
{
    public array $students = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $duplicate = User::where('student_id', $row['student_id'])->exists();

            $invalid = empty($row['student_id']) ||
                       empty($row['first_name']) ||
                       empty($row['last_name']) ||
                       empty($row['course']) ||
                       empty($row['email']) ||
                       !filter_var($row['email'], FILTER_VALIDATE_EMAIL);

            $this->students[] = [
                'student_id' => $row['student_id'],
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'course' => $row['course'],
                'email' => $row['email'],
                'status' => $invalid
                    ? 'invalid'
                    : ($duplicate ? 'duplicate' : 'new'),
            ];
        }
    }
}