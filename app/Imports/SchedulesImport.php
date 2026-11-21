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

    protected array $roomCache = [];
    protected array $instructorCache = [];

    protected array $existingConflictIndex = [];
    protected array $uploadedConflictIndex = [];

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
        /*
         * Load reference data once.
         */

        $rooms = Room::all();

        foreach ($rooms as $room) {
            $number = $this->normalizeText($room->room_number);
            $name = $this->normalizeText($room->room_name);

            if ($number !== '' && !isset($this->roomCache[$number])) {
                $this->roomCache[$number] = $room;
            }

            if ($name !== '' && !isset($this->roomCache[$name])) {
                $this->roomCache[$name] = $room;
            }
        }

        $instructors = User::query()
            ->where('role', 'instructor')
            ->where('status', 'active')
            ->whereNotNull('employee_id')
            ->get([
                'id',
                'employee_id',
                'first_name',
                'last_name',
            ]);

        foreach ($instructors as $instructor) {
            $employeeId = trim((string) $instructor->employee_id);

            if ($employeeId !== '') {
                $this->instructorCache[$employeeId] = $instructor;
            }
        }

        /*
         * Load existing schedules once for this semester and school year.
         */

        $existingSchedules = Schedule::query()
            ->where('semester', $this->semester)
            ->where('school_year', $this->schoolYear)
            ->get([
                'id',
                'room_id',
                'instructor_id',
                'subject_code',
                'day',
                'start_time',
                'end_time',
            ]);

        foreach ($existingSchedules as $existing) {
            $this->addToConflictIndex(
                $this->existingConflictIndex,
                [
                    'id' => $existing->id,
                    'room_id' => $existing->room_id,
                    'instructor_id' => $existing->instructor_id,
                    'subject_code' => $existing->subject_code,
                    'day' => (string) $existing->day,
                    'start_time' => $this->formatTime($existing->start_time),
                    'end_time' => $this->formatTime($existing->end_time),
                ]
            );
        }

        /*
         * Process imported rows.
         */

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
                ? ($this->instructorCache[$employeeId] ?? null)
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

            /*
             * Required information validation.
             */

            if ($code === '' || $subject === '' || $day === '' || !$start || !$end) {
                $record['status'] = 'invalid';
                $record['remarks'][] =
                    'Missing subject code, subject title, day, or valid time.';
            }

            if ($start && $end && $end <= $start) {
                $record['status'] = 'invalid';
                $record['remarks'][] =
                    'End time must be later than start time.';
            }

            if (!$this->semester || !$this->schoolYear) {
                $record['status'] = 'invalid';
                $record['remarks'][] =
                    'Semester or school year is missing.';
            }

            /*
             * Room and instructor warnings.
             */

            if ($classroom !== '' && !$room) {
                $record['remarks'][] =
                    "Classroom '{$classroom}' was not found.";
            }

            if ($employeeId !== '' && !$instructor) {
                $record['remarks'][] =
                    "Instructor ID '{$employeeId}' was not found or is inactive.";
            }

            if ($classroom === '') {
                $record['remarks'][] = 'No classroom assigned.';
            }

            if ($employeeId === '') {
                $record['remarks'][] = 'No instructor assigned.';
            }

            /*
             * Check conflicts using the indexed candidates.
             */

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

            $record['remarks'] = implode(' ', array_unique($record['remarks']));

            $this->schedules[] = $record;

            /*
             * Preserve the old behavior:
             * Invalid rows are not considered in later conflict checks.
             * Duplicate rows are still considered.
             */

            if ($record['status'] !== 'invalid') {
                $this->addToConflictIndex(
                    $this->uploadedConflictIndex,
                    $record
                );
            }
        }
    }

    private function findRoom(string $classroom): ?Room
    {
        $normalized = $this->normalizeText($classroom);

        return $this->roomCache[$normalized] ?? null;
    }

    private function findConflicts(array $record): array
    {
        $messages = [];

        /*
         * Check existing database schedules.
         */

        $existingCandidates = $this->getConflictCandidates(
            $this->existingConflictIndex,
            $record
        );

        foreach ($existingCandidates as $existing) {
            $result = $this->compareSchedules($record, $existing);

            if ($result) {
                $messages[] = $result;
            }
        }

        /*
         * Check previously processed uploaded rows.
         */

        $uploadedCandidates = $this->getConflictCandidates(
            $this->uploadedConflictIndex,
            $record
        );

        foreach ($uploadedCandidates as $previous) {
            $result = $this->compareRows($record, $previous);

            if ($result) {
                $messages[] = $result;
            }
        }

        return array_values(array_unique($messages));
    }

    private function addToConflictIndex(
        array &$index,
        array $record
    ): void {
        $days = $this->dayTokens((string) ($record['day'] ?? ''));

        foreach ($days as $day) {
            $day = strtoupper($day);

            if (!empty($record['room_id'])) {
                $key = (string) $record['room_id'];

                $index[$day]['room'][$key][] = $record;
            }

            if (!empty($record['instructor_id'])) {
                $key = (string) $record['instructor_id'];

                $index[$day]['instructor'][$key][] = $record;
            }
        }
    }

    private function getConflictCandidates(
        array $index,
        array $record
    ): array {
        $candidates = [];

        $days = $this->dayTokens((string) ($record['day'] ?? ''));

        foreach ($days as $day) {
            $day = strtoupper($day);

            if (!empty($record['room_id'])) {
                $key = (string) $record['room_id'];

                foreach ($index[$day]['room'][$key] ?? [] as $candidate) {
                    $uniqueKey = $this->candidateKey($candidate);

                    $candidates[$uniqueKey] = $candidate;
                }
            }

            if (!empty($record['instructor_id'])) {
                $key = (string) $record['instructor_id'];

                foreach ($index[$day]['instructor'][$key] ?? [] as $candidate) {
                    $uniqueKey = $this->candidateKey($candidate);

                    $candidates[$uniqueKey] = $candidate;
                }
            }
        }

        return array_values($candidates);
    }

    private function candidateKey(array $record): string
    {
        if (isset($record['id'])) {
            return 'id:' . $record['id'];
        }

        return 'row:' . md5(serialize([
            $record['room_id'] ?? null,
            $record['instructor_id'] ?? null,
            $record['subject_code'] ?? null,
            $record['day'] ?? null,
            $record['start_time'] ?? null,
            $record['end_time'] ?? null,
        ]));
    }

    private function compareSchedules(
        array $new,
        array $existing
    ): ?string {
        $sameTime = $this->overlaps(
            $new['start_time'],
            $new['end_time'],
            $existing['start_time'],
            $existing['end_time']
        );

        if (!$sameTime || !$this->sharesDay($new['day'], $existing['day'])) {
            return null;
        }

        if (
            $new['room_id'] &&
            $existing['room_id'] == $new['room_id']
        ) {
            if (
                $new['subject_code'] === $existing['subject_code'] &&
                $new['start_time'] === $existing['start_time'] &&
                $new['end_time'] === $existing['end_time']
            ) {
                return 'Exact duplicate of an existing schedule.';
            }

            return 'Room conflict: classroom is already occupied during this time.';
        }

        if (
            $new['instructor_id'] &&
            $existing['instructor_id'] == $new['instructor_id']
        ) {
            return 'Instructor conflict: instructor has another schedule during this time.';
        }

        return null;
    }

    private function compareRows(
        array $new,
        array $previous
    ): ?string {
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

    private function sharesDay(
        string $daysA,
        string $daysB
    ): bool {
        $a = $this->dayTokens($daysA);
        $b = $this->dayTokens($daysB);

        return !empty(array_intersect($a, $b));
    }

    private function normalizeDays($value): string
    {
        $value = strtoupper(trim((string) $value));
        $value = preg_replace('/[\s,\/\-]+/', '', $value);

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
                return '';
            }
        }

        return implode('', array_unique($tokens));
    }

    private function dayTokens(string $days): array
    {
        preg_match_all('/Th|Su|[MTWFS]/i', $days, $matches);

        return array_values(array_unique($matches[0]));
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

    private function formatTime($value): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('H:i:s');
        }

        return Carbon::parse((string) $value)->format('H:i:s');
    }

    private function normalizeText($value): string
    {
        return strtolower(
            trim(preg_replace('/\s+/', ' ', (string) $value))
        );
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
