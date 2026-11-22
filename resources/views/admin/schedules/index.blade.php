@extends('layouts.admin')

@section('title', 'Schedule Management')

@section('content')

<div class="w-full mx-auto p-4 sm:p-6 lg:p-8">

    <!-- ===================================================== -->
    <!-- HEADER -->
    <!-- ===================================================== -->

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-6">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">
                Schedule Management
            </h1>

            <p class="mt-1 text-slate-500">
                Manage finalized class schedules imported from the Registrar.
            </p>

        </div>


       <!-- Action Buttons -->

<div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">

    @if(auth()->user()->hasAdminPermission('schedule_management', 'full'))

        <!-- Bulk Delete -->
        <button
            type="button"
            id="openBulkDelete"
            class="inline-flex items-center justify-center gap-2 rounded-xl border border-red-200 bg-red-50 px-5 py-3 text-sm font-medium text-red-600 hover:bg-red-100 hover:border-red-300 transition">

            <x-heroicon-o-trash class="w-5 h-5"/>

            Delete Schedules
        </button>

        <!-- Import -->
        <button
            type="button"
            id="openImportSchedule"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0E4C6B] px-5 py-3 text-sm font-medium text-white hover:bg-[#0B3D56] transition">

            <x-heroicon-o-arrow-up-tray class="w-5 h-5"/>

            Import Schedule
        </button>

    @endif

</div>

    </div>


    
@if(session('success') || session('error') || $errors->any())

    <div id="scheduleToast"
         class="fixed top-6 right-6 z-[110] w-full max-w-sm rounded-xl border bg-white p-4 shadow-xl
         {{ session('error') || $errors->any() ? 'border-red-200' : 'border-green-200' }}">

        <div class="flex items-start gap-3">

            @if(session('error') || $errors->any())
                <x-heroicon-o-exclamation-circle class="h-6 w-6 shrink-0 text-red-600"/>
            @else
                <x-heroicon-o-check-circle class="h-6 w-6 shrink-0 text-green-600"/>
            @endif

            <div class="min-w-0 flex-1">
                <p class="font-semibold text-slate-800">
                    {{ session('error') || $errors->any() ? 'Action Failed' : 'Success' }}
                </p>

                <p class="mt-1 text-sm text-slate-600">
                    @if($errors->any())
                        {{ $errors->first() }}
                    @else
                        {{ session('error') ?? session('success') }}
                    @endif
                </p>
            </div>

            <button type="button"
                    onclick="document.getElementById('scheduleToast').remove()"
                    class="text-slate-400 hover:text-slate-700">
                &times;
            </button>

        </div>
    </div>

    <script>
        setTimeout(() => {
            document.getElementById('scheduleToast')?.remove();
        }, 5000);
    </script>

@endif


    <!-- ===================================================== -->
    <!-- STATISTICS -->
    <!-- ===================================================== -->

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">


        <!-- Total -->

        <div
            class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">

            <div class="flex items-center justify-between gap-3">
                <p class="text-sm font-medium text-slate-500">
                    Total Schedules
                </p>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <x-heroicon-o-calendar-days class="h-5 w-5"/>
                </div>
            </div>

            <h2 class="mt-3 text-3xl font-bold text-slate-800">

                {{ $schedules->total() }}

            </h2>

        </div>


        <!-- Instructors -->

        <div
            class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">

            <div class="flex items-center justify-between gap-3">
                <p class="text-sm font-medium text-slate-500">
                    Instructors
                </p>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                    <x-heroicon-o-users class="h-5 w-5"/>
                </div>
            </div>

            <h2 class="mt-3 text-3xl font-bold text-slate-800">

                {{ $schedules->getCollection()->pluck('instructor_id')->unique()->count() }}

            </h2>

        </div>


        <!-- Rooms -->

        <div
            class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">

            <div class="flex items-center justify-between gap-3">
                <p class="text-sm font-medium text-slate-500">
                    Rooms Used
                </p>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <x-heroicon-o-building-office-2 class="h-5 w-5"/>
                </div>
            </div>

            <h2 class="mt-3 text-3xl font-bold text-slate-800">

                {{ $schedules->getCollection()->pluck('room_id')->unique()->count() }}

            </h2>

        </div>


        <!-- Subjects -->

        <div
            class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">

            <div class="flex items-center justify-between gap-3">
                <p class="text-sm font-medium text-slate-500">
                    Subjects
                </p>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                    <x-heroicon-o-book-open class="h-5 w-5"/>
                </div>
            </div>

            <h2 class="mt-3 text-3xl font-bold text-slate-800">

                {{ $schedules->getCollection()->pluck('subject_code')->unique()->count() }}

            </h2>

        </div>

    </div>


    <!-- ===================================================== -->
    <!-- SEARCH -->
    <!-- ===================================================== -->

    <div
        class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 mb-6">

        <label
            for="scheduleSearch"
            class="block text-sm font-semibold text-slate-700 mb-2">

            Search Schedules

        </label>

        <input
            id="scheduleSearch"
            type="text"
            placeholder="Search instructor, subject, room, day..."
            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 placeholder:text-slate-400 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

    </div>


    <!-- ===================================================== -->
    <!-- TABLE -->
    <!-- ===================================================== -->

    <div
        class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full min-w-[1450px]">

                <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">

                    <tr>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide">
                            Instructor
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide">
                            Subject
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wide">
                            Units
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wide">
                            Enrolled
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wide">
                            Room
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wide">
                            Day
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wide">
                            Time
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wide">
                            Semester
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wide">
                            School Year
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wide">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody id="scheduleTable">

                    @forelse($schedules as $schedule)

                        <tr
                            class="border-b border-slate-100 hover:bg-slate-50/80 transition-colors duration-150">


                            <!-- Instructor -->

                            <td class="px-6 py-5">

                                @if($schedule->instructor)

                                    <p class="text-sm font-semibold text-slate-800">

                                        {{ $schedule->instructor->first_name }}
                                        {{ $schedule->instructor->last_name }}

                                    </p>

                               @else
    <span class="text-amber-600 text-sm">
        Unassigned Instructor
    </span>
@endif

                            </td>


                            <!-- Subject -->

                            <td class="px-6 py-5">

                                <span
                                    class="inline-flex
                                           bg-blue-50
                                           text-blue-700
                                           px-3 py-1
                                           rounded-full
                                           border border-blue-100
                                           text-xs font-semibold">

                                    {{ $schedule->subject_code }}

                                </span>

                                @if($schedule->subject_name)

                                    <p class="mt-2 text-sm text-slate-600 max-w-xs">
                                        {{ $schedule->subject_name }}
                                    </p>

                                @endif

                            </td>


                            <!-- Units -->

                            <td class="px-6 py-5 text-center">

                                <span class="text-sm font-medium text-slate-700">
                                    @if($schedule->units !== null)
                                        {{ rtrim(rtrim(number_format((float) $schedule->units, 2, '.', ''), '0'), '.') }}
                                    @else
                                        —
                                    @endif
                                </span>

                            </td>


                            <!-- Enrolled -->

                            <td class="px-6 py-5 text-center">

                                <span class="text-sm font-medium text-slate-700">
                                    {{ $schedule->enrolled ?? '—' }}
                                </span>

                            </td>


                            <!-- Room -->

                            <td class="px-6 py-5 text-center">

                                @if($schedule->room)

                                    <span
                                        class="inline-flex
                                               bg-green-50
                                               text-green-700
                                               px-3 py-1
                                               rounded-full
                                               text-xs font-semibold">

                                        {{ $schedule->room->room_name }}

                                    </span>

                                    <p class="mt-2 text-xs text-slate-500">

                                        Room {{ $schedule->room->room_number }}

                                    </p>

                             @else
    <span class="text-amber-600 text-sm">
        Unassigned Room
    </span>
@endif

                            </td>


                            <!-- Day -->

                            <td class="px-6 py-5 text-center">

                                <span
                                    class="inline-flex
                                           bg-yellow-50
                                           text-yellow-700
                                           px-3 py-1
                                           rounded-full
                                           border border-blue-100
                                           text-xs font-semibold">

                                    {{ $schedule->day }}

                                </span>

                            </td>


                            <!-- Time -->

                            <td
                                class="px-6 py-5
                                       text-center
                                       whitespace-nowrap">

                                <span
                                    class="inline-flex
                                           bg-indigo-50
                                           text-indigo-700
                                           px-3 py-1
                                           rounded-full
                                           border border-blue-100
                                           text-xs font-semibold">

                                    {{ date('g:i A', strtotime($schedule->start_time)) }}

                                    -

                                    {{ date('g:i A', strtotime($schedule->end_time)) }}

                                </span>

                            </td>


                            <!-- Semester -->

                            <td class="px-6 py-5 text-center">

                                <span
                                    class="inline-flex
                                           bg-purple-50
                                           text-purple-700
                                           px-3 py-1
                                           rounded-full
                                           border border-blue-100
                                           text-xs font-semibold">

                                    {{ $schedule->semester }}

                                </span>

                            </td>


                            <!-- School Year -->

                            <td class="px-6 py-5 text-center">

                                <span
                                    class="inline-flex
                                           bg-slate-100
                                           text-slate-700
                                           px-3 py-1
                                           rounded-full
                                           border border-blue-100
                                           text-xs font-semibold">

                                    {{ $schedule->school_year }}

                                </span>

                            </td>


                        <!-- Delete -->
<td class="px-6 py-5 text-center">

    @if(auth()->user()->hasAdminPermission('schedule_management', 'full'))

        <form
            action="{{ route('admin.schedules.destroy', $schedule) }}"
            method="POST"
            class="delete-schedule-form inline">

            @csrf
            @method('DELETE')

            <button type="submit"
                class="inline-flex items-center justify-center rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-100 hover:border-red-300 transition">
                Delete
            </button>

        </form>

    @else

        <span class="text-xs text-slate-400">View only</span>

    @endif

</td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="10"
                                class="px-6 py-16
                                       text-center
                                       text-slate-500">

                                <div class="flex flex-col items-center">

                                    <x-heroicon-o-calendar-days
                                        class="w-12 h-12 text-slate-300 mb-3"/>

                                    <p class="font-semibold text-slate-600">
                                        No schedules found.
                                    </p>

                                    <p class="text-sm mt-1">
                                        Import the finalized Registrar schedule to get started.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        <!-- Pagination -->

        <div class="p-4 sm:p-6 overflow-x-auto">

            {{ $schedules->links() }}

        </div>

    </div>

</div>


<!-- ========================================================= -->
<!-- IMPORT SCHEDULE MODAL -->
<!-- ========================================================= -->

<div
    id="importScheduleModal"
    class="fixed inset-0 z-50 hidden
           items-center justify-center
           bg-black/50 backdrop-blur-sm
           p-4">


    <div
        class="w-full max-w-lg
               rounded-xl
               bg-white
               shadow-2xl
               overflow-hidden">


        <!-- Modal Header -->

        <div
            class="border-b border-slate-200
                   p-6">

            <div class="flex items-start justify-between gap-4">

                <div>

                    <h2 class="text-xl font-bold text-slate-800">
                        Import Schedule
                    </h2>

                    <p class="mt-1 text-slate-500">
                        Upload the finalized schedule from the Registrar.
                    </p>

                </div>


                <button
                    type="button"
                    id="closeImportScheduleX"
                    class="text-slate-400
                           hover:text-slate-700
                           text-2xl
                           leading-none">

                    &times;

                </button>

            </div>

        </div>


        <!-- Import Form -->

       
<form
    action="{{ route('admin.schedules.import') }}"
    method="POST"
    enctype="multipart/form-data"
    class="p-6">

    @csrf

    {{-- Semester --}}
    <div class="mb-5">
        <label for="importSemester"
               class="block text-sm font-semibold text-slate-700 mb-2">
            Semester
        </label>

        <select
            id="importSemester"
            name="semester"
            required
            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

            <option value="">Select Semester</option>
            <option value="1st Semester">1st Semester</option>
            <option value="2nd Semester">2nd Semester</option>

        </select>
    </div>

    {{-- School Year --}}
    <div class="mb-5">
        <label for="importSchoolYear"
               class="block text-sm font-semibold text-slate-700 mb-2">
            School Year
        </label>

        <input
            type="text"
            id="importSchoolYear"
            name="school_year"
            placeholder="Example: 2026-2027"
            maxlength="20"
            required
            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">
    </div>

    {{-- Schedule File --}}
    <div>
        <label
            for="scheduleFile"
            class="block text-sm font-semibold text-slate-700 mb-2">
            Registrar Schedule File
        </label>

        <input
            id="scheduleFile"
            type="file"
            name="file"
            accept=".xlsx,.xls,.csv"
            required
            class="block w-full rounded-xl border border-slate-300 p-3 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

        <p class="mt-2 text-xs text-slate-500">
            Accepted formats: Excel (.xlsx, .xls) and CSV (.csv). Maximum size: 10 MB.
        </p>
    </div>

    {{-- Expected Format --}}
    <div class="mt-6 rounded-xl bg-blue-50 border border-blue-100 p-4">

        <p class="font-semibold text-slate-700 mb-3">
            Expected Registrar Excel Columns
        </p>

        <div class="grid grid-cols-2 gap-y-2 text-sm text-slate-600">
            <p>• Code</p>
            <p>• Name</p>
            <p>• Subject Title</p>
            <p>• Units</p>
            <p>• Start</p>
            <p>• End</p>
            <p>• Days</p>
            <p>• Classroom</p>
            <p>• Instructor</p>
            <p>• Enrolled</p>
        </div>

        <p class="mt-3 text-xs text-slate-500">
            Semester and School Year are entered above and do not need to be included in the Excel file.
        </p>
    </div>

    {{-- Buttons --}}
    <div class="mt-8 flex flex-col-reverse sm:flex-row justify-end gap-3">

        <button
            type="button"
            id="closeImportSchedule"
            class="w-full sm:w-auto rounded-xl border border-slate-300 px-5 py-2.5 hover:bg-slate-100 transition">
            Cancel
        </button>

        <button
            type="submit"
            class="w-full sm:w-auto rounded-xl bg-[#0E4C6B] px-6 py-2.5 text-white font-semibold hover:bg-[#0B3D56] transition">
            Continue to Preview
        </button>

    </div>

</form>

   </div>
</div>



<!-- ========================================================= -->
<!-- BULK DELETE SCHEDULE MODAL -->
<!-- ========================================================= -->

<div
    id="bulkDeleteModal"
    class="fixed inset-0 z-50 hidden
           items-center justify-center
           bg-black/50 backdrop-blur-sm
           p-4">

    <div
        class="w-full max-w-lg
               rounded-xl
               bg-white
               shadow-2xl
               overflow-hidden">

        <!-- Modal Header -->
        <div class="border-b border-slate-200 p-6">

            <div class="flex items-start justify-between gap-4">

                <div>
                    <h2 class="text-xl font-bold text-slate-800">
                        Delete Schedules
                    </h2>

                    <p class="mt-1 text-slate-500">
                        Remove schedules in bulk using a specific filter.
                    </p>
                </div>

                <button
                    type="button"
                    id="closeBulkDeleteX"
                    class="text-slate-400 hover:text-slate-700 text-2xl leading-none">
                    &times;
                </button>

            </div>

        </div>

        <!-- Bulk Delete Form -->
        <form
            id="bulkDeleteForm"
            action="{{ route('admin.schedules.bulk-delete') }}"
            method="POST"
            class="p-6">

            @csrf
            @method('DELETE')

            <!-- Delete Type -->
            <div>
                <label
                    for="deleteType"
                    class="block text-sm font-semibold text-slate-700 mb-2">
                    Delete By
                </label>

                <select
                    id="deleteType"
                    name="delete_type"
                    required
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

                    <option value="all">All Schedules</option>
                    <option value="semester">Semester</option>
                    <option value="school_year">School Year</option>
                    <option value="semester_school_year">Semester + School Year</option>

                </select>
            </div>

            <!-- Semester -->
            <div id="semesterField" class="mt-5 hidden">

                <label
                    for="deleteSemester"
                    class="block text-sm font-semibold text-slate-700 mb-2">
                    Semester
                </label>

                <select
                    id="deleteSemester"
                    name="semester"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

                    <option value="">Select Semester</option>
                    <option value="1st Semester">1st Semester</option>
                    <option value="2nd Semester">2nd Semester</option>

                </select>
            </div>

            <!-- School Year -->
            <div id="schoolYearField" class="mt-5 hidden">

                <label
                    for="deleteSchoolYear"
                    class="block text-sm font-semibold text-slate-700 mb-2">
                    School Year
                </label>

                <select
                    id="deleteSchoolYear"
                    name="school_year"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

                    <option value="">Select School Year</option>

                    @php
                        $schoolYears = \App\Models\Schedule::query()
                            ->select('school_year')
                            ->whereNotNull('school_year')
                            ->distinct()
                            ->orderByDesc('school_year')
                            ->pluck('school_year');
                    @endphp

                    @foreach($schoolYears as $schoolYear)
                        <option value="{{ $schoolYear }}">
                            {{ $schoolYear }}
                        </option>
                    @endforeach

                </select>
            </div>

            <!-- Warning -->
            <div
                id="bulkDeleteWarning"
                class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4">

                <div class="flex gap-3">

                    <x-heroicon-o-exclamation-triangle
                        class="w-6 h-6 text-red-600 flex-shrink-0"/>

                    <div>
                        <p class="font-semibold text-red-700">
                            Warning
                        </p>

                        <p class="mt-1 text-sm text-red-600">
                            Deleted schedules cannot be recovered. Please make sure you selected the correct criteria.
                        </p>
                    </div>

                </div>
            </div>

            <!-- Buttons -->
            <div
                class="mt-8 flex flex-col-reverse sm:flex-row justify-end gap-3">

                <button
                    type="button"
                    id="closeBulkDelete"
                    class="w-full sm:w-auto rounded-xl border border-slate-300 px-5 py-2.5 hover:bg-slate-100 transition">
                    Cancel
                </button>

                <button
                    type="submit"
                    class="w-full sm:w-auto rounded-xl bg-red-600 px-6 py-2.5 text-white font-semibold hover:bg-red-700 transition">
                    Delete Schedules
                </button>

            </div>

        </form>
    </div>
</div>


<!-- ========================================================= -->
<!-- JAVASCRIPT -->
<!-- ========================================================= -->

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Import Modal
    |--------------------------------------------------------------------------
    */

    const modal =
        document.getElementById('importScheduleModal');

    const openButton =
        document.getElementById('openImportSchedule');

    const closeButton =
        document.getElementById('closeImportSchedule');

    const closeX =
        document.getElementById('closeImportScheduleX');


    function openModal() {

        modal.classList.remove('hidden');

        modal.classList.add('flex');

        document.body.classList.add('overflow-hidden');

    }


    function closeModal() {

        modal.classList.add('hidden');

        modal.classList.remove('flex');

        document.body.classList.remove('overflow-hidden');

    }


    if (openButton) {

        openButton.addEventListener(
            'click',
            openModal
        );

    }


    if (closeButton) {

        closeButton.addEventListener(
            'click',
            closeModal
        );

    }


    if (closeX) {

        closeX.addEventListener(
            'click',
            closeModal
        );

    }


    if (modal) {

        modal.addEventListener(
            'click',
            function (event) {

                if (event.target === modal) {

                    closeModal();

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ESC closes modal
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key === 'Escape' &&
                modal &&
                !modal.classList.contains('hidden')
            ) {

                closeModal();

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    const search =
        document.getElementById('scheduleSearch');


    if (search) {

        search.addEventListener(
            'keyup',
            function () {

                const value =
                    this.value.toLowerCase().trim();


                document
                    .querySelectorAll('#scheduleTable tr')
                    .forEach(function (row) {

                        row.style.display =
                            row.innerText
                                .toLowerCase()
                                .includes(value)
                                ? ''
                                : 'none';

                    });

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Bulk Delete Modal
    |--------------------------------------------------------------------------
    */

    const bulkDeleteModal =
        document.getElementById('bulkDeleteModal');

    const openBulkDelete =
        document.getElementById('openBulkDelete');

    const closeBulkDelete =
        document.getElementById('closeBulkDelete');

    const closeBulkDeleteX =
        document.getElementById('closeBulkDeleteX');

    const deleteType =
        document.getElementById('deleteType');

    const semesterField =
        document.getElementById('semesterField');

    const schoolYearField =
        document.getElementById('schoolYearField');

    const deleteSemester =
        document.getElementById('deleteSemester');

    const deleteSchoolYear =
        document.getElementById('deleteSchoolYear');

    function openBulkDeleteModal() {

        bulkDeleteModal.classList.remove('hidden');
        bulkDeleteModal.classList.add('flex');
        document.body.classList.add('overflow-hidden');

    }

    function closeBulkDeleteModal() {

        bulkDeleteModal.classList.add('hidden');
        bulkDeleteModal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');

    }

    function updateDeleteFields() {

        const type = deleteType.value;

        semesterField.classList.add('hidden');
        schoolYearField.classList.add('hidden');

        deleteSemester.required = false;
        deleteSchoolYear.required = false;

        if (
            type === 'semester' ||
            type === 'semester_school_year'
        ) {
            semesterField.classList.remove('hidden');
            deleteSemester.required = true;
        }

        if (
            type === 'school_year' ||
            type === 'semester_school_year'
        ) {
            schoolYearField.classList.remove('hidden');
            deleteSchoolYear.required = true;
        }

    }

    if (openBulkDelete) {
        openBulkDelete.addEventListener('click', openBulkDeleteModal);
    }

    if (closeBulkDelete) {
        closeBulkDelete.addEventListener('click', closeBulkDeleteModal);
    }

    if (closeBulkDeleteX) {
        closeBulkDeleteX.addEventListener('click', closeBulkDeleteModal);
    }

    if (bulkDeleteModal) {
        bulkDeleteModal.addEventListener('click', function (event) {
            if (event.target === bulkDeleteModal) {
                closeBulkDeleteModal();
            }
        });
    }

    if (deleteType) {
        deleteType.addEventListener('change', updateDeleteFields);
    }

    updateDeleteFields();

    /*
    |--------------------------------------------------------------------------
    | Bulk Delete Confirmation
    |--------------------------------------------------------------------------
    */

    const bulkDeleteForm =
        document.getElementById('bulkDeleteForm');

    if (bulkDeleteForm) {

        bulkDeleteForm.addEventListener('submit', function (event) {

            event.preventDefault();

            const type = deleteType.value;

            let message =
                'The selected schedules will be permanently deleted.';

            if (type === 'all') {
                message =
                    'ALL schedules will be permanently deleted. This action cannot be undone.';
            }
            else if (type === 'semester') {
                message =
                    'All schedules for ' +
                    deleteSemester.value +
                    ' will be permanently deleted.';
            }
            else if (type === 'school_year') {
                message =
                    'All schedules for school year ' +
                    deleteSchoolYear.value +
                    ' will be permanently deleted.';
            }
            else if (type === 'semester_school_year') {
                message =
                    'All schedules for ' +
                    deleteSemester.value +
                    ' - ' +
                    deleteSchoolYear.value +
                    ' will be permanently deleted.';
            }

            if (typeof Swal === 'undefined') {
                if (confirm(message)) {
                    bulkDeleteForm.submit();
                }
                return;
            }

            Swal.fire({
                title: 'Delete Schedules?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel'
            }).then(function (result) {
                if (result.isConfirmed) {
                    bulkDeleteForm.submit();
                }
            });

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Delete Confirmation
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.delete-schedule-form')
        .forEach(function (form) {

            form.addEventListener(
                'submit',
                function (event) {

                    event.preventDefault();


                    if (typeof Swal === 'undefined') {

                        if (
                            confirm(
                                'Delete this schedule?'
                            )
                        ) {

                            form.submit();

                        }

                        return;

                    }


                    Swal.fire({

                        title: 'Delete Schedule?',

                        text: 'This schedule will be permanently removed.',

                        icon: 'warning',

                        showCancelButton: true,

                        confirmButtonColor: '#dc2626',

                        cancelButtonColor: '#6b7280',

                        confirmButtonText: 'Yes, Delete',

                        cancelButtonText: 'Cancel'

                    }).then(function (result) {

                        if (result.isConfirmed) {

                            form.submit();

                        }

                    });

                }
            );

        });

        

});

</script>

@endpush

@endsection