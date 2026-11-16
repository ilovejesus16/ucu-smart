<?php



namespace App\Imports;



use App\Models\Room;

use App\Models\User;

use App\Models\Schedule;

use Illuminate\Support\Collection;

use Maatwebsite\Excel\Concerns\ToCollection;

use Maatwebsite\Excel\Concerns\WithHeadingRow;

use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

use Carbon\Carbon;



class SchedulesImport implements ToCollection, WithHeadingRow

{

    public array $schedules = [];

    protected Collection $roomCache;

    protected ?string $semester;

    protected ?string $schoolYear;



    public function __construct(

        ?string $semester = null,

        ?string $schoolYear = null

    ) {

        $this->semester = $semester;

        $this->schoolYear = $schoolYear;

    }



    public function collection(Collection $rows)

    {
        // Cache rooms once for this import so each row does not query them again.
        $this->roomCache = Room::all();

        foreach ($rows as $row) {

            $code = trim((string) ($row['code'] ?? ''));

            $section = trim((string) ($row['name'] ?? ''));

            $subject = trim((string) ($row['subject_title'] ?? ''));



            $classroom = trim((string) ($row['classroom'] ?? ''));

            $employeeId = trim((string) ($row['instructor'] ?? ''));



            $day = $this->normalizeDays($row['days'] ?? '');

            $start = $this->convertTime($row['start'] ?? null);

            $end = $this->convertTime($row['end'] ?? null);



            $room = $classroom !== ''

                ? $this->findRoom($classroom)

                : null;



            $instructor = $employeeId !== ''

                ? User::where('employee_id', $employeeId)

                    ->where('role', 'instructor')

                    ->where('status', 'active')

                    ->first()

                : null;



            $record = [

                'employee_id' => $employeeId,

                'instructor_id' => $instructor?->id,

                'instructor_name' => $instructor

                    ? trim($instructor->first_name . ' ' . $instructor->last_name)

                    : ($employeeId ?: 'Not assigned'),



                'subject_code' => $code,

                'section_name' => $section,

                'subject_name' => $subject,

                'units' => $this->numericValue($row['units'] ?? null),

                'enrolled' => $this->integerValue($row['enrolled'] ?? null),



                'room_input' => $classroom,

                'room_id' => $room?->id,

                'room_name' => $room?->room_name,

                'room_number' => $room?->room_number,



                'day' => $day,

                'start_time' => $start,

                'end_time' => $end,



                'semester' => $this->semester,

                'school_year' => $this->schoolYear,



                'status' => 'new',

                'remarks' => [],

            ];



            // Required information

            if ($code === '' || $subject === '' || $day === '' || !$start || !$end) {

                $record['status'] = 'invalid';

                $record['remarks'][] = 'Missing subject code, subject title, day, or valid time.';

            }



            if ($start && $end && $end <= $start) {

                $record['status'] = 'invalid';

                $record['remarks'][] = 'End time must be later than start time.';

            }



            if (!$this->semester || !$this->schoolYear) {

                $record['status'] = 'invalid';

                $record['remarks'][] = 'Semester or school year is missing.';

            }



            // Room and instructor can be reviewed or assigned in the preview.

            if ($classroom !== '' && !$room) {

                $record['remarks'][] = "Classroom '{$classroom}' was not found.";

            }



            if ($employeeId !== '' && !$instructor) {

                $record['remarks'][] = "Instructor ID '{$employeeId}' was not found or is inactive.";

            }



            if ($classroom === '') {

                $record['remarks'][] = 'No classroom assigned.';

            }



            if ($employeeId === '') {

                $record['remarks'][] = 'No instructor assigned.';

            }



            // Check exact duplicates and overlapping schedules.

            if ($record['status'] !== 'invalid') {

                $conflicts = $this->findConflicts($record);



                if (!empty($conflicts)) {

                    $record['status'] = 'duplicate';

                    $record['remarks'] = array_merge(

                        $record['remarks'],

                        $conflicts

                    );

                }

            }



            $record['remarks'] = implode(' ', $record['remarks']);



            $this->schedules[] = $record;

        }

    }



    private function findRoom(string $classroom): ?Room

    {

        $normalized = $this->normalizeText($classroom);



        return $this->roomCache->first(function ($room) use ($normalized) {

            return $this->normalizeText($room->room_number) === $normalized

                || $this->normalizeText($room->room_name) === $normalized;

        });

    }



    private function findConflicts(array $record): array

    {

        $messages = [];



        $existingSchedules = Schedule::with('room', 'instructor')

            ->where('semester', $record['semester'])

            ->where('school_year', $record['school_year'])

            ->get();



        foreach ($existingSchedules as $existing) {

            $result = $this->compareSchedules($record, $existing);



            if ($result) {

                $messages[] = $result;

            }

        }



        // Also check rows already read from this uploaded file.

        foreach ($this->schedules as $previous) {

            if ($previous['status'] === 'invalid') {

                continue;

            }



            $result = $this->compareRows($record, $previous);



            if ($result) {

                $messages[] = $result;

            }

        }



        return array_unique($messages);

    }



    private function compareSchedules(array $new, Schedule $existing): ?string

    {

        $sameTime = $this->overlaps(

            $new['start_time'],

            $new['end_time'],

            $existing->start_time,

            $existing->end_time

        );



        if (!$sameTime || !$this->sharesDay($new['day'], $existing->day)) {

            return null;

        }



        if (

            $new['room_id'] &&

            $existing->room_id == $new['room_id']

        ) {

            if (

                $new['subject_code'] === $existing->subject_code &&

                $new['start_time'] === $existing->start_time &&

                $new['end_time'] === $existing->end_time

            ) {

                return 'Exact duplicate of an existing schedule.';

            }



            return 'Room conflict: classroom is already occupied during this time.';

        }



        if (

            $new['instructor_id'] &&

            $existing->instructor_id == $new['instructor_id']

        ) {

            return 'Instructor conflict: instructor has another schedule during this time.';

        }



        return null;

    }



    private function compareRows(array $new, array $previous): ?string

    {

        if (

            !$this->overlaps(

                $new['start_time'],

                $new['end_time'],

                $previous['start_time'],

                $previous['end_time']

            ) ||

            !$this->sharesDay($new['day'], $previous['day'])

        ) {

            return null;

        }



        if (

            $new['room_id'] &&

            $previous['room_id'] &&

            $new['room_id'] == $previous['room_id']

        ) {

            return 'Room conflict with another row in this upload.';

        }



        if (

            $new['instructor_id'] &&

            $previous['instructor_id'] &&

            $new['instructor_id'] == $previous['instructor_id']

        ) {

            return 'Instructor conflict with another row in this upload.';

        }



        return null;

    }



    private function overlaps(

        ?string $startA,

        ?string $endA,

        ?string $startB,

        ?string $endB

    ): bool {

        if (!$startA || !$endA || !$startB || !$endB) {

            return false;

        }



        return $startA < $endB && $endA > $startB;

    }



    private function sharesDay(string $daysA, string $daysB): bool

    {

        $a = $this->dayTokens($daysA);

        $b = $this->dayTokens($daysB);



        return !empty(array_intersect($a, $b));

    }



    private function normalizeDays($value): string
    {
        $value = strtoupper(trim((string) $value));
        $value = preg_replace('/[\\s,\\/\\-]+/', '', $value);

        if ($value === '') {
            return '';
        }

        $tokens = [];
        $length = strlen($value);

        for ($i = 0; $i < $length;) {
            $pair = substr($value, $i, 2);

            if ($pair === 'TH') {
                $tokens[] = 'Th';
                $i += 2;
            } elseif ($pair === 'SU') {
                $tokens[] = 'Su';
                $i += 2;
            } elseif (in_array($value[$i], ['M', 'T', 'W', 'F', 'S'], true)) {
                $tokens[] = $value[$i];
                $i++;
            } else {
                // Reject unknown day characters instead of silently accepting them.
                return '';
            }
        }

        return implode('', array_unique($tokens));
    }

    private function dayTokens(string $days): array

    {

        preg_match_all('/Th|Su|[MTWFS]/i', $days, $matches);



        return array_unique($matches[0]);

    }



    private function convertTime($value): ?string

    {

        if ($value === null || trim((string) $value) === '') {

            return null;

        }



        try {

            if (is_numeric($value)) {

                $date = ExcelDate::excelToDateTimeObject((float) $value);



                return Carbon::instance($date)->format('H:i:s');

            }



            return Carbon::parse(trim((string) $value))->format('H:i:s');

        } catch (\Throwable $e) {

            return null;

        }

    }



    private function normalizeText($value): string

    {

        return strtolower(trim(preg_replace('/\s+/', ' ', (string) $value)));

    }



    private function numericValue($value): ?float

    {

        return is_numeric($value) ? (float) $value : null;

    }



    private function integerValue($value): ?int

    {

        return is_numeric($value) ? (int) $value : null;

    }

}
