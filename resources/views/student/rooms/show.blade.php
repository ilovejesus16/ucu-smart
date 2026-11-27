@extends('layouts.student')

@section('content')
<div class="space-y-6">

    {{-- Back button --}}
    <a href="{{ route('student.rooms.building', $room->building_id) }}"
       class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-[#0E4C6B] transition">
        <i class="fas fa-arrow-left"></i>
        Back to {{ $room->building->building_name ?? 'Building' }}
    </a>

    {{-- Room Header --}}
    <div class="rounded-2xl bg-[#0E2958] p-6 text-white shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-blue-200">
                    {{ $room->building->building_name ?? 'Building' }}
                </p>

                <h1 class="mt-1 text-2xl font-bold">
                    {{ $room->room_name ?? $room->room_number }}
                </h1>

                <p class="mt-1 text-sm text-blue-200">
                    Room {{ $room->room_number }}
                </p>

                @if($room->facility)
                    <span class="mt-3 inline-flex rounded-full bg-blue-400/20 px-3 py-1 text-xs font-semibold text-blue-100">
                        Facility
                    </span>
                @endif
            </div>

            <div>
                @php
                    $status = $room->facility
                        ? ($availabilityStatus ?? 'available')
                        : ($schedule ? 'occupied' : 'available');

                    $statusStyles = [
                        'available' => 'bg-emerald-100 text-emerald-700',
                        'reserved' => 'bg-amber-100 text-amber-700',
                        'occupied' => 'bg-red-100 text-red-700',
                        'inactive' => 'bg-slate-200 text-slate-600',
                    ];

                    $statusLabels = [
                        'available' => 'Available',
                        'reserved' => 'Reserved',
                        'occupied' => 'Occupied',
                        'inactive' => 'Inactive',
                    ];
                @endphp

                <span class="inline-flex items-center rounded-full px-4 py-2 text-sm font-bold {{ $statusStyles[$status] ?? $statusStyles['available'] }}">
                    {{ $statusLabels[$status] ?? 'Available' }}
                </span>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Room Information --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="mb-5 text-lg font-bold text-slate-800">
                Room Information
            </h2>

            <div class="space-y-4">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Building</p>
                    <p class="mt-1 font-semibold text-slate-700">
                        {{ $room->building->building_name ?? 'N/A' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Room Number</p>
                    <p class="mt-1 font-semibold text-slate-700">
                        {{ $room->room_number ?? 'N/A' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Floor</p>
                    <p class="mt-1 font-semibold text-slate-700">
                        {{ $room->floor ?? 'N/A' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Capacity</p>
                    <p class="mt-1 font-semibold text-slate-700">
                        {{ $room->capacity ?? $room->facility->capacity ?? 'Not specified' }}
                    </p>
                </div>

                @if($room->facility)
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Facility</p>
                        <p class="mt-1 font-semibold text-slate-700">
                            {{ $room->facility->name }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Status and Usage --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">

            @if($room->facility)

                <h2 class="mb-5 text-lg font-bold text-slate-800">
                    Facility Usage
                </h2>

                @if($status === 'inactive')
                    <div class="rounded-xl bg-slate-50 p-5 text-center">
                        <p class="font-semibold text-slate-700">Facility Inactive</p>
                        <p class="mt-1 text-sm text-slate-500">
                            This facility is currently unavailable for use.
                        </p>
                    </div>
                @else

                    {{-- Current Event Reservation --}}
                    @if($currentReservation)
                        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-5">
                            <div class="mb-3 flex items-center justify-between gap-3">
                                <h3 class="font-bold text-red-800">Current Event Reservation</h3>
                                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700">
                                    Ongoing
                                </span>
                            </div>

                            <p class="text-lg font-bold text-slate-800">
                                {{ $currentReservation->event_name }}
                            </p>

                            <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                <div>
                                    <p class="text-xs text-slate-500">Requesting Office</p>
                                    <p class="font-semibold text-slate-700">
                                        {{ $currentReservation->requesting_office }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs text-slate-500">Date</p>
                                    <p class="font-semibold text-slate-700">
                                        {{ $currentReservation->reservation_date->format('F d, Y') }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs text-slate-500">Time</p>
                                    <p class="font-semibold text-slate-700">
                                        {{ \Carbon\Carbon::parse($currentReservation->start_time)->format('h:i A') }}
                                        –
                                        {{ \Carbon\Carbon::parse($currentReservation->end_time)->format('h:i A') }}
                                    </p>
                                </div>
                            </div>

                            @if($currentReservation->description)
                                <div class="mt-3">
                                    <p class="text-xs text-slate-500">Description</p>
                                    <p class="mt-1 text-sm text-slate-700">
                                        {{ $currentReservation->description }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Current Facility Classes --}}
                    @if($currentFacilityClasses->isNotEmpty())
                        <div class="mb-5">
                            <h3 class="mb-3 font-bold text-slate-800">
                                Ongoing Classes
                            </h3>

                            <div class="space-y-3">
                                @foreach($currentFacilityClasses as $class)
                                    <div class="rounded-xl border border-red-200 bg-red-50 p-4">
                                        <div class="flex flex-wrap items-start justify-between gap-2">
                                            <div>
                                                <p class="font-bold text-slate-800">
                                                    {{ $class->subject ?? $class->subject_code }}
                                                </p>
                                                <p class="text-sm text-slate-500">
                                                    {{ $class->subject_code }}
                                                </p>
                                            </div>

                                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700">
                                                Ongoing Class
                                            </span>
                                        </div>

                                        <div class="mt-3 grid gap-2 sm:grid-cols-2">
                                            <div>
                                                <p class="text-xs text-slate-500">Instructor</p>
                                                <p class="text-sm font-semibold text-slate-700">
                                                    {{ $class->instructor->first_name ?? '' }}
                                                    {{ $class->instructor->last_name ?? '' }}
                                                </p>
                                            </div>

                                            <div>
                                                <p class="text-xs text-slate-500">Time</p>
                                                <p class="text-sm font-semibold text-slate-700">
                                                    {{ \Carbon\Carbon::parse($class->start_time)->format('h:i A') }}
                                                    –
                                                    {{ \Carbon\Carbon::parse($class->end_time)->format('h:i A') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Upcoming Classes Today --}}
                    @php
                        $upcomingClasses = $facilityClasses->reject(function ($class) use ($currentFacilityClasses) {
                            return $currentFacilityClasses->contains('id', $class->id);
                        });
                    @endphp

                    @if($upcomingClasses->isNotEmpty())
                        <div class="mb-5">
                            <h3 class="mb-3 font-bold text-slate-800">
                                Today's Class Schedule
                            </h3>

                            <div class="space-y-3">
                                @foreach($upcomingClasses as $class)
                                    <div class="rounded-xl border border-slate-200 p-4">
                                        <p class="font-bold text-slate-800">
                                            {{ $class->subject ?? $class->subject_code }}
                                        </p>
                                        <p class="text-sm text-slate-500">
                                            {{ $class->subject_code }}
                                        </p>

                                        <div class="mt-2 flex flex-wrap justify-between gap-2 text-sm">
                                            <span class="text-slate-600">
                                                {{ $class->instructor->first_name ?? '' }}
                                                {{ $class->instructor->last_name ?? '' }}
                                            </span>

                                            <span class="font-semibold text-slate-700">
                                                {{ \Carbon\Carbon::parse($class->start_time)->format('h:i A') }}
                                                –
                                                {{ \Carbon\Carbon::parse($class->end_time)->format('h:i A') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Upcoming Event Reservations --}}
                    <div>
                        <h3 class="mb-3 font-bold text-slate-800">
                            Upcoming Event Reservations
                        </h3>

                        @php
                            $upcomingEvents = $facilityReservations->filter(function ($reservation) use ($currentReservation, $now) {
                                if ($currentReservation && $reservation->id === $currentReservation->id) {
                                    return false;
                                }

                                return $reservation->reservation_date->toDateString() > $now->toDateString()
                                    || (
                                        $reservation->reservation_date->toDateString() === $now->toDateString()
                                        && $reservation->start_time > $now->format('H:i:s')
                                    );
                            });
                        @endphp

                        @if($upcomingEvents->isNotEmpty())
                            <div class="space-y-3">
                                @foreach($upcomingEvents as $reservation)
                                    <div class="rounded-xl border border-amber-200 bg-amber-50 p-4">
                                        <p class="font-bold text-slate-800">
                                            {{ $reservation->event_name }}
                                        </p>

                                        <div class="mt-2 grid gap-2 sm:grid-cols-2">
                                            <div>
                                                <p class="text-xs text-slate-500">Requesting Office</p>
                                                <p class="text-sm font-semibold text-slate-700">
                                                    {{ $reservation->requesting_office }}
                                                </p>
                                            </div>

                                            <div>
                                                <p class="text-xs text-slate-500">Date</p>
                                                <p class="text-sm font-semibold text-slate-700">
                                                    {{ $reservation->reservation_date->format('F d, Y') }}
                                                </p>
                                            </div>

                                            <div>
                                                <p class="text-xs text-slate-500">Time</p>
                                                <p class="text-sm font-semibold text-slate-700">
                                                    {{ \Carbon\Carbon::parse($reservation->start_time)->format('h:i A') }}
                                                    –
                                                    {{ \Carbon\Carbon::parse($reservation->end_time)->format('h:i A') }}
                                                </p>
                                            </div>
                                        </div>

                                        @if($reservation->description)
                                            <p class="mt-2 text-sm text-slate-600">
                                                {{ $reservation->description }}
                                            </p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="rounded-xl bg-slate-50 p-4 text-sm text-slate-500">
                                No upcoming event reservations.
                            </p>
                        @endif
                    </div>

                    @if(!$currentReservation && $currentFacilityClasses->isEmpty() && $facilityClasses->isEmpty() && $upcomingEvents->isEmpty())
                        <div class="rounded-xl bg-emerald-50 p-5 text-center">
                            <p class="font-semibold text-emerald-700">
                                Facility Available
                            </p>
                            <p class="mt-1 text-sm text-emerald-600">
                                There are currently no scheduled classes or event reservations.
                            </p>
                        </div>
                    @endif

                @endif

            @else

                {{-- Original Classroom Details --}}
                <h2 class="mb-5 text-lg font-bold text-slate-800">
                    Current Classroom Status
                </h2>

                @if($schedule)
                    <div class="rounded-xl border border-red-200 bg-red-50 p-5">
                        <div class="mb-3 flex items-center justify-between gap-3">
                            <h3 class="font-bold text-red-800">Currently Occupied</h3>
                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700">
                                Ongoing Class
                            </span>
                        </div>

                        <p class="text-lg font-bold text-slate-800">
                            {{ $schedule->subject ?? $schedule->subject_code }}
                        </p>

                        <p class="mt-1 text-sm text-slate-500">
                            {{ $schedule->subject_code }}
                        </p>

                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            <div>
                                <p class="text-xs text-slate-500">Instructor</p>
                                <p class="font-semibold text-slate-700">
                                    {{ $schedule->instructor->first_name ?? '' }}
                                    {{ $schedule->instructor->last_name ?? '' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-slate-500">Time</p>
                                <p class="font-semibold text-slate-700">
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}
                                    –
                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="rounded-xl bg-emerald-50 p-5 text-center">
                        <p class="font-semibold text-emerald-700">
                            Classroom Available
                        </p>
                        <p class="mt-1 text-sm text-emerald-600">
                            There is currently no ongoing class in this room.
                        </p>
                    </div>
                @endif

            @endif
        </div>
    </div>
</div>
@endsection