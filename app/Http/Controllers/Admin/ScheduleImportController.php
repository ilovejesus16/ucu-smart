<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SchedulesImport;

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

        $scheduleCollection = collect($schedules);

        $total = $scheduleCollection->count();

        $new = $scheduleCollection
            ->where('status', 'new')
            ->count();

        $duplicates = $scheduleCollection
            ->where('status', 'duplicate')
            ->count();

        $invalid = $scheduleCollection
            ->where('status', 'invalid')
            ->count();

        /*
         * Load room IDs once instead of querying the database
         * separately for every preview row.
         */
        $roomIds = array_fill_keys(
            Room::pluck('id')
                ->map(fn ($id) => (string) $id)
                ->all(),
            true
        );

        $eligibleRows = $scheduleCollection->filter(function ($row) use ($roomIds) {
            $roomId = $row['room_id'] ?? null;

            return in_array(
                $row['status'] ?? 'invalid',
                ['new', 'duplicate'],
                true
            )
                && !empty($roomId)
                && isset($roomIds[(string) $roomId]);
        });

        $ready = $eligibleRows->count();

        $missingRooms = $scheduleCollection->filter(function ($row) {
            return in_array(
                $row['status'] ?? 'invalid',
                ['new', 'duplicate'],
                true
            )
                && empty($row['room_id']);
        })->count();

        $rooms = Room::with('building')
            ->orderBy('room_number')
            ->get();

        $instructors = User::where('role', 'instructor')
            ->where('status', 'active')
            ->orderBy('first_name')
            ->get();

        /*
         * Fetch existing schedules once for the selected semester
         * and school year.
         */
        $conflictSchedules = Schedule::where(
            'semester',
            $metadata['semester'] ?? null
        )
            ->where(
                'school_year',
                $metadata['school_year'] ?? null
            )
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

        /*
         * Validate basic input types first.
         * Actual room and instructor existence is checked against
         * preloaded ID sets below to avoid repeated database queries.
         */
        $request->validate([
            'rows' => 'sometimes|array',
            'rows.*.room_id' => 'nullable|integer',
            'rows.*.instructor_id' => 'nullable|integer',
            'rows.*.day' => 'nullable|string|max:30',
            'rows.*.start_time' => 'nullable|date_format:H:i:s,H:i',
            'rows.*.end_time' => 'nullable|date_format:H:i:s,H:i',
        ]);

        $editedRows = $request->input('rows', []);

        /*
         * Load valid room IDs and active instructor IDs once.
         */
        $roomIds = array_fill_keys(
            Room::pluck('id')
                ->map(fn ($id) => (string) $id)
                ->all(),
            true
        );

        $instructorIds = array_fill_keys(
            User::where('role', 'instructor')
                ->where('status', 'active')
                ->pluck('id')
                ->map(fn ($id) => (string) $id)
                ->all(),
            true
        );

        /*
         * Check submitted corrections against the preloaded IDs.
         */
        $unresolvedRooms = [];

        foreach ($schedules as $index => $originalRow) {
            if (!in_array(
                $originalRow['status'] ?? 'invalid',
                ['new', 'duplicate'],
                true
            )) {
                continue;
            }

            $submittedRoomId = $editedRows[$index]['room_id']
                ?? ($originalRow['room_id'] ?? null);

            if (
                empty($submittedRoomId) ||
                !isset($roomIds[(string) $submittedRoomId])
            ) {
                $unresolvedRooms[] = $index + 1;
            }

            if (
                isset($editedRows[$index]['instructor_id']) &&
                $editedRows[$index]['instructor_id'] !== '' &&
                !isset($instructorIds[(string) $editedRows[$index]['instructor_id']])
            ) {
                throw ValidationException::withMessages([
                    "rows.{$index}.instructor_id" =>
                        'The selected instructor is invalid or inactive.',
                ]);
            }
        }

        if (!empty($unresolvedRooms)) {
            return redirect()
                ->route('admin.schedules.preview')
                ->withErrors([
                    'rooms' =>
                        'Import blocked. Assign a valid classroom to every eligible row. Rows missing a room: '
                        . implode(', ', $unresolvedRooms),
                ]);
        }

        $metadata = session('schedule_import_metadata', []);

        $imported = 0;
        $skipped = 0;
        $conflictCount = 0;

        $admin = $request->user();
        $adminId = $admin?->id;
        $adminName = trim(($admin?->first_name ?? '') . ' ' . ($admin?->last_name ?? ''));

        DB::transaction(function () use (
            $schedules,
            $editedRows,
            $metadata,
            $roomIds,
            $instructorIds,
            $adminId,
            $adminName,
            &$imported,
            &$skipped,
            &$conflictCount
        ) {
            /*
             * Load existing schedules once.
             */
            $existingSchedules = Schedule::where(
                'semester',
                $metadata['semester'] ?? null
            )
                ->where(
                    'school_year',
                    $metadata['school_year'] ?? null
                )
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
                ]);

            /*
             * Build a conflict index for existing schedules.
             * This avoids comparing every imported row with every
             * existing schedule.
             */
            $conflictIndex = [];

            foreach ($existingSchedules as $key => $existing) {
                $this->addToConflictIndex(
                    $conflictIndex,
                    $existing,
                    'existing_' . $key
                );
            }

            foreach ($schedules as $index => $originalRow) {
                $row = $originalRow;

                /*
                 * Only new and duplicate rows are eligible for review.
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

                /*
                 * Apply admin corrections.
                 */
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

                /*
                 * Normalize HTML time inputs.
                 */
                foreach (['start_time', 'end_time'] as $field) {
                    if (!empty($row[$field])) {
                        $row[$field] = $this->normalizeTime(
                            $row[$field]
                        );
                    }
                }

                /*
                 * Check required information.
                 */
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

                /*
                 * Check time range.
                 */
                if ($row['end_time'] <= $row['start_time']) {
                    $skipped++;
                    continue;
                }

                /*
                 * Verify room using the preloaded ID set.
                 */
                if (!isset($roomIds[(string) $row['room_id']])) {
                    $skipped++;
                    continue;
                }

                /*
                 * Verify instructor using the preloaded ID set.
                 */
                if (
                    !empty($row['instructor_id']) &&
                    !isset($instructorIds[(string) $row['instructor_id']])
                ) {
                    $skipped++;
                    continue;
                }

                /*
                 * Recheck conflicts using the indexed schedules.
                 */
                if ($this->hasIndexedConflict($row, $conflictIndex)) {
                    $conflictCount++;

                    throw ValidationException::withMessages([
                        'rows' =>
                            'Import cancelled: a room or instructor conflict was detected during final validation. Review the preview and resolve every conflict before trying again.',
                    ]);
                }

                /*
                 * Save schedule.
                 * Keep Eloquent create() to preserve model events.
                 */
                Schedule::create([
                    'room_id' => $row['room_id'],
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

                /*
                 * Add the accepted schedule to the index so subsequent
                 * uploaded rows are checked against it.
                 */
                $this->addToConflictIndex(
                    $conflictIndex,
                    $row,
                    'imported_' . $index
                );

                $imported++;
            }

            // Record one aggregate activity for the completed bulk import.
            // This intentionally has no subject reference because it represents
            // a batch operation rather than one individual schedule record.
            if ($imported > 0) {
                ActivityLog::create([
                    'user_id' => $adminId,
                    'user_name' => $adminName !== '' ? $adminName : 'Unknown User',
                    'action' => 'imported',
                    'module' => 'Schedules',
                    'description' => sprintf(
                        '%d schedules imported for %s, %s. %d records skipped.',
                        $imported,
                        $metadata['semester'] ?? 'Unknown semester',
                        $metadata['school_year'] ?? 'Unknown school year',
                        $skipped
                    ),
                ]);
            }
        });

        /*
         * Clear preview only after a successful transaction.
         */
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
     * Add a schedule to the conflict index.
     */
    private function addToConflictIndex(
        array &$index,
        array $row,
        string $key
    ): void {
        $days = $this->dayTokens($row['day'] ?? '');

        foreach ($days as $day) {
            if (!empty($row['room_id'])) {
                $resourceKey = 'room:' . $row['room_id'];

                $index[$day][$resourceKey][$key] = $row;
            }

            if (!empty($row['instructor_id'])) {
                $resourceKey = 'instructor:' . $row['instructor_id'];

                $index[$day][$resourceKey][$key] = $row;
            }
        }
    }

    /**
     * Check conflicts only against schedules sharing the same day
     * and either the same room or the same instructor.
     */
    private function hasIndexedConflict(
        array $row,
        array $index
    ): bool {
        $candidates = [];

        $days = $this->dayTokens($row['day'] ?? '');

        foreach ($days as $day) {
            if (!isset($index[$day])) {
                continue;
            }

            if (!empty($row['room_id'])) {
                $resourceKey = 'room:' . $row['room_id'];

                foreach ($index[$day][$resourceKey] ?? [] as $key => $candidate) {
                    $candidates[$key] = $candidate;
                }
            }

            if (!empty($row['instructor_id'])) {
                $resourceKey = 'instructor:' . $row['instructor_id'];

                foreach ($index[$day][$resourceKey] ?? [] as $key => $candidate) {
                    $candidates[$key] = $candidate;
                }
            }
        }

        foreach ($candidates as $candidate) {
            if ($this->hasConflict($row, $candidate)) {
                return true;
            }
        }

        return false;
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
     */
    private function sharesDay(string $daysA, string $daysB): bool
    {
        $tokensA = $this->dayTokens($daysA);
        $tokensB = $this->dayTokens($daysB);

        return !empty(array_intersect($tokensA, $tokensB));
    }

    /**
     * Extract normalized day tokens.
     * Supports Th and Su as two-letter tokens.
     */
    private function dayTokens(string $days): array
    {
        preg_match_all('/Th|Su|[MTWFS]/i', $days, $matches);

        return array_values(array_unique(array_map(
            fn ($day) => strtoupper($day),
            $matches[0]
        )));
    }
}
