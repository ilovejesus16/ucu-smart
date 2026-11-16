
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
            @endphp

            <div class="bg-white rounded-2xl border border-gray-200
                        shadow-sm p-5 sm:p-6 mb-5">

                <!-- Schedule Header -->

                <div class="flex flex-col sm:flex-row
                            sm:items-start sm:justify-between gap-4">

                    <div class="flex items-start gap-3 min-w-0">

                        <div class="w-11 h-11 rounded-xl
                                    bg-[#0E4C6B]/10
                                    flex items-center justify-center
                                    flex-shrink-0">

                            <x-heroicon-o-academic-cap
                                class="w-6 h-6 text-[#0E4C6B]"/>

                        </div>

                        <div class="min-w-0">

                            <h3 class="text-lg font-bold text-[#0E2958]">
                                {{ $schedule->subject_name }}
                            </h3>

                            <p class="text-sm text-gray-500 mt-0.5">
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


                    <!-- Attendance Status -->

                    
<!-- Class Status and Attendance Status -->

<div class="flex flex-col items-start gap-3">

    <!-- CLASS ACTIVITY STATUS -->
    <div>
        <p class="text-xs font-semibold text-gray-500 mb-1">
            Class Status
        </p>

        @if($schedule->classDisplayStatus == 'upcoming')

            <span class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 border border-blue-200 px-3 py-2 rounded-xl text-sm font-bold">
                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                Upcoming
            </span>

        @elseif($schedule->classDisplayStatus == 'scheduled')

            <span class="inline-flex items-center gap-2 bg-yellow-50 text-yellow-700 border border-yellow-200 px-3 py-2 rounded-xl text-sm font-bold">
                <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                Scheduled
            </span>

        @elseif($schedule->classDisplayStatus == 'in_progress')

            <span class="inline-flex items-center gap-2 bg-red-50 text-red-700 border border-red-200 px-3 py-2 rounded-xl text-sm font-bold">
                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                In Progress
            </span>

        @elseif($schedule->classDisplayStatus == 'completed')

            <span class="inline-flex items-center gap-2 bg-green-50 text-green-700 border border-green-200 px-3 py-2 rounded-xl text-sm font-bold">
                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                Completed
            </span>

        @elseif($schedule->classDisplayStatus == 'missed')

            <span class="inline-flex items-center gap-2 bg-gray-100 text-gray-600 border border-gray-200 px-3 py-2 rounded-xl text-sm font-bold">
                <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                Missed
            </span>

        @elseif($schedule->classDisplayStatus == 'cancelled')

            <span class="inline-flex items-center gap-2 bg-gray-100 text-gray-600 border border-gray-200 px-3 py-2 rounded-xl text-sm font-bold">
                <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                Cancelled
            </span>

        @else

            <span class="inline-flex items-center gap-2 bg-gray-100 text-gray-500 border border-gray-200 px-3 py-2 rounded-xl text-sm font-bold">
                No Record
            </span>

        @endif
    </div>

    <!-- HR ATTENDANCE VERIFICATION -->
    <div>
        <p class="text-xs font-semibold text-gray-500 mb-1">
            HR Attendance
        </p>

        @if($monitoring)

            @if($monitoring->attendance_status === 'present')

                <span class="inline-flex items-center gap-2 bg-green-50 text-green-700 border border-green-200 px-3 py-2 rounded-xl text-sm font-bold">
                    <x-heroicon-o-check-circle class="w-5 h-5"/>
                    Present
                </span>

            @else

                <span class="inline-flex items-center gap-2 bg-red-50 text-red-700 border border-red-200 px-3 py-2 rounded-xl text-sm font-bold">
                    <x-heroicon-o-x-circle class="w-5 h-5"/>
                    Absent
                </span>

            @endif

        @else

            <span class="inline-flex items-center gap-2 bg-amber-50 text-amber-700 border border-amber-200 px-3 py-2 rounded-xl text-sm font-bold">
                <x-heroicon-o-clock class="w-5 h-5"/>
                Not checked
            </span>

        @endif
    </div>

</div>



                <!-- Class Time -->

                <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-3">

                    <div class="bg-gray-50 border border-gray-100
                                rounded-xl p-3">

                        <p class="text-xs text-gray-500">
                            Class Time
                        </p>

                        <p class="font-semibold text-gray-800 mt-1">

                            {{ date('g:i A', strtotime($schedule->start_time)) }}

                            –

                            {{ date('g:i A', strtotime($schedule->end_time)) }}

                        </p>

                    </div>


                    <div class="bg-gray-50 border border-gray-100
                                rounded-xl p-3">

                        <p class="text-xs text-gray-500">
                            Schedule Day
                        </p>

                        <p class="font-semibold text-gray-800 mt-1">
                            {{ $selectedDate->format('l') }}
                        </p>

                    </div>

                </div>


                <!-- Existing Verification Information -->

                @if($monitoring)

                    <div class="mt-5 bg-gray-50 border border-gray-100
                                rounded-xl p-4">

                        <p class="text-sm font-bold text-gray-700 mb-2">
                            Verification Details
                        </p>

                        <p class="text-sm text-gray-600">

                            Verified by:

                            <span class="font-semibold text-gray-800">

                                {{ $monitoring->verifier->first_name ?? 'Unknown' }}
                                {{ $monitoring->verifier->last_name ?? '' }}

                            </span>

                        </p>

                        <p class="text-sm text-gray-600 mt-1">

                            Date and time:

                            <span class="font-semibold text-gray-800">

                                {{ $monitoring->verified_at?->format('M d, Y - g:i A') ?? 'Not available' }}

                            </span>

                        </p>

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


                <!-- Attendance Form -->

                @if(auth()->user()->hasAdminPermission('room_monitoring', 'full'))

                    <form
                        method="POST"
                        action="{{ route('admin.room-monitoring.attendance.store', $room) }}"
                        class="mt-5 pt-5 border-t border-gray-100 space-y-4">

                        @csrf

                        <input
                            type="hidden"
                            name="schedule_id"
                            value="{{ $schedule->id }}">

                        <input
                            type="hidden"
                            name="date"
                            value="{{ $date }}">


                        <div>

                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Attendance Status
                            </label>

                            <select
                                name="attendance_status"
                                required
                                class="w-full rounded-xl border-gray-300
                                       focus:border-[#0E4C6B]
                                       focus:ring-[#0E4C6B]">

                                <option value="">
                                    Select attendance status
                                </option>

                                <option
                                    value="present"
                                    @selected(old('schedule_id') == $schedule->id
                                        ? old('attendance_status') === 'present'
                                        : ($monitoring?->attendance_status === 'present'))>

                                    Present

                                </option>

                                <option
                                    value="absent"
                                    @selected(old('schedule_id') == $schedule->id
                                        ? old('attendance_status') === 'absent'
                                        : ($monitoring?->attendance_status === 'absent'))>

                                    Absent

                                </option>

                            </select>

                        </div>


                        <div>

                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Remarks (Optional)
                            </label>

                            <textarea
                                name="remarks"
                                rows="2"
                                maxlength="1000"
                                placeholder="Enter additional observations or remarks..."
                                class="w-full rounded-xl border-gray-300
                                       focus:border-[#0E4C6B]
                                       focus:ring-[#0E4C6B]">{{ old('schedule_id') == $schedule->id ? old('remarks') : ($monitoring?->remarks ?? '') }}</textarea>

                        </div>


                        <div class="flex flex-col sm:flex-row
                                    sm:items-center sm:justify-between gap-3">

                            @if($monitoring)

                                <p class="text-xs text-gray-500">
                                    Saving changes will create an entry in the attendance audit history.
                                </p>

                            @else

                                <p class="text-xs text-gray-500">
                                    This instructor has not been checked yet.
                                </p>

                            @endif


                            <button
                                type="submit"
                                class="inline-flex items-center justify-center gap-2
                                       bg-[#0E2958]
                                       hover:bg-[#163A74]
                                       text-white font-semibold
                                       px-5 py-3 rounded-xl
                                       transition">

                                <x-heroicon-o-check-circle class="w-5 h-5"/>

                                {{ $monitoring ? 'Update Attendance' : 'Save Attendance' }}

                            </button>

                        </div>

                    </form>

                @else

                    <div class="mt-5 pt-4 border-t border-gray-100">

                        <p class="text-sm text-gray-500">
                            You have view-only access to Room Monitoring.
                        </p>

                    </div>

                @endif


                <!-- Change History Preview -->

                @if($monitoring && $monitoring->histories->count())

                    <div class="mt-5 pt-4 border-t border-gray-100">

                        <p class="text-sm font-bold text-gray-700 mb-3">
                            Recent Changes
                        </p>

                        @foreach($monitoring->histories->sortByDesc('changed_at')->take(3) as $history)

                            <div class="text-sm text-gray-600 mb-2">

                                <span class="font-semibold text-gray-800">

                                    {{ ucfirst($history->old_status) }}
                                    →
                                    {{ ucfirst($history->new_status) }}

                                </span>

                                <span class="text-gray-400">
                                    by
                                </span>

                                {{ $history->changedBy->first_name ?? 'Unknown' }}
                                {{ $history->changedBy->last_name ?? '' }}

                                <span class="text-gray-400">
                                    on {{ $history->changed_at?->format('M d, Y g:i A') }}
                                </span>

                            </div>

                        @endforeach

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
