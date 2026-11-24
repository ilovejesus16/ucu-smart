
@extends('layouts.admin')

@section('title', 'Room Monitoring - ' . $room->room_number)

@section('content')

<div class="max-w-6xl mx-auto">

    <!-- ========================================================= -->
    <!-- BACK -->
    <!-- ========================================================= -->

    <a
        href="{{ route('admin.room-monitoring.buildings', $room->building) }}"
        class="inline-flex items-center gap-2
               text-sm font-semibold
               text-[#0E4C6B]
               hover:text-[#0B3D56]
               transition">

        <x-heroicon-o-arrow-left class="w-4 h-4"/>

        Back to {{ $room->building->building_name }}

    </a>


    <!-- ========================================================= -->
    <!-- ROOM CARD -->
    <!-- ========================================================= -->

    <div class="bg-white rounded-2xl border border-gray-200
                shadow-sm mt-5 overflow-hidden">

        <!-- ROOM HEADER -->

        <div class="bg-[#0E2958] text-white p-6 sm:p-8">

            <div class="flex items-start gap-4">

                <div class="w-14 h-14 rounded-2xl bg-white/10
                            flex items-center justify-center flex-shrink-0">

                    <x-heroicon-o-home-modern class="w-7 h-7"/>

                </div>

                <div class="min-w-0">

                    <p class="text-sm text-blue-200 font-medium">
                        {{ $room->building->building_name }}
                    </p>

                    <h1 class="text-3xl sm:text-4xl font-extrabold mt-1 break-words">
                        {{ $room->room_number }}
                    </h1>

                    <p class="text-blue-100 mt-1 text-lg break-words">
                        {{ $room->room_name }}
                    </p>

                </div>

            </div>

        </div>


        <!-- CONTENT -->

        <div class="p-6 sm:p-8">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- ROOM INFORMATION -->

                <div>

                    <h2 class="text-xl font-bold text-[#0E2958] mb-5">
                        Room Information
                    </h2>

                    <div class="space-y-3">

                        <div class="flex items-center justify-between gap-4
                                    border-b border-gray-100 pb-3">

                            <div class="flex items-center gap-2 text-gray-500">

                                <x-heroicon-o-building-office-2 class="w-5 h-5"/>

                                Building

                            </div>

                            <span class="font-semibold text-gray-800 text-right">
                                {{ $room->building->building_name }}
                            </span>

                        </div>


                        <div class="flex items-center justify-between gap-4
                                    border-b border-gray-100 pb-3">

                            <div class="flex items-center gap-2 text-gray-500">

                                <x-heroicon-o-bars-3 class="w-5 h-5"/>

                                Floor

                            </div>

                            <span class="font-semibold text-gray-800">
                                {{ $room->floor }}
                            </span>

                        </div>


                        <div class="flex items-center justify-between gap-4
                                    border-b border-gray-100 pb-3">

                            <div class="flex items-center gap-2 text-gray-500">

                                <x-heroicon-o-user-group class="w-5 h-5"/>

                                Capacity

                            </div>

                            <span class="font-semibold text-gray-800">
                                {{ $room->capacity }}
                            </span>

                        </div>

                    </div>

                </div>


                <!-- MONITORING SUMMARY -->

                <div>

                    <h2 class="text-xl font-bold text-[#0E2958] mb-5">
                        Monitoring Summary
                    </h2>

                    @php
                        $presentCount = $monitorings->where('attendance_status', 'present')->count();
                        $absentCount = $monitorings->where('attendance_status', 'absent')->count();
                        $checkedCount = $monitorings->count();
                        $uncheckedCount = $schedules->count() - $checkedCount;
                    @endphp

                    <div class="grid grid-cols-2 gap-3">

                        <div class="bg-green-50 border border-green-200 rounded-xl p-4">

                            <p class="text-sm text-green-600">
                                Present
                            </p>

                            <p class="text-2xl font-extrabold text-green-700 mt-1">
                                {{ $presentCount }}
                            </p>

                        </div>


                        <div class="bg-red-50 border border-red-200 rounded-xl p-4">

                            <p class="text-sm text-red-600">
                                Absent
                            </p>

                            <p class="text-2xl font-extrabold text-red-700 mt-1">
                                {{ $absentCount }}
                            </p>

                        </div>


                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">

                            <p class="text-sm text-amber-600">
                                Not checked
                            </p>

                            <p class="text-2xl font-extrabold text-amber-700 mt-1">
                                {{ max(0, $uncheckedCount) }}
                            </p>

                        </div>


                        <div class="bg-[#0E4C6B]/5 border border-[#0E4C6B]/15 rounded-xl p-4">

                            <p class="text-sm text-[#0E4C6B]">
                                Total Classes
                            </p>

                            <p class="text-2xl font-extrabold text-[#0E2958] mt-1">
                                {{ $schedules->count() }}
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ========================================================= -->
    <!-- DATE SELECTION -->
    <!-- ========================================================= -->

    <div class="bg-white rounded-2xl border border-gray-200
                shadow-sm p-5 sm:p-6 mt-6">

        <div class="flex flex-col sm:flex-row
                    sm:items-center sm:justify-between gap-4">

            <div>

                <h2 class="text-lg font-bold text-[#0E2958]">
                    Attendance Monitoring
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Select a date to view and verify scheduled instructors.
                </p>

            </div>


            <form
                method="GET"
                action="{{ route('admin.room-monitoring.rooms.show', $room) }}"
                class="flex flex-col sm:flex-row gap-2">

                <input
                    type="date"
                    name="date"
                    value="{{ $date }}"
                    max="{{ now()->toDateString() }}"
                    required
                    class="rounded-xl border-gray-300
                           focus:border-[#0E4C6B]
                           focus:ring-[#0E4C6B]">

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2
                           bg-[#0E2958] hover:bg-[#163A74]
                           text-white font-semibold
                           px-5 py-2.5 rounded-xl transition">

                    <x-heroicon-o-calendar-days class="w-5 h-5"/>

                    View Date

                </button>

            </form>

        </div>


        <div class="mt-4 flex flex-wrap items-center gap-2">

            <span class="text-sm text-gray-500">
                Selected date:
            </span>

            <span class="bg-[#0E4C6B]/10 text-[#0E4C6B]
                         px-3 py-1.5 rounded-lg text-sm font-bold">

                {{ $selectedDate->format('F d, Y') }}

            </span>

            @if($selectedDate->isToday())

                <span class="bg-blue-50 text-blue-700
                             px-3 py-1.5 rounded-lg text-xs font-semibold">

                    Today

                </span>

            @else

                <span class="bg-amber-50 text-amber-700
                             px-3 py-1.5 rounded-lg text-xs font-semibold">

                    Historical Record

                </span>

            @endif

        </div>

    </div>


    <!-- ========================================================= -->
    <!-- SUCCESS MESSAGE -->
    <!-- ========================================================= -->

    @if(session('success'))

        <div class="mt-6 bg-green-50 border border-green-200
                    text-green-700 rounded-xl p-4">

            <div class="flex items-center gap-2">

                <x-heroicon-o-check-circle class="w-5 h-5"/>

                {{ session('success') }}

            </div>

        </div>

    @endif


    <!-- ========================================================= -->
    <!-- VALIDATION ERRORS -->
    <!-- ========================================================= -->

    @if($errors->any())

        <div class="mt-6 bg-red-50 border border-red-200
                    text-red-700 rounded-xl p-4">

            <p class="font-bold mb-2">
                Please check the following:
            </p>

            <ul class="list-disc pl-5 text-sm space-y-1">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <!-- ========================================================= -->
    <!-- SCHEDULED INSTRUCTORS -->
    <!-- ========================================================= -->

    <div class="mt-8">

        <div class="flex flex-col sm:flex-row
                    sm:items-center sm:justify-between gap-3 mb-5">

            <div>

                <h2 class="text-xl font-bold text-[#0E2958]">
                    Scheduled Instructors
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    All classes assigned to this room on the selected date.
                </p>

            </div>

            <a
                href="{{ route('admin.room-monitoring.rooms.history', $room) }}"
                class="inline-flex items-center justify-center gap-2
                       text-sm font-semibold
                       text-[#0E4C6B]
                       hover:text-[#0B3D56]
                       transition">

                <x-heroicon-o-clock class="w-5 h-5"/>

                Attendance History

            </a>

        </div>


        
@forelse($schedules as $schedule)

    @php
        $monitoring = $monitorings->get($schedule->id);

        $classStatusStyles = [
            'upcoming' => 'bg-blue-50 text-blue-700 border-blue-200',
            'scheduled' => 'bg-amber-50 text-amber-700 border-amber-200',
            'in_progress' => 'bg-red-50 text-red-700 border-red-200',
            'completed' => 'bg-green-50 text-green-700 border-green-200',
            'missed' => 'bg-gray-100 text-gray-600 border-gray-200',
            'cancelled' => 'bg-gray-100 text-gray-600 border-gray-200',
            'no_record' => 'bg-gray-100 text-gray-500 border-gray-200',
        ];

        $classStatusLabels = [
            'upcoming' => 'Upcoming',
            'scheduled' => 'Scheduled',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'missed' => 'Missed',
            'cancelled' => 'Cancelled',
            'no_record' => 'No Record',
        ];

        $classStatus = $schedule->classDisplayStatus;
    @endphp

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm
                overflow-hidden mb-5">

        {{-- SUBJECT HEADER --}}
        <div class="p-5 sm:p-6">

            <div class="flex flex-col sm:flex-row sm:items-start
                        sm:justify-between gap-5">

                <div class="flex items-start gap-3 min-w-0">

                    <div class="w-12 h-12 rounded-xl bg-[#0E4C6B]/10
                                flex items-center justify-center flex-shrink-0">

                        <x-heroicon-o-academic-cap
                            class="w-6 h-6 text-[#0E4C6B]"/>

                    </div>

                    <div class="min-w-0">

                        <h3 class="text-lg font-bold text-[#0E2958] break-words">
                            {{ $schedule->subject_name }}
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            {{ $schedule->subject_code }}
                        </p>

                        <p class="text-sm text-gray-600 mt-2">
                            Instructor:
                            <span class="font-semibold text-gray-800">
                                {{ $schedule->instructor->first_name ?? 'Unknown' }}
                                {{ $schedule->instructor->last_name ?? '' }}
                            </span>
                        </p>

                    </div>

                </div>

                {{-- STATUS BADGES --}}
                <div class="flex flex-wrap gap-3 sm:justify-end">

                    <div class="min-w-[130px]">

                        <p class="text-xs font-semibold text-gray-500 mb-2">
                            Class Status
                        </p>

                        <span class="inline-flex items-center gap-2
                                     px-3 py-2 rounded-xl border
                                     text-sm font-semibold
                                     {{ $classStatusStyles[$classStatus] ?? 'bg-gray-100 text-gray-500 border-gray-200' }}">

                            <span class="w-2 h-2 rounded-full bg-current"></span>

                            {{ $classStatusLabels[$classStatus] ?? 'No Record' }}

                        </span>

                    </div>

                    <div class="min-w-[130px]">

                        <p class="text-xs font-semibold text-gray-500 mb-2">
                            HR Attendance
                        </p>

                        @if($monitoring)

                            @if($monitoring->attendance_status === 'present')

                                <span class="inline-flex items-center gap-2
                                             px-3 py-2 rounded-xl border
                                             border-green-200 bg-green-50
                                             text-green-700 text-sm font-semibold">

                                    <x-heroicon-o-check-circle class="w-4 h-4"/>
                                    Present

                                </span>

                            @else

                                <span class="inline-flex items-center gap-2
                                             px-3 py-2 rounded-xl border
                                             border-red-200 bg-red-50
                                             text-red-700 text-sm font-semibold">

                                    <x-heroicon-o-x-circle class="w-4 h-4"/>
                                    Absent

                                </span>

                            @endif

                        @else

                            <span class="inline-flex items-center gap-2
                                         px-3 py-2 rounded-xl border
                                         border-amber-200 bg-amber-50
                                         text-amber-700 text-sm font-semibold">

                                <x-heroicon-o-clock class="w-4 h-4"/>
                                Not checked

                            </span>

                        @endif

                    </div>

                </div>

            </div>

            {{-- CLASS INFORMATION --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-6">

                <div class="flex items-center gap-3 rounded-xl
                            bg-gray-50 border border-gray-100 p-4">

                    <div class="w-10 h-10 rounded-lg bg-white
                                flex items-center justify-center">

                        <x-heroicon-o-clock class="w-5 h-5 text-[#0E4C6B]"/>

                    </div>

                    <div>

                        <p class="text-xs text-gray-500">
                            Class Time
                        </p>

                        <p class="text-sm font-bold text-gray-800 mt-1">
                            {{ date('g:i A', strtotime($schedule->start_time)) }}
                            –
                            {{ date('g:i A', strtotime($schedule->end_time)) }}
                        </p>

                    </div>

                </div>

                <div class="flex items-center gap-3 rounded-xl
                            bg-gray-50 border border-gray-100 p-4">

                    <div class="w-10 h-10 rounded-lg bg-white
                                flex items-center justify-center">

                        <x-heroicon-o-calendar-days
                            class="w-5 h-5 text-[#0E4C6B]"/>

                    </div>

                    <div>

                        <p class="text-xs text-gray-500">
                            Schedule Day
                        </p>

                        <p class="text-sm font-bold text-gray-800 mt-1">
                            {{ $selectedDate->format('l') }}
                        </p>

                    </div>

                </div>

            </div>

            {{-- EXISTING VERIFICATION DETAILS --}}
            @if($monitoring)

                <div class="mt-5 rounded-xl border border-gray-100
                            bg-gray-50 p-4">

                    <p class="text-sm font-bold text-gray-700 mb-2">
                        Verification Details
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">

                        <p class="text-sm text-gray-600">
                            Verified by:
                            <span class="font-semibold text-gray-800">
                                {{ $monitoring->verifier->first_name ?? 'Unknown' }}
                                {{ $monitoring->verifier->last_name ?? '' }}
                            </span>
                        </p>

                        <p class="text-sm text-gray-600">
                            Date:
                            <span class="font-semibold text-gray-800">
                                {{ $monitoring->verified_at?->format('M d, Y - g:i A') ?? 'Not available' }}
                            </span>
                        </p>

                    </div>

                    @if($monitoring->remarks)

                        <p class="text-sm text-gray-600 mt-2">
                            Remarks:
                            <span class="text-gray-800">
                                {{ $monitoring->remarks }}
                            </span>
                        </p>

                    @endif

                </div>

            @endif

        </div>

        {{-- ATTENDANCE FORM --}}
        @if(auth()->user()->hasAdminPermission('room_monitoring', 'full'))

            <div class="border-t border-gray-100 bg-gray-50/70 p-5 sm:p-6">

                <div class="flex items-center gap-2 mb-5">

                    <x-heroicon-o-clipboard-document-check
                        class="w-5 h-5 text-[#0E4C6B]"/>

                    <h4 class="font-bold text-[#0E2958]">
                        Attendance Verification
                    </h4>

                </div>

                <form
                    method="POST"
                    action="{{ route('admin.room-monitoring.attendance.store', $room) }}"
                    class="space-y-4">

                    @csrf

                    <input
                        type="hidden"
                        name="schedule_id"
                        value="{{ $schedule->id }}">

                    <input
                        type="hidden"
                        name="date"
                        value="{{ $date }}">

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

                        <div>

                            <label class="block text-sm font-semibold
                                          text-gray-700 mb-2">

                                Attendance Status

                            </label>

                            <select
                                name="attendance_status"
                                required
                                class="w-full rounded-xl border-gray-300
                                       bg-white py-3
                                       focus:border-[#0E4C6B]
                                       focus:ring-[#0E4C6B]">

                                <option value="">
                                    Select attendance status
                                </option>

                                <option
                                    value="present"
                                    @selected(
                                        old('schedule_id') == $schedule->id
                                            ? old('attendance_status') === 'present'
                                            : ($monitoring?->attendance_status === 'present')
                                    )>

                                    Present

                                </option>

                                <option
                                    value="absent"
                                    @selected(
                                        old('schedule_id') == $schedule->id
                                            ? old('attendance_status') === 'absent'
                                            : ($monitoring?->attendance_status === 'absent')
                                    )>

                                    Absent

                                </option>

                            </select>

                        </div>

                        <div>

                            <label class="block text-sm font-semibold
                                          text-gray-700 mb-2">

                                Remarks
                                <span class="font-normal text-gray-400">
                                    (Optional)
                                </span>

                            </label>

                            <textarea
                                name="remarks"
                                rows="2"
                                maxlength="1000"
                                placeholder="Enter additional observations or remarks..."
                                class="w-full rounded-xl border-gray-300
                                       bg-white
                                       focus:border-[#0E4C6B]
                                       focus:ring-[#0E4C6B]">{{ old('schedule_id') == $schedule->id ? old('remarks') : ($monitoring?->remarks ?? '') }}</textarea>

                        </div>

                    </div>

                    {{-- FORM FOOTER --}}
                    <div class="flex flex-col sm:flex-row
                                sm:items-center sm:justify-between
                                gap-4 pt-3 border-t border-gray-200">

                        <p class="text-xs text-gray-500">

                            @if($monitoring)

                                Saving changes will create an entry
                                in the attendance audit history.

                            @else

                                This instructor has not been checked yet.

                            @endif

                        </p>

                        <button
                            type="submit"
                            class="w-full sm:w-auto inline-flex
                                   items-center justify-center gap-2
                                   bg-[#0E2958] hover:bg-[#163A74]
                                   text-white font-semibold
                                   px-6 py-3 rounded-xl
                                   transition shadow-sm">

                            <x-heroicon-o-check-circle class="w-5 h-5"/>

                            {{ $monitoring ? 'Update Attendance' : 'Save Attendance' }}

                        </button>

                    </div>

                </form>

            </div>

        @else

            <div class="border-t border-gray-100 bg-gray-50/70 p-5">

                <p class="text-sm text-gray-500">
                    You have view-only access to Room Monitoring.
                </p>

            </div>

        @endif

        {{-- CHANGE HISTORY --}}
        @if($monitoring && $monitoring->histories->count())

            <div class="border-t border-gray-100 p-5 sm:p-6">

                <p class="text-sm font-bold text-gray-700 mb-3">
                    Recent Changes
                </p>

                <div class="space-y-3">

                    @foreach($monitoring->histories->sortByDesc('changed_at')->take(3) as $history)

                        <div class="flex flex-col sm:flex-row
                                    sm:items-center sm:justify-between
                                    gap-1 text-sm">

                            <p class="text-gray-700">

                                <span class="font-semibold">
                                    {{ ucfirst($history->old_status) }}
                                    →
                                    {{ ucfirst($history->new_status) }}
                                </span>

                                <span class="text-gray-400">
                                    by
                                </span>

                                {{ $history->changedBy->first_name ?? 'Unknown' }}
                                {{ $history->changedBy->last_name ?? '' }}

                            </p>

                            <span class="text-xs text-gray-400">
                                {{ $history->changed_at?->format('M d, Y g:i A') }}
                            </span>

                        </div>

                    @endforeach

                </div>

            </div>

        @endif

    </div>

@empty

    <div class="bg-white rounded-2xl border border-gray-200
                shadow-sm p-12 text-center">

        <div class="w-16 h-16 rounded-2xl bg-gray-100
                    flex items-center justify-center
                    mx-auto mb-4">

            <x-heroicon-o-calendar-days
                class="w-8 h-8 text-gray-400"/>

        </div>

        <h3 class="text-lg font-semibold text-gray-700">
            No scheduled classes
        </h3>

        <p class="text-sm text-gray-500 mt-1">
            There are no classes assigned to this room on
            {{ $selectedDate->format('F d, Y') }}.
        </p>

        <p class="text-xs text-gray-400 mt-2">
            No attendance record is required when there is no scheduled class.
        </p>

    </div>

@endforelse


    </div>

</div>

@endsection
