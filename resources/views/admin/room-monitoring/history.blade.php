
@extends('layouts.admin')

@section('title', 'Attendance History')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    <!-- HEADER -->
    <div>
        <a
            href="{{ route('admin.room-monitoring.rooms.show', [
                'room' => $room->id,
                'date' => now()->toDateString()
            ]) }}"
            class="inline-flex items-center gap-2 text-sm font-semibold
                   text-[#0E4C6B] hover:text-[#0B3D56] transition">

            <x-heroicon-o-arrow-left class="w-4 h-4"/>
            Back to Room Monitoring

        </a>

        <div class="mt-5">
            <h1 class="text-2xl font-extrabold text-[#0E2958]">
                Attendance History
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Review instructor attendance records and verification changes.
            </p>
        </div>
    </div>


    <!-- ROOM INFORMATION -->
    <div class="bg-[#0E2958] rounded-2xl p-6 text-white shadow-sm">

        <div class="flex items-center gap-4">

            <div class="w-12 h-12 rounded-xl bg-white/10
                        flex items-center justify-center flex-shrink-0">

                <x-heroicon-o-building-office-2 class="w-6 h-6"/>

            </div>

            <div>
                <p class="text-sm text-blue-200">
                    {{ $room->building->building_name }}
                </p>

                <h2 class="text-2xl font-extrabold">
                    {{ $room->room_number }}
                </h2>

                <p class="text-sm text-blue-100">
                    {{ $room->room_name }}
                </p>
            </div>

        </div>

    </div>


    <!-- SUMMARY -->
    @php
        $presentCount = $monitorings->where('attendance_status', 'present')->count();
        $absentCount = $monitorings->where('attendance_status', 'absent')->count();
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Records</p>
            <p class="text-3xl font-extrabold text-[#0E2958] mt-2">
                {{ $monitorings->count() }}
            </p>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-xl p-5">
            <p class="text-sm text-green-700">Present</p>
            <p class="text-3xl font-extrabold text-green-700 mt-2">
                {{ $presentCount }}
            </p>
        </div>

        <div class="bg-red-50 border border-red-200 rounded-xl p-5">
            <p class="text-sm text-red-700">Absent</p>
            <p class="text-3xl font-extrabold text-red-700 mt-2">
                {{ $absentCount }}
            </p>
        </div>

    </div>


    <!-- HISTORY RECORDS -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

        <div class="p-5 sm:p-6 border-b border-gray-100">

            <h2 class="text-lg font-bold text-[#0E2958]">
                Recorded Attendance
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Attendance records are grouped by monitoring date.
            </p>

        </div>


        @forelse($monitorings->groupBy(function ($monitoring) {
            return $monitoring->monitoring_date->format('Y-m-d');
        }) as $monitoringDate => $dateRecords)

            <div class="border-b border-gray-100 last:border-b-0">

                <!-- DATE HEADER -->
                <div class="bg-gray-50 px-5 sm:px-6 py-4
                            flex flex-col sm:flex-row
                            sm:items-center sm:justify-between gap-2">

                    <div class="flex items-center gap-2">

                        <x-heroicon-o-calendar-days
                            class="w-5 h-5 text-[#0E4C6B]"/>

                        <h3 class="font-bold text-[#0E2958]">
                            {{ \Carbon\Carbon::parse($monitoringDate)->format('F d, Y') }}
                        </h3>

                    </div>

                    <span class="text-xs font-semibold text-gray-500">
                        {{ $dateRecords->count() }}
                        {{ $dateRecords->count() === 1 ? 'record' : 'records' }}
                    </span>

                </div>


                <!-- RECORDS FOR THIS DATE -->
                <div class="p-5 sm:p-6 space-y-4">

                    @foreach($dateRecords as $monitoring)

                        <div class="border border-gray-200 rounded-xl p-4 sm:p-5">

                            <div class="flex flex-col lg:flex-row
                                        lg:items-start lg:justify-between gap-4">

                                <!-- CLASS INFORMATION -->
                                <div class="min-w-0">

                                    <h4 class="font-bold text-[#0E2958]">
                                        {{ $monitoring->schedule->subject_name ?? 'Unknown Subject' }}
                                    </h4>

                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $monitoring->schedule->subject_code ?? 'No subject code' }}
                                    </p>

                                    <p class="text-sm text-gray-700 mt-3">

                                        Instructor:

                                        <span class="font-semibold">
                                            {{ $monitoring->instructor->first_name ?? 'Unknown' }}
                                            {{ $monitoring->instructor->last_name ?? '' }}
                                        </span>

                                    </p>

                                    <p class="text-sm text-gray-500 mt-1">

                                        Class time:

                                        @if($monitoring->schedule)
                                            {{ date('g:i A', strtotime($monitoring->schedule->start_time)) }}
                                            –
                                            {{ date('g:i A', strtotime($monitoring->schedule->end_time)) }}
                                        @else
                                            Not available
                                        @endif

                                    </p>

                                </div>


                                <!-- STATUS -->
                                <div class="flex-shrink-0">

                                    @if($monitoring->attendance_status === 'present')

                                        <span class="inline-flex items-center gap-2
                                                     bg-green-50 text-green-700
                                                     border border-green-200
                                                     px-3 py-2 rounded-xl
                                                     text-sm font-bold">

                                            <x-heroicon-o-check-circle class="w-5 h-5"/>
                                            Present

                                        </span>

                                    @else

                                        <span class="inline-flex items-center gap-2
                                                     bg-red-50 text-red-700
                                                     border border-red-200
                                                     px-3 py-2 rounded-xl
                                                     text-sm font-bold">

                                            <x-heroicon-o-x-circle class="w-5 h-5"/>
                                            Absent

                                        </span>

                                    @endif

                                </div>

                            </div>


                            <!-- VERIFICATION DETAILS -->
                            <div class="mt-4 pt-4 border-t border-gray-100
                                        grid grid-cols-1 sm:grid-cols-2 gap-3">

                                <div>
                                    <p class="text-xs text-gray-500">
                                        Verified by
                                    </p>

                                    <p class="text-sm font-semibold text-gray-800 mt-1">
                                        {{ $monitoring->verifier->first_name ?? 'Unknown' }}
                                        {{ $monitoring->verifier->last_name ?? '' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs text-gray-500">
                                        Last verified
                                    </p>

                                    <p class="text-sm font-semibold text-gray-800 mt-1">
                                        {{ $monitoring->verified_at?->format('M d, Y - g:i A') ?? 'Not available' }}
                                    </p>
                                </div>

                            </div>


                            @if($monitoring->remarks)

                                <div class="mt-4 bg-gray-50 rounded-lg p-3">

                                    <p class="text-xs font-semibold text-gray-500">
                                        Remarks
                                    </p>

                                    <p class="text-sm text-gray-700 mt-1">
                                        {{ $monitoring->remarks }}
                                    </p>

                                </div>

                            @endif


                            <!-- AUDIT HISTORY -->
                            @if($monitoring->histories->count())

                                <div class="mt-5 pt-4 border-t border-gray-100">

                                    <h5 class="text-sm font-bold text-[#0E2958] mb-3">
                                        Attendance Changes
                                    </h5>

                                    <div class="space-y-3">

                                        @foreach($monitoring->histories->sortByDesc('changed_at') as $history)

                                            <div class="flex gap-3">

                                                <div class="mt-1 flex-shrink-0">

                                                    <div class="w-8 h-8 rounded-full
                                                                bg-amber-50
                                                                flex items-center justify-center">

                                                        <x-heroicon-o-pencil-square
                                                            class="w-4 h-4 text-amber-600"/>

                                                    </div>

                                                </div>


                                                <div class="min-w-0 flex-1">

                                                    <p class="text-sm text-gray-800">

                                                        Status changed from

                                                        <span class="font-bold">
                                                            {{ ucfirst($history->old_status) }}
                                                        </span>

                                                        to

                                                        <span class="font-bold
                                                            {{ $history->new_status === 'present'
                                                                ? 'text-green-700'
                                                                : 'text-red-700' }}">

                                                            {{ ucfirst($history->new_status) }}

                                                        </span>

                                                    </p>

                                                    <p class="text-xs text-gray-500 mt-1">

                                                        Changed by:

                                                        <span class="font-semibold text-gray-700">

                                                            {{ $history->changedBy->first_name ?? 'Unknown' }}
                                                            {{ $history->changedBy->last_name ?? '' }}

                                                        </span>

                                                    </p>

                                                    <p class="text-xs text-gray-400 mt-1">
                                                        {{ $history->changed_at?->format('F d, Y - g:i A') }}
                                                    </p>


                                                    @if($history->old_remarks || $history->new_remarks)

                                                        <div class="mt-2 text-xs text-gray-600 space-y-1">

                                                            @if($history->old_remarks !== $history->new_remarks)

                                                                <p>
                                                                    <span class="font-semibold">Previous remarks:</span>
                                                                    {{ $history->old_remarks ?: 'None' }}
                                                                </p>

                                                                <p>
                                                                    <span class="font-semibold">Updated remarks:</span>
                                                                    {{ $history->new_remarks ?: 'None' }}
                                                                </p>

                                                            @endif

                                                        </div>

                                                    @endif

                                                </div>

                                            </div>

                                        @endforeach

                                    </div>

                                </div>

                            @else

                                <div class="mt-4 pt-3 border-t border-gray-100">

                                    <p class="text-xs text-gray-400">
                                        No changes have been recorded for this attendance entry.
                                    </p>

                                </div>

                            @endif

                        </div>

                    @endforeach

                </div>

            </div>

        @empty

            <div class="p-12 text-center">

                <div class="w-16 h-16 rounded-2xl bg-gray-100
                            flex items-center justify-center
                            mx-auto mb-4">

                    <x-heroicon-o-clipboard-document-list
                        class="w-8 h-8 text-gray-400"/>

                </div>

                <h3 class="text-lg font-semibold text-gray-700">
                    No attendance records yet
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Attendance records will appear here once HR verifies instructors.
                </p>

            </div>

        @endforelse

    </div>

</div>

@endsection
