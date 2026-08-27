@extends('layouts.admin')

@section('content')

<div class="max-w-7xl mx-auto px-6 py-8">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">

        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                Import Schedules
            </h1>

            <p class="mt-1 text-slate-500">
                Review the schedules before adding them to the system.
            </p>
        </div>

        <a
            href="{{ route('admin.schedules') }}"
            class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-medium text-slate-700 hover:bg-slate-100 transition">

            ← Cancel

        </a>

    </div>


    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">

        {{-- Total --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

            <p class="text-sm text-slate-500">
                Total Rows
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-800">
                {{ $total }}
            </p>

        </div>


        {{-- Ready --}}
        <div class="bg-white rounded-2xl border border-green-200 shadow-sm p-6">

            <p class="text-sm text-slate-500">
                Ready to Import
            </p>

            <p class="mt-2 text-3xl font-bold text-green-600">
                {{ $ready }}
            </p>

        </div>


        {{-- Duplicates --}}
        <div class="bg-white rounded-2xl border border-yellow-200 shadow-sm p-6">

            <p class="text-sm text-slate-500">
                Duplicates
            </p>

            <p class="mt-2 text-3xl font-bold text-yellow-600">
                {{ $duplicates }}
            </p>

        </div>


        {{-- Invalid --}}
        <div class="bg-white rounded-2xl border border-red-200 shadow-sm p-6">

            <p class="text-sm text-slate-500">
                Invalid
            </p>

            <p class="mt-2 text-3xl font-bold text-red-600">
                {{ $invalid }}
            </p>

        </div>

    </div>


    {{-- Preview Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        {{-- Table Header --}}
        <div class="px-6 py-5 border-b border-slate-200">

            <h2 class="text-xl font-bold text-slate-800">
                Import Preview
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Only rows marked as ready will be imported.
            </p>

        </div>


        {{-- Table --}}
        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-[#0E4C6B] text-white">

                    <tr>

                        <th class="px-4 py-4 text-left font-semibold">
                            #
                        </th>

                        <th class="px-4 py-4 text-left font-semibold">
                            Instructor
                        </th>

                        <th class="px-4 py-4 text-left font-semibold">
                            Subject Code
                        </th>

                        <th class="px-4 py-4 text-left font-semibold">
                            Subject Name
                        </th>

                        <th class="px-4 py-4 text-left font-semibold">
                            Room
                        </th>

                        <th class="px-4 py-4 text-left font-semibold">
                            Day
                        </th>

                        <th class="px-4 py-4 text-left font-semibold">
                            Time
                        </th>

                        <th class="px-4 py-4 text-left font-semibold">
                            Semester
                        </th>

                        <th class="px-4 py-4 text-left font-semibold">
                            School Year
                        </th>

                        <th class="px-4 py-4 text-left font-semibold">
                            Status
                        </th>

                        <th class="px-4 py-4 text-left font-semibold">
                            Remarks
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-200">

                    @forelse($schedules as $index => $schedule)

                        <tr class="hover:bg-slate-50 transition">

                            {{-- Number --}}
                            <td class="px-4 py-4 text-slate-500">
                                {{ $index + 1 }}
                            </td>


                            {{-- Instructor --}}
                            <td class="px-4 py-4">

                                @if($schedule['instructor_name'])

                                    <div class="font-medium text-slate-800">
                                        {{ $schedule['instructor_name'] }}
                                    </div>

                                    <div class="text-xs text-slate-500 mt-1">
                                        {{ $schedule['employee_id'] }}
                                    </div>

                                @else

                                    <span class="text-slate-400">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Subject Code --}}
                            <td class="px-4 py-4 font-medium text-slate-800">

                                {{ $schedule['subject_code'] ?: '—' }}

                            </td>


                            {{-- Subject Name --}}
                            <td class="px-4 py-4 text-slate-700">

                                {{ $schedule['subject_name'] ?: '—' }}

                            </td>


                            {{-- Room --}}
                            <td class="px-4 py-4">

                                @if($schedule['room_name'])

                                    <div class="font-medium text-slate-800">
                                        {{ $schedule['room_name'] }}
                                    </div>

                                    <div class="text-xs text-slate-500 mt-1">
                                        Room {{ $schedule['room_number'] }}
                                    </div>

                                @else

                                    <span class="text-slate-400">
                                        {{ $schedule['room_input'] ?: '—' }}
                                    </span>

                                @endif

                            </td>


                            {{-- Day --}}
                            <td class="px-4 py-4 text-slate-700">

                                {{ $schedule['day'] ?: '—' }}

                            </td>


                            {{-- Time --}}
                            <td class="px-4 py-4 whitespace-nowrap">

                                @if($schedule['start_time'] && $schedule['end_time'])

                                    {{ \Carbon\Carbon::parse($schedule['start_time'])->format('g:i A') }}

                                    <span class="text-slate-400">
                                        -
                                    </span>

                                    {{ \Carbon\Carbon::parse($schedule['end_time'])->format('g:i A') }}

                                @else

                                    <span class="text-slate-400">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Semester --}}
                            <td class="px-4 py-4 text-slate-700">

                                {{ $schedule['semester'] ?: '—' }}

                            </td>


                            {{-- School Year --}}
                            <td class="px-4 py-4 text-slate-700">

                                {{ $schedule['school_year'] ?: '—' }}

                            </td>


                            {{-- Status --}}
                            <td class="px-4 py-4">

                                @if($schedule['status'] === 'new')

                                    <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                        Ready
                                    </span>

                                @elseif($schedule['status'] === 'duplicate')

                                    <span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                        Duplicate
                                    </span>

                                @else

                                    <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                        Invalid
                                    </span>

                                @endif

                            </td>


                            {{-- Remarks --}}
                            <td class="px-4 py-4 text-slate-600">

                                {{ $schedule['remarks'] }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="11"
                                class="px-6 py-12 text-center text-slate-500">

                                No schedules found in the uploaded file.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Bottom Actions --}}
        <div class="border-t border-slate-200 px-6 py-5">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">

                <div class="text-sm text-slate-600">

                    @if($ready > 0)

                        <span class="font-semibold text-green-600">
                            {{ $ready }}
                        </span>

                        schedule(s) are ready to be imported.

                    @else

                        <span class="font-semibold text-red-600">
                            No schedules
                        </span>

                        are ready to be imported.

                    @endif

                </div>


                <div class="flex items-center gap-3">

                    {{-- Cancel --}}
                    <form
                        method="POST"
                        action="{{ route('admin.schedules.cancel') }}">

                        @csrf

                        <button
                            type="submit"
                            class="rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-medium text-slate-700 hover:bg-slate-100 transition">

                            Cancel

                        </button>

                    </form>


                    {{-- Confirm --}}
                    <form
                        method="POST"
                        action="{{ route('admin.schedules.store') }}">

                        @csrf

                        <button
                            type="submit"
                            @disabled($ready === 0)
                            class="rounded-xl bg-[#0E4C6B] px-6 py-3 text-sm font-semibold text-white hover:bg-[#0B3D56] transition disabled:cursor-not-allowed disabled:opacity-50">

                            Confirm Import

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection