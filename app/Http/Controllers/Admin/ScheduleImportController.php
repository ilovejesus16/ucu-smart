<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\SchedulesImport;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class ScheduleImportController extends Controller
{
    /**
     * Upload and process the Registrar's schedule file.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'semester' => [
                'required',
                Rule::in(['1st Semester', '2nd Semester']),
            ],
            'school_year' => 'required|string|max:20',
        ]);

        $import = new SchedulesImport(
            $request->semester,
            $request->school_year
        );

        Excel::import($import, $request->file('file'));

        $schedules = $import->schedules;

        if (empty($schedules)) {
            return back()->withErrors([
                'file' => 'The uploaded file contains no schedule records.',
            ]);
        }

        session([
            'schedule_import_preview' => $schedules,

            'schedule_import_metadata' => [
                'semester' => $request->semester,
                'school_year' => $request->school_year,
            ],
        ]);

        return redirect()
            ->route('admin.schedules.preview')
            ->with(
                'success',
                'Schedule file processed successfully. Review the records before importing.'
            );
    }

    /**
     * Display schedule import preview.
     */
    public function preview()
    {
        $schedules = session('schedule_import_preview');

        if (empty($schedules)) {
            return redirect()
                ->route('admin.schedules')
                ->with(
                    'error',
                    'No schedule import preview was found. Please upload a file first.'
                );
        }

        $metadata = session('schedule_import_metadata', []);

        $total = count($schedules);

        $new = collect($schedules)
            ->where('status', 'new')
            ->count();

        $duplicates = collect($schedules)
            ->where('status', 'duplicate')
            ->count();

        $invalid = collect($schedules)
            ->where('status', 'invalid')
            ->count();

        // New and duplicate rows can be reviewed and checked again.
        $eligibleRows = collect($schedules)->filter(function ($row) {
            return in_array($row['status'] ?? 'invalid', ['new', 'duplicate'], true)
                && !empty($row['room_id'])
                && Room::whereKey($row['room_id'])->exists();
        });

        $ready = $eligibleRows->count();

        $missingRooms = collect($schedules)->filter(function ($row) {
            return in_array($row['status'] ?? 'invalid', ['new', 'duplicate'], true)
                && empty($row['room_id']);
        })->count();

        $rooms = Room::with('building')
            ->orderBy('room_number')
            ->get();

        $instructors = User::where('role', 'instructor')
            ->where('status', 'active')
            ->orderBy('first_name')
            ->get();

        // Existing schedules are sent to the preview so the browser can
        // immediately detect room/instructor overlaps against saved records.
        $conflictSchedules = Schedule::where('semester', $metadata['semester'] ?? null)
            ->where('school_year', $metadata['school_year'] ?? null)
            ->get([
                'room_id',
                'instructor_id',
                'day',
                'start_time',
                'end_time',
            ])
            ->map(fn ($schedule) => [
                'room_id' => $schedule->room_id,
                'instructor_id' => $schedule->instructor_id,
                'day' => $schedule->day,
                'start_time' => (string) $schedule->start_time,
                'end_time' => (string) $schedule->end_time,
            ])
            ->values()
            ->all();

        return view('admin.schedules.import-preview', compact(
            'schedules',
            'total',
            'new',
            'duplicates',
            'invalid',
            'ready',
            'missingRooms',
            'rooms',
            'instructors',
            'conflictSchedules'
        ));
    }

    /**
     * Save reviewed and corrected schedules.
     */
    public function storeImportedSchedules(Request $request)
    {
        $schedules = session('schedule_import_preview');

        if (empty($schedules)) {
            return redirect()
                ->route('admin.schedules')
                ->with(
                    'error',
                    'Import preview expired. Please upload the file again.'
                );
        }

        $request->validate([
            'rows' => 'sometimes|array',

            'rows.*.room_id' => [
                'nullable',
                'integer',
                'exists:rooms,id',
            ],

            'rows.*.instructor_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('role', 'instructor')
                        ->where('status', 'active');
                }),
            ],

            'rows.*.day' => 'nullable|string|max:30',

            'rows.*.start_time' => 'nullable|date_format:H:i:s,H:i',

            'rows.*.end_time' => 'nullable|date_format:H:i:s,H:i',
        ]);

        $editedRows = $request->input('rows', []);

        // Merge submitted room edits and block confirmation while any otherwise
        // importable row still has no valid room assignment.
        $unresolvedRooms = [];
        foreach ($schedules as $index => $originalRow) {
            if (!in_array($originalRow['status'] ?? 'invalid', ['new', 'duplicate'], true)) {
                continue;
            }

            $submittedRoomId = $editedRows[$index]['room_id'] ?? ($originalRow['room_id'] ?? null);

            if (empty($submittedRoomId) || !Room::whereKey($submittedRoomId)->exists()) {
                $unresolvedRooms[] = $index + 1;
            }
        }

        if (!empty($unresolvedRooms)) {
            return redirect()
                ->route('admin.schedules.preview')
                ->withErrors([
                    'rooms' => 'Import blocked. Assign a valid classroom to every eligible row. Rows missing a room: '
                        . implode(', ', $unresolvedRooms),
                ]);
        }

        $metadata = session('schedule_import_metadata', []);

        $imported = 0;
        $skipped = 0;
        $conflictCount = 0;

        DB::transaction(function () use (
            $schedules,
            $editedRows,
            $metadata,
            &$imported,
            &$skipped,
            &$conflictCount
        ) {
            // Load existing schedules for this semester and school year.
            $existingSchedules = Schedule::where(
                'semester',
                $metadata['semester'] ?? null
            )
                ->where(
                    'school_year',
                    $metadata['school_year'] ?? null
                )
                ->get();

            // Keep track of schedules accepted in this import.
            $acceptedRows = [];

            foreach ($schedules as $index => $originalRow) {

                $row = $originalRow;

                /*
                 * Allow both new and duplicate rows to be reviewed.
                 * Invalid rows remain skipped.
                 */
                if (!in_array(
                    $row['status'] ?? 'invalid',
                    ['new', 'duplicate'],
                    true
                )) {
                    $skipped++;
                    continue;
                }

                // Apply user edits from the preview page.
                if (isset($editedRows[$index])) {

                    $edits = $editedRows[$index];

                    foreach ([
                        'room_id',
                        'instructor_id',
                        'day',
                        'start_time',
                        'end_time',
                    ] as $field) {

                        if (array_key_exists($field, $edits)) {
                            $row[$field] = $edits[$field] === ''
                                ? null
                                : $edits[$field];
                        }
                    }
                }

                // Normalize HTML time inputs to H:i:s.
                foreach (['start_time', 'end_time'] as $field) {

                    if (!empty($row[$field])) {
                        $row[$field] = $this->normalizeTime(
                            $row[$field]
                        );
                    }
                }

                // Check required information.
                if (
                    empty($row['subject_code']) ||
                    empty($row['subject_name']) ||
                    empty($row['room_id']) ||
                    empty($row['day']) ||
                    empty($row['start_time']) ||
                    empty($row['end_time']) ||
                    empty($row['semester']) ||
                    empty($row['school_year'])
                ) {
                    $skipped++;
                    continue;
                }

                // Check time range.
                if ($row['end_time'] <= $row['start_time']) {
                    $skipped++;
                    continue;
                }

                // A valid classroom is mandatory for every imported schedule.
                if (empty($row['room_id']) || !Room::whereKey($row['room_id'])->exists()) {
                    $skipped++;
                    continue;
                }

                // Verify selected instructor.
                if (!empty($row['instructor_id'])) {

                    $validInstructor = User::whereKey(
                        $row['instructor_id']
                    )
                        ->where('role', 'instructor')
                        ->where('status', 'active')
                        ->exists();

                    if (!$validInstructor) {
                        $skipped++;
                        continue;
                    }
                }

                /*
                 * Recheck conflicts against existing database schedules.
                 */
                $hasConflict = false;

                foreach ($existingSchedules as $existing) {

                    if ($this->hasConflict($row, [
                        'room_id' => $existing->room_id,
                        'instructor_id' => $existing->instructor_id,
                        'day' => $existing->day,
                        'start_time' => $existing->start_time,
                        'end_time' => $existing->end_time,
                    ])) {
                        $hasConflict = true;
                        break;
                    }
                }

                /*
                 * Recheck conflicts against previously accepted upload rows.
                 */
                if (!$hasConflict) {

                    foreach ($acceptedRows as $accepted) {

                        if ($this->hasConflict($row, $accepted)) {
                            $hasConflict = true;
                            break;
                        }
                    }
                }

                // Abort the entire import if any conflict remains. This prevents
                // a partial import where some rows are saved and others silently skipped.
                if ($hasConflict) {
                    $conflictCount++;

                    throw ValidationException::withMessages([
                        'rows' => 'Import cancelled: a room or instructor conflict was detected during final validation. Review the preview and resolve every conflict before trying again.',
                    ]);
                }

                // Save schedule.
                Schedule::create([
                    'room_id' => $row['room_id'] ?: null,
                    'instructor_id' => $row['instructor_id'] ?: null,

                    'subject_code' => $row['subject_code'],
                    'section_name' => $row['section_name'] ?? null,
                    'subject_name' => $row['subject_name'],

                    'units' => $row['units'] ?? null,
                    'enrolled' => $row['enrolled'] ?? null,

                    'day' => $row['day'],
                    'start_time' => $row['start_time'],
                    'end_time' => $row['end_time'],

                    'semester' => $row['semester'],
                    'school_year' => $row['school_year'],

                    'status' => 'scheduled',
                ]);

                // Add successfully imported row to conflict checking.
                $acceptedRows[] = [
                    'room_id' => $row['room_id'],
                    'instructor_id' => $row['instructor_id'],
                    'day' => $row['day'],
                    'start_time' => $row['start_time'],
                    'end_time' => $row['end_time'],
                ];

                $imported++;
            }
        });

        // Clear import preview after successful transaction.
        session()->forget([
            'schedule_import_preview',
            'schedule_import_metadata',
        ]);

        return redirect()
            ->route('admin.schedules')
            ->with(
                'success',
                "{$imported} schedules imported successfully. "
                . "{$skipped} records skipped. "
                . "{$conflictCount} additional conflicts detected during confirmation."
            );
    }

    /**
     * Cancel current import.
     */
    public function cancel()
    {
        session()->forget([
            'schedule_import_preview',
            'schedule_import_metadata',
        ]);

        return redirect()
            ->route('admin.schedules')
            ->with('success', 'Schedule import cancelled.');
    }

    /**
     * Normalize time to H:i:s.
     */
    private function normalizeTime(string $time): string
    {
        $time = trim($time);

        if (strlen($time) === 5) {
            $time .= ':00';
        }

        return $time;
    }

    /**
     * Check schedule conflicts based on shared room or instructor,
     * overlapping times, and matching days.
     */
    private function hasConflict(array $new, array $existing): bool
    {
        if (
            empty($new['start_time']) ||
            empty($new['end_time']) ||
            empty($existing['start_time']) ||
            empty($existing['end_time'])
        ) {
            return false;
        }

        $timeOverlap =
            $new['start_time'] < $existing['end_time'] &&
            $new['end_time'] > $existing['start_time'];

        if (!$timeOverlap) {
            return false;
        }

        if (!$this->sharesDay(
            $new['day'] ?? '',
            $existing['day'] ?? ''
        )) {
            return false;
        }

        $sameRoom =
            !empty($new['room_id']) &&
            !empty($existing['room_id']) &&
            (string) $new['room_id'] === (string) $existing['room_id'];

        $sameInstructor =
            !empty($new['instructor_id']) &&
            !empty($existing['instructor_id']) &&
            (string) $new['instructor_id'] === (string) $existing['instructor_id'];

        return $sameRoom || $sameInstructor;
    }

    /**
     * Check whether two schedules share a day.
     * Supports Th and Su as two-letter tokens.
     */
    private function sharesDay(string $daysA, string $daysB): bool
    {
        $tokensA = $this->dayTokens($daysA);
        $tokensB = $this->dayTokens($daysB);

        return !empty(array_intersect($tokensA, $tokensB));
    }

    /**
     * Extract day tokens.
     */
    private function dayTokens(string $days): array
    {
        preg_match_all('/Th|Su|[MTWFS]/i', $days, $matches);

        return array_map(
            fn ($day) => strtoupper($day),
            $matches[0]
        );
    }
}
