<?php

namespace App\Imports;

use App\Models\Room;
use App\Models\User;
use App\Models\Schedule;
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
            | Skip Completely Empty Rows
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
            | Read Excel Values
            |--------------------------------------------------------------------------
            */

            $employeeId = trim(
                (string) ($row['employee_id'] ?? '')
            );

            $subjectCode = trim(
                (string) ($row['subject_code'] ?? '')
            );

            $subjectName = trim(
                (string) ($row['subject_name'] ?? '')
            );

            $roomValue = trim(
                (string) ($row['room'] ?? '')
            );

            $day = trim(
                (string) ($row['day'] ?? '')
            );

            $semester = trim(
                (string) ($row['semester'] ?? '')
            );

            $schoolYear = trim(
                (string) ($row['school_year'] ?? '')
            );


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
                ->where(
                    'role',
                    'instructor'
                )
                ->where(
                    'status',
                    'active'
                )
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

                    'remarks' =>
                        'Instructor not found or inactive.',

                ];

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Normalize Room Number
            |--------------------------------------------------------------------------
            |
            | The Rooms table stores room_number as:
            |
            | LA I - 101
            | LA I - 102
            | LA II - 108
            | RW - 305
            | TEC - 401
            |
            | But the schedule Excel may contain:
            |
            | LA I 102
            | LA I 104
            | LA II 108
            | RW 401
            | TEC 302
            |
            | Therefore we normalize the Excel value to the same format
            | used by RoomsImport.
            |
            */

            $normalizedRoom = $this->normalizeRoomNumber(
                $roomValue
            );


            /*
            |--------------------------------------------------------------------------
            | Find Room
            |--------------------------------------------------------------------------
            */

            $room = Room::whereRaw(
                'LOWER(TRIM(room_number)) = ?',
                [
                    strtolower($normalizedRoom)
                ]
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

                    'room_number' => $normalizedRoom,

                    'day' => $day,

                    'start_time' => null,

                    'end_time' => null,

                    'semester' => $semester,

                    'school_year' => $schoolYear,

                    'status' => 'invalid',

                    'remarks' =>
                        'Room not found: ' .
                        $normalizedRoom,

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

            $duplicate = Schedule::where(
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


    /*
    |--------------------------------------------------------------------------
    | Normalize Room Number
    |--------------------------------------------------------------------------
    |
    | Examples:
    |
    | LA I 102       -> LA I - 102
    | LA I - 102     -> LA I - 102
    | LA II 211      -> LA II - 211
    | RW 401         -> RW - 401
    | TEC 302        -> TEC - 302
    |
    |--------------------------------------------------------------------------
    */

    private function normalizeRoomNumber(
        string $value
    ): string {

        $value = trim($value);

        /*
        |--------------------------------------------------------------------------
        | Normalize whitespace
        |--------------------------------------------------------------------------
        */

        $value = preg_replace(
            '/\s+/u',
            ' ',
            $value
        );


        /*
        |--------------------------------------------------------------------------
        | Normalize dash characters
        |--------------------------------------------------------------------------
        */

        $value = str_replace(
            ['—', '–'],
            '-',
            $value
        );


        /*
        |--------------------------------------------------------------------------
        | Normalize spaces around dash
        |--------------------------------------------------------------------------
        */

        $value = preg_replace(
            '/\s*-\s*/',
            ' - ',
            $value
        );


        /*
        |--------------------------------------------------------------------------
        | Already in correct format
        |--------------------------------------------------------------------------
        */

        if (
            preg_match(
                '/^(.+?)\s*-\s*(\d+)$/',
                $value,
                $matches
            )
        ) {

            return trim($matches[1])
                . ' - '
                . trim($matches[2]);
        }


        /*
        |--------------------------------------------------------------------------
        | Excel format without dash
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | LA I 102
        |
        */

        if (
            preg_match(
                '/^(.+?)\s+(\d+)$/',
                $value,
                $matches
            )
        ) {

            return trim($matches[1])
                . ' - '
                . trim($matches[2]);
        }


        /*
        |--------------------------------------------------------------------------
        | Return Original Value
        |--------------------------------------------------------------------------
        */

        return trim($value);
    }


    /*
    |--------------------------------------------------------------------------
    | Convert Excel / Text Time
    |--------------------------------------------------------------------------
    */

    private function convertTime(
        $value
    ): string {

        /*
        |--------------------------------------------------------------------------
        | Excel Numeric Time
        |--------------------------------------------------------------------------
        */

        if (is_numeric($value)) {

            return Carbon::instance(
                Date::excelToDateTimeObject(
                    $value
                )
            )->format('H:i:s');
        }


        /*
        |--------------------------------------------------------------------------
        | String Time
        |--------------------------------------------------------------------------
        */

        $value = trim(
            (string) $value
        );


        if ($value === '') {

            throw new \InvalidArgumentException(
                'Time value is empty.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Common Time Formats
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

            return Carbon::parse(
                $value
            )->format('H:i:s');

        } catch (\Throwable $e) {

            throw new \InvalidArgumentException(
                'Invalid time format.'
            );
        }
    }
}