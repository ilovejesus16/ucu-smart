@extends('layouts.admin')

@section('title', 'Reports & Analytics')

@section('content')
<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                Reports & Analytics
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Monitor classroom usage, schedule status, and system activity.
            </p>
        </div>

        <div class="text-sm text-slate-500">
            Updated {{ now()->format('F d, Y • h:i A') }}
        </div>
    </div>


    {{-- ========================================================= --}}
    {{-- 1. SCHEDULE STATUS SUMMARY --}}
    {{-- ========================================================= --}}

    <section class="mb-8">
        <div class="mb-5">
            <h2 class="text-xl font-bold text-slate-800">
                Schedule Status Summary
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Overview of all schedules currently recorded in the system.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4">

            {{-- Total --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <p class="text-sm font-medium text-slate-500">
                    Total Schedules
                </p>
                <p class="mt-3 text-3xl font-bold text-slate-800">
                    {{ $totalSchedules }}
                </p>
                <p class="mt-1 text-xs text-slate-500">
                    All recorded schedules
                </p>
            </div>

            {{-- Scheduled --}}
            <div class="bg-white rounded-2xl border border-green-100 shadow-sm p-5">
                <p class="text-sm font-medium text-green-600">
                    Scheduled
                </p>
                <p class="mt-3 text-3xl font-bold text-green-700">
                    {{ $scheduled }}
                </p>
                <p class="mt-1 text-xs text-slate-500">
                    Upcoming or planned
                </p>
            </div>

            {{-- In Progress --}}
            <div class="bg-white rounded-2xl border border-yellow-100 shadow-sm p-5">
                <p class="text-sm font-medium text-yellow-600">
                    In Progress
                </p>
                <p class="mt-3 text-3xl font-bold text-yellow-700">
                    {{ $inProgress }}
                </p>
                <p class="mt-1 text-xs text-slate-500">
                    Currently ongoing
                </p>
            </div>

            {{-- Completed --}}
            <div class="bg-white rounded-2xl border border-blue-100 shadow-sm p-5">
                <p class="text-sm font-medium text-blue-600">
                    Completed
                </p>
                <p class="mt-3 text-3xl font-bold text-blue-700">
                    {{ $completed }}
                </p>
                <p class="mt-1 text-xs text-slate-500">
                    Finished schedules
                </p>
            </div>

            {{-- Cancelled --}}
            <div class="bg-white rounded-2xl border border-red-100 shadow-sm p-5">
                <p class="text-sm font-medium text-red-600">
                    Cancelled
                </p>
                <p class="mt-3 text-3xl font-bold text-red-700">
                    {{ $cancelled }}
                </p>
                <p class="mt-1 text-xs text-slate-500">
                    Cancelled schedules
                </p>
            </div>

        </div>
    </section>


    {{-- ========================================================= --}}
    {{-- 2. ROOM USAGE ANALYTICS --}}
    {{-- ========================================================= --}}

    @php
        $usagePeriod = request('usage_period', 'today');

        $roomSort = request('room_sort', 'most');

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

    <section class="mb-8">
        <div class="mb-5">
            <h2 class="text-xl font-bold text-slate-800">
                Room Usage Analytics
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Review recorded classroom usage sessions and total hours.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-sm text-slate-500">
                        Selected Reporting Period
                    </p>
                    <h3 class="mt-1 text-xl font-bold text-slate-800">
                        {{ $periodLabel }}
                    </h3>
                </div>

                <form method="GET" action="{{ route('admin.reports') }}"
                      class="flex items-center gap-2">

                    <input type="hidden" name="room_sort" value="{{ $roomSort }}">

                    <select
                        name="usage_period"
                        onchange="this.form.submit()"
                        aria-label="Select room usage reporting period"
                        class="w-full sm:w-auto rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

                        <option value="today" {{ $usagePeriod === 'today' ? 'selected' : '' }}>
                            Today
                        </option>

                        <option value="week" {{ $usagePeriod === 'week' ? 'selected' : '' }}>
                            This Week
                        </option>

                        <option value="month" {{ $usagePeriod === 'month' ? 'selected' : '' }}>
                            This Month
                        </option>

                        <option value="year" {{ $usagePeriod === 'year' ? 'selected' : '' }}>
                            This Year
                        </option>
                    </select>
                </form>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-6">

                {{-- Usage Sessions --}}
                <div class="rounded-xl border border-blue-100 bg-blue-50 p-5">
                    <p class="text-sm font-medium text-blue-700">
                        Room Usage Sessions
                    </p>

                    <p class="mt-3 text-3xl font-bold text-blue-800">
                        {{ number_format($selectedUsage) }}
                    </p>

                    <p class="mt-1 text-sm text-blue-600">
                        Recorded sessions for {{ strtolower($periodLabel) }}
                    </p>
                </div>

                {{-- Usage Hours --}}
                <div class="rounded-xl border border-green-100 bg-green-50 p-5">
                    <p class="text-sm font-medium text-green-700">
                        Total Usage Hours
                    </p>

                    <p class="mt-3 text-3xl font-bold text-green-800">
                        {{ number_format($selectedHours, 1) }}
                    </p>

                    <p class="mt-1 text-sm text-green-600">
                        Recorded classroom usage duration
                    </p>
                </div>

            </div>
        </div>
    </section>


    {{-- ========================================================= --}}
    {{-- 3. ROOM USAGE RANKING --}}
    {{-- ========================================================= --}}

    <section class="mb-8" id="room-usage-ranking">
        <div class="mb-5">
            <h2 class="text-xl font-bold text-slate-800">
                Room Usage Ranking
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Compare classrooms by their recorded usage frequency and hours.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            {{-- Ranking Header and Filter --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200 px-6 py-5">

                <div>
                    <h3 class="text-lg font-bold text-slate-800">
                        {{ $roomSort === 'least' ? 'Least Used Rooms' : 'Most Used Rooms' }}
                    </h3>
                    <p class="mt-1 text-sm text-slate-500">
                        Showing up to 10 rooms with recorded usage.
                    </p>
                </div>

                <form method="GET" action="{{ route('admin.reports') }}#room-usage-ranking"
                      class="flex items-center gap-2">

                    <input type="hidden" name="usage_period" value="{{ $usagePeriod }}">

                    <select
                        name="room_sort"
                        onchange="this.form.submit()"
                        aria-label="Sort room usage ranking"
                        class="w-full sm:w-auto rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

                        <option value="most" {{ $roomSort === 'most' ? 'selected' : '' }}>
                            Most Used
                        </option>

                        <option value="least" {{ $roomSort === 'least' ? 'selected' : '' }}>
                            Least Used
                        </option>
                    </select>
                </form>
            </div>

            {{-- Ranking Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Rank
                            </th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Room
                            </th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Building
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Sessions
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Hours Used
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($roomUsage as $index => $usage)
                            <tr class="transition hover:bg-slate-50">

                                <td class="whitespace-nowrap px-6 py-4">
                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-sm font-bold text-slate-700">
                                        {{ $index + 1 }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    @if ($usage->room)
                                        <p class="font-semibold text-slate-800">
                                            {{ $usage->room->room_name ?: 'Unnamed Room' }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-500">
                                            Room {{ $usage->room->room_number }}
                                        </p>
                                    @else
                                        <span class="text-sm text-red-500">
                                            Room unavailable
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-sm text-slate-600">
                                    @if ($usage->room && $usage->room->building)
                                        {{ $usage->room->building->building_name }}
                                    @else
                                        —
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-right">
                                    <span class="inline-flex min-w-10 items-center justify-center rounded-full bg-blue-100 px-3 py-1 text-sm font-bold text-blue-700">
                                        {{ number_format($usage->total_usage) }}
                                    </span>
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-semibold text-slate-700">
                                    {{ number_format(($usage->total_minutes ?? 0) / 60, 1) }} hrs
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <p class="font-medium text-slate-700">
                                        No room usage data available yet.
                                    </p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Rooms will appear here once usage records exist.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 px-6 py-4">
                <p class="text-xs text-slate-500">
                    Ranking is based on recorded usage sessions across all available room usage records.
                </p>
            </div>
        </div>
    </section>


    {{-- ========================================================= --}}
    {{-- 4. ACTIVITY LOG / AUDIT TRAIL --}}
    {{-- ========================================================= --}}

    <section class="mb-8">
        <div class="mb-5">
            <h2 class="text-xl font-bold text-slate-800">
                Activity Log
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Review recorded administrative actions and system changes.
            </p>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-slate-200 px-6 py-5">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">
                        Audit Trail
                    </h3>
                    <p class="mt-1 text-sm text-slate-500">
                        Latest recorded activities appear first.
                    </p>
                </div>

                <span class="text-sm text-slate-500">
                    {{ $activityLogs->total() }} records
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="whitespace-nowrap px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                User
                            </th>
                            <th class="whitespace-nowrap px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Action
                            </th>
                            <th class="whitespace-nowrap px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Module
                            </th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Description
                            </th>
                            <th class="whitespace-nowrap px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Date & Time
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($activityLogs as $log)
                            <tr class="transition hover:bg-slate-50">

                                <td class="whitespace-nowrap px-6 py-4">
                                    <p class="text-sm font-semibold text-slate-800">
                                        {{ $log->user_name ?: 'Unknown User' }}
                                    </p>
                                </td>

                                <td class="whitespace-nowrap px-6 py-4">
                                    @php
                                        $actionColor = match (strtolower($log->action)) {
                                            'created', 'approved', 'imported' => 'bg-green-100 text-green-700',
                                            'updated' => 'bg-blue-100 text-blue-700',
                                            'deleted', 'rejected' => 'bg-red-100 text-red-700',
                                            default => 'bg-slate-100 text-slate-700',
                                        };
                                    @endphp

                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $actionColor }}">
                                        {{ $log->action }}
                                    </span>
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                    {{ $log->module }}
                                </td>

                                <td class="min-w-64 px-6 py-4 text-sm text-slate-600">
                                    {{ $log->description ?: 'No description provided.' }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">
                                    {{ $log->created_at?->format('M d, Y') ?? '—' }}
                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ $log->created_at?->format('h:i A') ?? '' }}
                                    </p>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <p class="font-medium text-slate-700">
                                        No activity has been recorded yet.
                                    </p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Logged actions will appear here when users perform supported operations.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($activityLogs->hasPages())
                <div class="border-t border-slate-200 px-6 py-4">
                    {{ $activityLogs->links() }}
                </div>
            @endif

        </div>
    </section>

</div>
@endsection
