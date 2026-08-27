<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InstructorsImport implements ToCollection, WithHeadingRow
{
    public array $instructors = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $duplicate = User::where('employee_id', $row['employee_id'])->exists();

            $invalid = empty($row['employee_id']) ||
                       empty($row['first_name']) ||
                       empty($row['last_name']) ||
                       empty($row['department']) ||
                       empty($row['email']) ||
                       !filter_var($row['email'], FILTER_VALIDATE_EMAIL);

            $this->instructors[] = [
                'employee_id' => $row['employee_id'],
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'department' => $row['department'],
                'email' => $row['email'],
                'status' => $invalid
                    ? 'invalid'
                    : ($duplicate ? 'duplicate' : 'new'),
            ];
        }
    }
}