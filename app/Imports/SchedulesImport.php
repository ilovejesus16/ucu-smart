<?php

namespace App\Imports;

use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

class SchedulesImport implements ToCollection, WithHeadingRow
{
    /**
     * Rows prepared for preview.
     */
    public array $schedules = [];

    /**
     * Process Excel rows.
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            /*
            |--------------------------------------------------------------------------
            | Skip completely empty rows
            |--------------------------------------------------------------------------
            */

            if (
                empty($row['employee_id']) &&
                empty($row['subject_code']) &&
                empty($row['subject_name']) &&
                empty($row['room'])
            ) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Read Excel values
            |--------------------------------------------------------------------------
            */

            $employeeId = trim((string) ($row['employee_id'] ?? ''));

            $subjectCode = trim((string) ($row['subject_code'] ?? ''));

            $subjectName = trim((string) ($row['subject_name'] ?? ''));

            $roomValue = trim((string) ($row['room'] ?? ''));

            $day = trim((string) ($row['day'] ?? ''));

            $semester = trim((string) ($row['semester'] ?? ''));

            $schoolYear = trim((string) ($row['school_year'] ?? ''));


            /*
            |--------------------------------------------------------------------------
            | Missing Required Information
            |--------------------------------------------------------------------------
            */

            if (
                $employeeId === '' ||
                $subjectCode === '' ||
                $subjectName === '' ||
                $roomValue === '' ||
                $day === '' ||
                empty($row['start_time']) ||
                empty($row['end_time']) ||
                $semester === '' ||
                $schoolYear === ''
            ) {

                $this->schedules[] = [

                    'employee_id' => $employeeId,

                    'instructor_id' => null,

                    'instructor_name' => null,

                    'subject_code' => $subjectCode,

                    'subject_name' => $subjectName,

                    'room_input' => $roomValue,

                    'room_id' => null,

                    'room_name' => null,

                    'room_number' => null,

                    'day' => $day,

                    'start_time' => null,

                    'end_time' => null,

                    'semester' => $semester,

                    'school_year' => $schoolYear,

                    'status' => 'invalid',

                    'remarks' => 'Missing required information.',

                ];

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Find Instructor
            |--------------------------------------------------------------------------
            */

            $instructor = User::where(
                'employee_id',
                $employeeId
            )
                ->where('role', 'instructor')
                ->where('status', 'active')
                ->first();


            /*
            |--------------------------------------------------------------------------
            | Instructor Not Found
            |--------------------------------------------------------------------------
            */

            if (!$instructor) {

                $this->schedules[] = [

                    'employee_id' => $employeeId,

                    'instructor_id' => null,

                    'instructor_name' => null,

                    'subject_code' => $subjectCode,

                    'subject_name' => $subjectName,

                    'room_input' => $roomValue,

                    'room_id' => null,

                    'room_name' => null,

                    'room_number' => null,

                    'day' => $day,

                    'start_time' => null,

                    'end_time' => null,

                    'semester' => $semester,

                    'school_year' => $schoolYear,

                    'status' => 'invalid',

                    'remarks' => 'Instructor not found or inactive.',

                ];

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Extract Room Number
            |--------------------------------------------------------------------------
            |
            | Example:
            |
            | "Room 401" → "401"
            | "RW 401"   → "401"
            | "401"      → "401"
            |
            */

            $roomNumber = preg_replace(
                '/\D+/',
                '',
                $roomValue
            );


            /*
            |--------------------------------------------------------------------------
            | Find Room
            |--------------------------------------------------------------------------
            */

            $room = Room::where(
                'room_number',
                $roomNumber
            )->first();


            /*
            |--------------------------------------------------------------------------
            | Room Not Found
            |--------------------------------------------------------------------------
            */

            if (!$room) {

                $this->schedules[] = [

                    'employee_id' => $employeeId,

                    'instructor_id' => $instructor->id,

                    'instructor_name' =>
                        $instructor->first_name . ' ' .
                        $instructor->last_name,

                    'subject_code' => $subjectCode,

                    'subject_name' => $subjectName,

                    'room_input' => $roomValue,

                    'room_id' => null,

                    'room_name' => null,

                    'room_number' => $roomNumber,

                    'day' => $day,

                    'start_time' => null,

                    'end_time' => null,

                    'semester' => $semester,

                    'school_year' => $schoolYear,

                    'status' => 'invalid',

                    'remarks' => 'Room not found.',

                ];

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Convert Start Time
            |--------------------------------------------------------------------------
            */

            try {

                $startTime = $this->convertTime(
                    $row['start_time']
                );

            } catch (\Throwable $e) {

                $this->schedules[] = [

                    'employee_id' => $employeeId,

                    'instructor_id' => $instructor->id,

                    'instructor_name' =>
                        $instructor->first_name . ' ' .
                        $instructor->last_name,

                    'subject_code' => $subjectCode,

                    'subject_name' => $subjectName,

                    'room_input' => $roomValue,

                    'room_id' => $room->id,

                    'room_name' => $room->room_name,

                    'room_number' => $room->room_number,

                    'day' => $day,

                    'start_time' => null,

                    'end_time' => null,

                    'semester' => $semester,

                    'school_year' => $schoolYear,

                    'status' => 'invalid',

                    'remarks' => 'Invalid start time.',

                ];

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Convert End Time
            |--------------------------------------------------------------------------
            */

            try {

                $endTime = $this->convertTime(
                    $row['end_time']
                );

            } catch (\Throwable $e) {

                $this->schedules[] = [

                    'employee_id' => $employeeId,

                    'instructor_id' => $instructor->id,

                    'instructor_name' =>
                        $instructor->first_name . ' ' .
                        $instructor->last_name,

                    'subject_code' => $subjectCode,

                    'subject_name' => $subjectName,

                    'room_input' => $roomValue,

                    'room_id' => $room->id,

                    'room_name' => $room->room_name,

                    'room_number' => $room->room_number,

                    'day' => $day,

                    'start_time' => $startTime,

                    'end_time' => null,

                    'semester' => $semester,

                    'school_year' => $schoolYear,

                    'status' => 'invalid',

                    'remarks' => 'Invalid end time.',

                ];

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Check Existing Duplicate
            |--------------------------------------------------------------------------
            */

            $duplicate = \App\Models\Schedule::where(
                'room_id',
                $room->id
            )
                ->where(
                    'day',
                    $day
                )
                ->where(
                    'start_time',
                    $startTime
                )
                ->where(
                    'end_time',
                    $endTime
                )
                ->where(
                    'semester',
                    $semester
                )
                ->where(
                    'school_year',
                    $schoolYear
                )
                ->exists();


            /*
            |--------------------------------------------------------------------------
            | Add Preview Row
            |--------------------------------------------------------------------------
            */

            $this->schedules[] = [

                'employee_id' => $employeeId,

                'instructor_id' => $instructor->id,

                'instructor_name' =>
                    $instructor->first_name . ' ' .
                    $instructor->last_name,

                'subject_code' => $subjectCode,

                'subject_name' => $subjectName,

                'room_input' => $roomValue,

                'room_id' => $room->id,

                'room_name' => $room->room_name,

                'room_number' => $room->room_number,

                'day' => $day,

                'start_time' => $startTime,

                'end_time' => $endTime,

                'semester' => $semester,

                'school_year' => $schoolYear,

                'status' => $duplicate
                    ? 'duplicate'
                    : 'new',

                'remarks' => $duplicate
                    ? 'Schedule already exists.'
                    : 'Ready to import.',

            ];
        }
    }


    /**
     * Convert Excel / text time to H:i:s.
     */
    private function convertTime($value): string
    {
        /*
        |--------------------------------------------------------------------------
        | Excel Numeric Time
        |--------------------------------------------------------------------------
        */

        if (is_numeric($value)) {

            return Carbon::instance(
                Date::excelToDateTimeObject($value)
            )->format('H:i:s');
        }


        /*
        |--------------------------------------------------------------------------
        | String Time
        |--------------------------------------------------------------------------
        */

        $value = trim((string) $value);


        if ($value === '') {

            throw new \InvalidArgumentException(
                'Time value is empty.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Try Common Time Formats
        |--------------------------------------------------------------------------
        */

        $formats = [

            'H:i:s',

            'H:i',

            'g:i A',

            'g:i a',

            'h:i A',

            'h:i a',

        ];


        foreach ($formats as $format) {

            try {

                return Carbon::createFromFormat(
                    $format,
                    $value
                )->format('H:i:s');

            } catch (\Throwable $e) {

                // Try next format.

            }
        }


        /*
        |--------------------------------------------------------------------------
        | Last Attempt
        |--------------------------------------------------------------------------
        */

        try {

            return Carbon::parse($value)
                ->format('H:i:s');

        } catch (\Throwable $e) {

            throw new \InvalidArgumentException(
                'Invalid time format.'
            );
        }
    }
}