@extends('layouts.admin')

@section('title', 'Reports')

@section('content')

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-8">

        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                Reports & Analytics
            </h1>

            <p class="mt-1 text-slate-500">
                Monitor schedules, classroom usage, instructors, and campus resources.
            </p>
        </div>

        <div class="text-sm text-slate-500">
            Updated {{ now()->format('F d, Y • h:i A') }}
        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- CURRENT FILTER VALUES --}}
    {{-- ========================================================= --}}

    @php

        $usagePeriod = request('usage_period', 'today');

        $roomSort = request('room_sort', 'most');

        $buildingSort = request('building_sort', 'most');


        switch ($usagePeriod) {

            case 'week':

                $selectedUsage = $weeklyUsage;
                $selectedHours = $weeklyHours;
                $periodLabel = 'This Week';

                break;


            case 'month':

                $selectedUsage = $monthlyUsage;
                $selectedHours = $monthlyHours;
                $periodLabel = 'This Month';

                break;


            case 'year':

                $selectedUsage = $yearlyUsage;
                $selectedHours = $yearlyHours;
                $periodLabel = 'This Year';

                break;


            default:

                $selectedUsage = $todayUsage;
                $selectedHours = $todayHours;
                $periodLabel = 'Today';

                break;

        }

    @endphp


    {{-- ========================================================= --}}
    {{-- ROOM USAGE ANALYTICS --}}
    {{-- ========================================================= --}}

    <div class="mb-8">

        <div class="mb-5">

            <h2 class="text-xl font-bold text-slate-800">
                Room Usage Analytics
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                View classroom usage based on the selected period.
            </p>

        </div>


        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Room Usage
                    </p>

                    <h2 class="mt-1 text-2xl font-bold text-slate-800">
                        {{ $periodLabel }}
                    </h2>

                </div>


                <form
                    method="GET"
                    action="{{ route('admin.reports') }}"
                    class="flex items-center gap-2">

                    <input
                        type="hidden"
                        name="room_sort"
                        value="{{ $roomSort }}">

                    <input
                        type="hidden"
                        name="building_sort"
                        value="{{ $buildingSort }}">

                    <select
                        name="usage_period"
                        onchange="this.form.submit()"
                        class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

                        <option
                            value="today"
                            {{ $usagePeriod === 'today' ? 'selected' : '' }}>
                            Today
                        </option>

                        <option
                            value="week"
                            {{ $usagePeriod === 'week' ? 'selected' : '' }}>
                            This Week
                        </option>

                        <option
                            value="month"
                            {{ $usagePeriod === 'month' ? 'selected' : '' }}>
                            This Month
                        </option>

                        <option
                            value="year"
                            {{ $usagePeriod === 'year' ? 'selected' : '' }}>
                            This Year
                        </option>

                    </select>

                </form>

            </div>


            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">


                {{-- SESSIONS --}}

                <div class="rounded-xl bg-blue-50 border border-blue-100 p-5">

                    <p class="text-sm text-blue-600">
                        Room Sessions
                    </p>

                    <p class="mt-2 text-3xl font-bold text-blue-800">
                        {{ $selectedUsage }}
                    </p>

                    <p class="mt-1 text-sm text-blue-600">
                        recorded sessions
                    </p>

                </div>


                {{-- HOURS --}}

                <div class="rounded-xl bg-green-50 border border-green-100 p-5">

                    <p class="text-sm text-green-600">
                        Usage Hours
                    </p>

                    <p class="mt-2 text-3xl font-bold text-green-800">
                        {{ number_format($selectedHours, 1) }}
                    </p>

                    <p class="mt-1 text-sm text-green-600">
                        total hours used
                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SCHEDULE STATISTICS --}}
    {{-- ========================================================= --}}

    <div class="mb-8">

        <div class="mb-5">

            <h2 class="text-xl font-bold text-slate-800">
                Schedule Overview
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Current status of schedules in the system.
            </p>

        </div>


        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-5">


            {{-- TOTAL --}}

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">

                <p class="text-sm text-slate-500">
                    Total Schedules
                </p>

                <p class="mt-2 text-3xl font-bold text-slate-800">
                    {{ $totalSchedules }}
                </p>

            </div>


            {{-- SCHEDULED --}}

            <div class="bg-white rounded-2xl border border-green-100 shadow-sm p-5">

                <p class="text-sm text-green-600">
                    Scheduled
                </p>

                <p class="mt-2 text-3xl font-bold text-green-700">
                    {{ $scheduled }}
                </p>

            </div>


            {{-- IN PROGRESS --}}

            <div class="bg-white rounded-2xl border border-yellow-100 shadow-sm p-5">

                <p class="text-sm text-yellow-600">
                    In Progress
                </p>

                <p class="mt-2 text-3xl font-bold text-yellow-700">
                    {{ $inProgress }}
                </p>

            </div>


            {{-- COMPLETED --}}

            <div class="bg-white rounded-2xl border border-blue-100 shadow-sm p-5">

                <p class="text-sm text-blue-600">
                    Completed
                </p>

                <p class="mt-2 text-3xl font-bold text-blue-700">
                    {{ $completed }}
                </p>

            </div>


            {{-- CANCELLED --}}

            <div class="bg-white rounded-2xl border border-red-100 shadow-sm p-5">

                <p class="text-sm text-red-600">
                    Cancelled
                </p>

                <p class="mt-2 text-3xl font-bold text-red-700">
                    {{ $cancelled }}
                </p>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- CAMPUS OVERVIEW --}}
    {{-- ========================================================= --}}

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">


        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

            <p class="text-sm text-slate-500">
                Buildings
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-800">
                {{ $totalBuildings }}
            </p>

        </div>


        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

            <p class="text-sm text-slate-500">
                Rooms
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-800">
                {{ $totalRooms }}
            </p>

        </div>


        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

            <p class="text-sm text-slate-500">
                Active Instructors
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-800">
                {{ $totalInstructors }}
            </p>

        </div>


        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

            <p class="text-sm text-slate-500">
                Subjects
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-800">
                {{ $subjectCount }}
            </p>

        </div>

    </div>


   


    {{-- ========================================================= --}}
    {{-- ROOM + BUILDING RANKINGS --}}
    {{-- ========================================================= --}}

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">


        {{-- ===================================================== --}}
        {{-- ROOM RANKING --}}
        {{-- ===================================================== --}}

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-slate-200">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                    <div>

                        <h2 class="text-xl font-bold text-slate-800">
                            Room Usage Ranking
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Compare rooms based on recorded usage.
                        </p>

                    </div>


                    <form
    method="GET"
    action="{{ route('admin.reports') }}#usage-rankings">

                        <input
                            type="hidden"
                            name="usage_period"
                            value="{{ $usagePeriod }}">

                        <input
                            type="hidden"
                            name="building_sort"
                            value="{{ $buildingSort }}">
                        
                        <input
    type="hidden"
    name="page"
    value="{{ request('page', 1) }}">    


                        <div class="relative">

    <select
        name="room_sort"
        onchange="this.form.submit()"
        class="appearance-none cursor-pointer rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 pl-10 pr-10 py-2.5 text-sm font-semibold text-slate-700 outline-none transition focus:border-[#0E4C6B] focus:ring-2 focus:ring-[#0E4C6B]/20">

        <option
            value="most"
            {{ $roomSort === 'most' ? 'selected' : '' }}>
            Most Used
        </option>

        <option
            value="least"
            {{ $roomSort === 'least' ? 'selected' : '' }}>
            Least Used
        </option>

    </select>


    <x-heroicon-o-arrows-up-down
        class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#0E4C6B]"/>


    <x-heroicon-o-chevron-down
        class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"/>

</div>

                    </form>

                </div>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-slate-50">

                        <tr>

                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">
                                Room
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">
                                Building
                            </th>

                            <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase">
                                Sessions
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @forelse($roomUsage as $usage)

                            <tr class="hover:bg-slate-50 transition">

                                <td class="px-6 py-4">

                                    @if($usage->room)

                                        <p class="font-semibold text-slate-800">
                                            {{ $usage->room->room_name }}
                                        </p>

                                        <p class="text-xs text-slate-500 mt-1">
                                            Room {{ $usage->room->room_number }}
                                        </p>

                                    @else

                                        <span class="text-red-500">
                                            Room unavailable
                                        </span>

                                    @endif

                                </td>


                                <td class="px-6 py-4 text-sm text-slate-600">

                                    @if($usage->room && $usage->room->building)

                                        {{ $usage->room->building->building_name }}

                                    @else

                                        —

                                    @endif

                                </td>


                                <td class="px-6 py-4 text-right">

                                    <span class="inline-flex items-center justify-center min-w-10 rounded-full bg-blue-100 px-3 py-1 text-xs font-bold text-blue-700">

                                        {{ $usage->total_usage }}

                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="3"
                                    class="px-6 py-10 text-center text-slate-500">

                                    No room usage data available yet.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- BUILDING RANKING --}}
        {{-- ===================================================== --}}

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-slate-200">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                    <div>

                        <h2 class="text-xl font-bold text-slate-800">
                            Building Usage Ranking
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Compare buildings based on recorded room usage.
                        </p>

                    </div>


                   <form
    method="GET"
    action="{{ route('admin.reports') }}#usage-rankings">

                        <input
                            type="hidden"
                            name="usage_period"
                            value="{{ $usagePeriod }}">

                        <input
                            type="hidden"
                            name="room_sort"
                            value="{{ $roomSort }}">
<input
    type="hidden"
    name="page"
    value="{{ request('page', 1) }}">

                        <div class="relative">

    <select
        name="building_sort"
        onchange="this.form.submit()"
        class="appearance-none cursor-pointer rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 pl-10 pr-10 py-2.5 text-sm font-semibold text-slate-700 outline-none transition focus:border-[#0E4C6B] focus:ring-2 focus:ring-[#0E4C6B]/20">

        <option
            value="most"
            {{ $buildingSort === 'most' ? 'selected' : '' }}>
            Most Used
        </option>

        <option
            value="least"
            {{ $buildingSort === 'least' ? 'selected' : '' }}>
            Least Used
        </option>

    </select>


    <x-heroicon-o-arrows-up-down
        class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#0E4C6B]"/>


    <x-heroicon-o-chevron-down
        class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"/>

</div>

                    </form>

                </div>

            </div>


            <div class="p-6">

                @php

                    $maxBuildingUsage =
                        $buildingUsage->max('total_usage') ?: 1;

                @endphp


                @forelse($buildingUsage as $usage)

                    @if($usage->room && $usage->room->building)

                        <div class="mb-6 last:mb-0">

                            <div class="flex items-center justify-between mb-2">

                                <span class="font-medium text-slate-700">

                                    {{ $usage->room->building->building_name }}

                                </span>


                                <span class="text-sm font-bold text-slate-800">

                                    {{ $usage->total_usage }}

                                    <span class="font-normal text-slate-500">
                                        sessions
                                    </span>

                                </span>

                            </div>


                            <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">

                                <div
                                    class="h-full bg-[#0E4C6B] rounded-full"
                                    style="width: {{ ($usage->total_usage / $maxBuildingUsage) * 100 }}%">
                                </div>

                            </div>


                            <p class="mt-2 text-xs text-slate-500">

                                {{ number_format(($usage->total_minutes ?? 0) / 60, 1) }}
                                hours of recorded usage

                            </p>

                        </div>

                    @endif

                @empty

                    <div class="py-10 text-center">

                        <x-heroicon-o-building-office-2 class="w-10 h-10 mx-auto text-slate-300"/>

                        <p class="mt-3 text-slate-500">
                            No building usage data available yet.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- CLASSES BY DAY + INSTRUCTOR LOAD --}}
    {{-- ========================================================= --}}

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">


        {{-- CLASSES BY DAY --}}

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-slate-200">

                <h2 class="text-xl font-bold text-slate-800">
                    Classes by Day
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Number of scheduled classes for each day.
                </p>

            </div>


            <div class="p-6">

                @php

                    $maxDayTotal =
                        $classesByDay->max('total') ?: 1;

                @endphp


                @forelse($classesByDay as $day)

                    <div class="mb-5 last:mb-0">

                        <div class="flex items-center justify-between mb-2">

                            <span class="text-sm font-medium text-slate-700">
                                {{ $day->day }}
                            </span>

                            <span class="text-sm font-bold text-slate-800">
                                {{ $day->total }}
                            </span>

                        </div>


                        <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">

                            <div
                                class="h-full bg-[#0E4C6B] rounded-full"
                                style="width: {{ ($day->total / $maxDayTotal) * 100 }}%">
                            </div>

                        </div>

                    </div>

                @empty

                    <div class="py-10 text-center text-slate-500">
                        No schedule data available.
                    </div>

                @endforelse

            </div>

        </div>


        {{-- INSTRUCTOR LOAD --}}

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-slate-200">

                <h2 class="text-xl font-bold text-slate-800">
                    Instructor Schedule Load
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Instructors with the highest number of assigned classes.
                </p>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-[#0E4C6B] text-white">

                        <tr>

                            <th class="px-6 py-4 text-left">
                                #
                            </th>

                            <th class="px-6 py-4 text-left">
                                Instructor
                            </th>

                            <th class="px-6 py-4 text-right">
                                Classes
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @forelse($instructorLoad as $index => $load)

                            <tr class="hover:bg-slate-50 transition">

                                <td class="px-6 py-4 text-slate-500">
                                    {{ $index + 1 }}
                                </td>


                                <td class="px-6 py-4">

                                    @if($load->instructor)

                                        <p class="font-semibold text-slate-800">

                                            {{ $load->instructor->first_name }}
                                            {{ $load->instructor->last_name }}

                                        </p>

                                        <p class="text-xs text-slate-500 mt-1">

                                            {{ $load->instructor->employee_id }}

                                        </p>

                                    @else

                                        <span class="text-red-500">
                                            Instructor unavailable
                                        </span>

                                    @endif

                                </td>


                                <td class="px-6 py-4 text-right">

                                    <span class="inline-flex items-center justify-center min-w-10 rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">

                                        {{ $load->total }}

                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="3"
                                    class="px-6 py-10 text-center text-slate-500">

                                    No instructor schedule data available.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SCHEDULE STATUS --}}
    {{-- ========================================================= --}}

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

        <h2 class="text-xl font-bold text-slate-800">
            Schedule Status Summary
        </h2>

        <p class="mt-1 text-sm text-slate-500 mb-6">
            Current status of all schedules in the system.
        </p>


        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">


            {{-- SCHEDULED --}}

            <div class="rounded-xl bg-green-50 border border-green-100 p-5">

                <p class="text-sm text-green-700">
                    Scheduled
                </p>

                <p class="mt-2 text-2xl font-bold text-green-800">
                    {{ $scheduled }}
                </p>

            </div>


            {{-- IN PROGRESS --}}

            <div class="rounded-xl bg-yellow-50 border border-yellow-100 p-5">

                <p class="text-sm text-yellow-700">
                    In Progress
                </p>

                <p class="mt-2 text-2xl font-bold text-yellow-800">
                    {{ $inProgress }}
                </p>

            </div>


            {{-- COMPLETED --}}

            <div class="rounded-xl bg-blue-50 border border-blue-100 p-5">

                <p class="text-sm text-blue-700">
                    Completed
                </p>

                <p class="mt-2 text-2xl font-bold text-blue-800">
                    {{ $completed }}
                </p>

            </div>


            {{-- CANCELLED --}}

            <div class="rounded-xl bg-red-50 border border-red-100 p-5">

                <p class="text-sm text-red-700">
                    Cancelled
                </p>

                <p class="mt-2 text-2xl font-bold text-red-800">
                    {{ $cancelled }}
                </p>

            </div>

        </div>

    </div>


</div>

@endsection