

@extends('layouts.admin')



@section('title', 'Import Schedule Preview')



@section('content')



@php

    $metadata = session('schedule_import_metadata', []);

@endphp



<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">



    {{-- Header --}}

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">

                Import Schedules

            </h1>



            <p class="mt-1 text-slate-500">

                Review and edit the Registrar's schedules before importing them.

            </p>

        </div>



        <a href="{{ route('admin.schedules') }}"

           class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-medium text-slate-700 hover:bg-slate-100 transition">

            ← Back to Schedules

        </a>

    </div>



    {{-- Import Information --}}

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6">



        <h2 class="font-bold text-slate-800 mb-4">

            Import Information

        </h2>



        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">



            <div>

                <p class="text-xs text-slate-500">Semester</p>

                <p class="font-semibold text-slate-800">

                    {{ $metadata['semester'] ?? '—' }}

                </p>

            </div>



            <div>

                <p class="text-xs text-slate-500">School Year</p>

                <p class="font-semibold text-slate-800">

                    {{ $metadata['school_year'] ?? '—' }}

                </p>

            </div>



        </div>

    </div>



    {{-- Summary Cards --}}

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">



        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

            <p class="text-sm text-slate-500">Total Rows</p>

           <p id="summary-total-count" class="mt-2 text-3xl font-bold text-slate-800">
    {{ $total }}
</p>

        </div>



        <div class="bg-white rounded-2xl border border-green-200 shadow-sm p-6">

            <p class="text-sm text-slate-500">New Records</p>

            <p id="summary-new-count" class="mt-2 text-3xl font-bold text-green-600">
    {{ $new }}
</p>

        </div>



        <div class="bg-white rounded-2xl border border-yellow-200 shadow-sm p-6">

            <p class="text-sm text-slate-500">Duplicates / Conflicts</p>

           <p id="summary-conflict-count" class="mt-2 text-3xl font-bold text-yellow-600">
    {{ $duplicates }}
</p>

        </div>



        <div class="bg-white rounded-2xl border border-red-200 shadow-sm p-6">

            <p class="text-sm text-slate-500">Invalid</p>

            <p id="summary-invalid-count" class="mt-2 text-3xl font-bold text-red-600">
    {{ $invalid }}
</p>

        </div>



    </div>



    {{-- Instructions --}}

    <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 mb-6">



        <div class="flex gap-3">



            <span class="text-blue-600 text-xl">ⓘ</span>



            <div>

                <p class="font-semibold text-blue-800">

                    Review before importing

                </p>



                <p class="text-sm text-blue-700 mt-1">
                    Assign a valid classroom to every eligible record before importing.
                    Rows with missing or unmatched classrooms will show a warning and block confirmation.
                    Conflicting records will be checked again when you confirm the import.
                    Invalid records will be skipped. Instructor assignments may remain blank.
                </p>

            </div>



        </div>

    </div>



    {{-- Preview Table --}}

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">



        <div class="px-6 py-5 border-b border-slate-200">

            <h2 class="text-xl font-bold text-slate-800">

                Schedule Import Preview

            </h2>



            <p class="mt-1 text-sm text-slate-500">

                Review the Registrar's records and make corrections before confirmation.

            </p>

        </div>



        {{-- Validation Errors --}}

        @if ($errors->any())

            <div class="mx-6 mt-5 rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">



                <p class="font-semibold mb-1">

                    Please check the following:

                </p>



                <ul class="list-disc pl-5">

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>



            </div>

        @endif



        <form method="POST" action="{{ route('admin.schedules.store') }}">

            @csrf



            <div class="overflow-x-auto">



                <table class="w-full text-sm">



                    <thead class="bg-[#0E4C6B] text-white">

                        <tr>

                            <th class="px-4 py-4 text-left">#</th>

                            <th class="px-4 py-4 text-left">Status</th>

                            <th class="px-4 py-4 text-left">Subject Code</th>

                            <th class="px-4 py-4 text-left">Section</th>

                            <th class="px-4 py-4 text-left">Subject Title</th>

                            <th class="px-4 py-4 text-left">Units</th>

                            <th class="px-4 py-4 text-left">Instructor</th>

                            <th class="px-4 py-4 text-left">Classroom</th>

                            <th class="px-4 py-4 text-left">Days</th>

                            <th class="px-4 py-4 text-left">Start</th>

                            <th class="px-4 py-4 text-left">End</th>

                            <th class="px-4 py-4 text-left">Enrolled</th>

                            <th class="px-4 py-4 text-left">Remarks</th>

                        </tr>

                    </thead>



                    <tbody class="divide-y divide-slate-200">



                        @forelse ($schedules as $index => $schedule)



                            <tr class="hover:bg-slate-50 align-top">



                                {{-- Row Number --}}

                                <td class="px-4 py-4 text-slate-500">

                                    {{ $index + 1 }}

                                </td>



                                {{-- Status --}}

                                <td class="px-4 py-4 whitespace-nowrap">



                                    @if (in_array($schedule['status'], ['new', 'duplicate'], true) && empty($schedule['room_id']))

                                        <span data-row-status="{{ $index }}" class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                            Missing Room
                                        </span>

                                    @elseif ($schedule['status'] === 'new')

                                        <span data-row-status="{{ $index }}" class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            Ready
                                        </span>

                                    @elseif ($schedule['status'] === 'duplicate')



                                        <span data-row-status="{{ $index }}" class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">

                                            Conflict

                                        </span>



                                    @else



                                        <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">

                                            Invalid

                                        </span>



                                    @endif



                                </td>



                                {{-- Subject Code --}}

                                <td class="px-4 py-4 font-semibold text-slate-800 whitespace-nowrap">

                                    {{ $schedule['subject_code'] ?: '—' }}

                                </td>



                                {{-- Section --}}

                                <td class="px-4 py-4 text-slate-700">

                                    {{ $schedule['section_name'] ?: '—' }}

                                </td>



                                {{-- Subject Title --}}

                                <td class="px-4 py-4 text-slate-700 min-w-48">

                                    {{ $schedule['subject_name'] ?: '—' }}

                                </td>



                                {{-- Units --}}

                                <td class="px-4 py-4 text-slate-700">

                                    {{ $schedule['units'] ?? '—' }}

                                </td>



                                {{-- Instructor --}}

                                <td class="px-4 py-4 min-w-56">



                                    @if (in_array($schedule['status'], ['new', 'duplicate'], true))



                                        <select

                                            name="rows[{{ $index }}][instructor_id]"

                                            class="w-52 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">



                                            <option value="">Unassigned</option>



                                            @foreach ($instructors as $instructor)



                                                <option

                                                    value="{{ $instructor->id }}"

                                                    @selected((string) ($schedule['instructor_id'] ?? '') === (string) $instructor->id)>



                                                    {{ $instructor->first_name }}

                                                    {{ $instructor->last_name }}

                                                    ({{ $instructor->employee_id }})



                                                </option>



                                            @endforeach



                                        </select>



                                    @else



                                        <span class="text-slate-700">

                                            {{ $schedule['instructor_name'] ?: '—' }}

                                        </span>



                                    @endif



                                </td>



                                {{-- Classroom --}}

                                <td class="px-4 py-4 min-w-56">



                                    @if (in_array($schedule['status'], ['new', 'duplicate'], true))



                                        <select

                                            name="rows[{{ $index }}][room_id]"

                                                                                        data-room-select
class="w-52 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">



                                            <option value="">Unassigned</option>



                                            @foreach ($rooms as $room)



                                                <option

                                                    value="{{ $room->id }}"

                                                    @selected((string) ($schedule['room_id'] ?? '') === (string) $room->id)>



                                                    {{ $room->room_number }}

                                                    — {{ $room->building->building_name ?? 'Building' }}



                                                </option>



                                            @endforeach



                                        </select>



                                    @else



                                        <span class="text-slate-700">

                                            {{ $schedule['room_input'] ?: '—' }}

                                        </span>



                                    @endif



                                </td>



                                {{-- Days --}}

                                <td class="px-4 py-4 min-w-32">



                                    @if (in_array($schedule['status'], ['new', 'duplicate'], true))



                                        <input

                                            type="text"

                                            name="rows[{{ $index }}][day]"

                                            value="{{ $schedule['day'] }}"

                                            placeholder="MWF"

                                            class="w-24 rounded-lg border border-slate-300 px-3 py-2 text-sm uppercase focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">



                                    @else



                                        {{ $schedule['day'] ?: '—' }}



                                    @endif



                                </td>



                                {{-- Start Time --}}

                                <td class="px-4 py-4 min-w-36">



                                    @if (in_array($schedule['status'], ['new', 'duplicate'], true))



                                        <input

                                            type="time"

                                            step="1"

                                            name="rows[{{ $index }}][start_time]"

                                            value="{{ $schedule['start_time'] ? substr($schedule['start_time'], 0, 8) : '' }}"

                                            class="w-32 rounded-lg border border-slate-300 px-2 py-2 text-sm focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">



                                    @else



                                        {{ $schedule['start_time'] ? \Carbon\Carbon::parse($schedule['start_time'])->format('g:i A') : '—' }}



                                    @endif



                                </td>



                                {{-- End Time --}}

                                <td class="px-4 py-4 min-w-36">



                                    @if (in_array($schedule['status'], ['new', 'duplicate'], true))



                                        <input

                                            type="time"

                                            step="1"

                                            name="rows[{{ $index }}][end_time]"

                                            value="{{ $schedule['end_time'] ? substr($schedule['end_time'], 0, 8) : '' }}"

                                            class="w-32 rounded-lg border border-slate-300 px-2 py-2 text-sm focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">



                                    @else



                                        {{ $schedule['end_time'] ? \Carbon\Carbon::parse($schedule['end_time'])->format('g:i A') : '—' }}



                                    @endif



                                </td>



                                {{-- Enrolled --}}

                                <td class="px-4 py-4 text-slate-700">

                                    {{ $schedule['enrolled'] ?? '—' }}

                                </td>



                                {{-- Remarks --}}

                                <td class="px-4 py-4 min-w-64">

                                    <span
                                        data-row-remarks
                                        data-original-remarks="{{ $schedule['remarks'] ?? '' }}"
                                        class="text-xs {{ !empty($schedule['remarks']) ? 'text-slate-600' : 'text-green-600' }}"
                                    >{{ !empty($schedule['remarks']) ? $schedule['remarks'] : 'No issues detected.' }}</span>

                                </td>



                            </tr>



                        @empty



                            <tr>

                                <td colspan="13" class="px-6 py-12 text-center text-slate-500">

                                    No schedules found in the uploaded file.

                                </td>

                            </tr>



                        @endforelse



                    </tbody>



                </table>



            </div>



            {{-- Bottom Actions --}}

            <div class="border-t border-slate-200 px-6 py-5">



                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">



                    <div class="text-sm text-slate-600">



                        <span id="eligible-count" class="font-semibold text-green-600">

                            {{ $ready }}

                        </span>
                        record(s) currently eligible for import.

                        <p class="text-xs text-slate-400 mt-1">
                            <span id="missing-room-count">{{ $missingRooms }}</span> record(s) still need a classroom. Assign all missing classrooms before confirming.
                        </p>
                        <p id="missing-room-warning" class="text-xs font-semibold text-red-600 mt-1 {{ $missingRooms === 0 ? 'hidden' : '' }}">
                            Import is blocked until every eligible record has a valid classroom.
                        </p>



                    </div>



                    <div class="flex flex-wrap items-center gap-3">



                        {{-- Cancel --}}

                        <button

                            type="submit"

                            form="cancel-import-form"

                            class="rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-medium text-slate-700 hover:bg-slate-100 transition">



                            Cancel Import



                        </button>



                        {{-- Confirm --}}

                        <button

                            type="submit"

                            @disabled($ready === 0 || $missingRooms > 0)

                           

                            id="confirm-import-button" class="rounded-xl bg-[#0E4C6B] px-6 py-3 text-sm font-semibold text-white hover:bg-[#0B3D56] transition disabled:cursor-not-allowed disabled:opacity-50">



                            Confirm Import ({{ $ready }})



                        </button>



                    </div>



                </div>



            </div>



        </form>



        {{-- Separate Cancel Form --}}

        <form

            id="cancel-import-form"

            method="POST"

            action="{{ route('admin.schedules.cancel') }}"

            class="hidden">



            @csrf



        </form>



    </div>



</div>




<script>
document.addEventListener('DOMContentLoaded', function () {

    // Find the form through the Confirm Import button.
    const confirmButton = document.getElementById('confirm-import-button');
    const form = confirmButton ? confirmButton.closest('form') : null;

    if (!form) {
        console.error('Schedule import form not found.');
        return;
    }
    form.addEventListener('submit', function (event) {
    event.preventDefault();

    Swal.fire({
        title: 'Import Schedules?',
        text: 'Are you sure you want to import all eligible schedules?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0E4C6B',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Import Schedules',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then(function (result) {
        if (result.isConfirmed) {
            HTMLFormElement.prototype.submit.call(form);
        }
    });
});

    const eligibleCount = document.getElementById('eligible-count');
    const missingCount = document.getElementById('missing-room-count');
    const warning = document.getElementById('missing-room-warning');

    const summaryNewCount = document.getElementById('summary-new-count');
    const summaryConflictCount = document.getElementById('summary-conflict-count');
    const summaryInvalidCount = document.getElementById('summary-invalid-count');

    const baseInvalidCount = Number({{ (int) $invalid }});
    const savedSchedules = @json($conflictSchedules);

    const rows = Array.from(form.querySelectorAll('tbody tr')).filter(row => {
        return row.querySelector('[name$="[room_id]"]');
    });

    function field(row, name) {
        return row.querySelector(`[name$="[${name}]"]`);
    }

    function parseDays(value) {
        const cleaned = String(value || '').toUpperCase().replace(/[\s,\/-]+/g, '');
        const tokens = [];
        let i = 0;

        while (i < cleaned.length) {
            const pair = cleaned.substring(i, i + 2);

            if (pair === 'TH' || pair === 'SU') {
                tokens.push(pair);
                i += 2;
            } else if ('MTWFS'.includes(cleaned[i])) {
                tokens.push(cleaned[i]);
                i++;
            } else {
                return { tokens: [], valid: false };
            }
        }

        return {
            tokens: [...new Set(tokens)],
            valid: tokens.length > 0
        };
    }

    function toSeconds(value) {
        if (!value) return null;

        const parts = value.split(':').map(Number);

        if (parts.length < 2 || parts.some(Number.isNaN)) {
            return null;
        }

        return parts[0] * 3600 + parts[1] * 60 + (parts[2] || 0);
    }

    function overlaps(aStart, aEnd, bStart, bEnd) {
        return aStart < bEnd && aEnd > bStart;
    }

    function rowData(row) {
        const room = field(row, 'room_id');
        const instructor = field(row, 'instructor_id');
        const day = field(row, 'day');
        const start = field(row, 'start_time');
        const end = field(row, 'end_time');

        const dayResult = parseDays(day?.value);
        const startSeconds = toSeconds(start?.value);
        const endSeconds = toSeconds(end?.value);

        const problems = [];

        if (!room?.value) {
            problems.push('Missing classroom. Select a valid room before importing.');
        }

        if (!dayResult.valid) {
            problems.push('Invalid or missing day.');
        }

        if (startSeconds === null || endSeconds === null) {
            problems.push('Missing or invalid start/end time.');
        } else if (endSeconds <= startSeconds) {
            problems.push('End time must be later than start time.');
        }

        return {
            row,
            roomId: room?.value || '',
            instructorId: instructor?.value || '',
            days: dayResult.tokens,
            dayValid: dayResult.valid,
            startSeconds,
            endSeconds,
            problems,
            conflicts: []
        };
    }

    function refreshValidation() {

        const data = rows.map(rowData);

        // Check against existing database schedules.
        data.forEach(item => {

            if (
                !item.dayValid ||
                item.startSeconds === null ||
                item.endSeconds === null ||
                item.endSeconds <= item.startSeconds
            ) return;

            savedSchedules.forEach(saved => {

                const savedDays = parseDays(saved.day);

                const savedStart = toSeconds(
                    String(saved.start_time || '').substring(0, 8)
                );

                const savedEnd = toSeconds(
                    String(saved.end_time || '').substring(0, 8)
                );

                if (!savedDays.valid || savedStart === null || savedEnd === null) {
                    return;
                }

                const sameDay = item.days.some(day =>
                    savedDays.tokens.includes(day)
                );

                const timeOverlap = overlaps(
                    item.startSeconds,
                    item.endSeconds,
                    savedStart,
                    savedEnd
                );

                if (!sameDay || !timeOverlap) return;

                if (
                    item.roomId &&
                    String(item.roomId) === String(saved.room_id || '')
                ) {
                    item.conflicts.push('Room conflict with an existing saved schedule.');
                }

                if (
                    item.instructorId &&
                    String(item.instructorId) === String(saved.instructor_id || '')
                ) {
                    item.conflicts.push('Instructor conflict with an existing saved schedule.');
                }

            });

        });

        // Check imported rows against one another.
        for (let i = 0; i < data.length; i++) {

            const a = data[i];

            if (
                !a.dayValid ||
                a.startSeconds === null ||
                a.endSeconds === null ||
                a.endSeconds <= a.startSeconds
            ) continue;

            for (let j = i + 1; j < data.length; j++) {

                const b = data[j];

                if (
                    !b.dayValid ||
                    b.startSeconds === null ||
                    b.endSeconds === null ||
                    b.endSeconds <= b.startSeconds
                ) continue;

                const sameDay = a.days.some(day => b.days.includes(day));

                const timeOverlap = overlaps(
                    a.startSeconds,
                    a.endSeconds,
                    b.startSeconds,
                    b.endSeconds
                );

                if (!sameDay || !timeOverlap) continue;

                if (a.roomId && a.roomId === b.roomId) {
                    a.conflicts.push(`Room conflict with row ${j + 1}.`);
                    b.conflicts.push(`Room conflict with row ${i + 1}.`);
                }

                if (a.instructorId && a.instructorId === b.instructorId) {
                    a.conflicts.push(`Instructor conflict with row ${j + 1}.`);
                    b.conflicts.push(`Instructor conflict with row ${i + 1}.`);
                }

            }
        }

        let missingRooms = 0;
        let eligible = 0;
        let invalidRows = 0;
        let conflictRows = 0;

        data.forEach(item => {

            const status = item.row.querySelector('[data-row-status]');
            const remarks = item.row.querySelector('[data-row-remarks]');

            const originalRemarks = remarks?.dataset.originalRemarks || '';

            const persistentRemarks = originalRemarks
                .replace(/Classroom ['"].*?['"] was not found\.\s*/gi, '')
                .replace(/Missing classroom\. Select a valid room before importing\.\s*/gi, '')
                .replace(/No classroom assigned\.\s*/gi, '')
                .replace(/Room conflict[^.]*\.\s*/gi, '')
                .replace(/Instructor conflict[^.]*\.\s*/gi, '')
                .replace(/Exact duplicate[^.]*\.\s*/gi, '')
                .trim();

            const messages = [...item.problems, ...item.conflicts];

            if (persistentRemarks) {
                messages.unshift(persistentRemarks);
            }

            const hasMissingRoom = !item.roomId;

            const hasInvalid = item.problems.some(problem =>
                problem.startsWith('Invalid') ||
                problem.startsWith('Missing or invalid') ||
                problem.startsWith('End time')
            );

            const hasConflict = item.conflicts.length > 0;

            if (hasMissingRoom) missingRooms++;
            if (hasInvalid) invalidRows++;
            if (hasConflict) conflictRows++;

            const rowReady =
                !hasMissingRoom &&
                !hasInvalid &&
                !hasConflict;

            if (rowReady) eligible++;

            // Update row status.
            if (status) {

                if (hasMissingRoom) {

                    status.textContent = 'Missing Room';
                    status.className =
                        'inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700';

                } else if (hasInvalid) {

                    status.textContent = 'Invalid';
                    status.className =
                        'inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700';

                } else if (hasConflict) {

                    status.textContent = 'Conflict';
                    status.className =
                        'inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700';

                } else {

                    status.textContent = 'Ready';
                    status.className =
                        'inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700';

                }
            }

            // Update row remarks.
            if (remarks) {

                remarks.textContent = messages.length
                    ? messages.join(' ')
                    : 'No issues detected.';

                remarks.className =
                    'text-xs ' + (messages.length ? 'text-red-600' : 'text-green-600');
            }

        });

        // UPDATE ALL SUMMARY CARDS.
        const totalRows = rows.length;

        if (summaryNewCount) {
            summaryNewCount.textContent = eligible;
        }

        if (summaryConflictCount) {
            summaryConflictCount.textContent = conflictRows;
        }

        if (summaryInvalidCount) {
            summaryInvalidCount.textContent =
                baseInvalidCount + missingRooms + invalidRows;
        }

        if (eligibleCount) {
            eligibleCount.textContent = eligible;
        }

        if (missingCount) {
            missingCount.textContent = missingRooms;
        }

        // Block confirmation if any problem remains.
        const blocked =
            missingRooms > 0 ||
            invalidRows > 0 ||
            conflictRows > 0 ||
            eligible === 0;

        if (confirmButton) {
            confirmButton.disabled = blocked;
            confirmButton.textContent = `Confirm Import (${eligible})`;
        }

        if (warning) {

            warning.textContent = blocked
                ? `Import is blocked: ${missingRooms} missing room(s), ${invalidRows} invalid row(s), and ${conflictRows} conflicting row(s). Resolve all issues before confirming.`
                : 'All rows passed preview validation. The server will perform a final conflict check before saving.';

            warning.classList.toggle('hidden', !blocked);
        }

    }

    // Revalidate whenever any editable field changes.
    rows.forEach(row => {

        row.querySelectorAll('select, input').forEach(input => {

            input.addEventListener('change', refreshValidation);
            input.addEventListener('input', refreshValidation);

        });

    });

    // Run validation immediately on page load.
    refreshValidation();

});
</script>

@endsection
