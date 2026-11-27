@extends('layouts.student')

@section('title', $building->building_name)

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- PAGE HEADER -->
    <div class="mb-8">

        <a href="{{ route('student.rooms') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-[#0E4C6B] hover:text-[#0B3D56] transition mb-5">

            <x-heroicon-o-arrow-left class="w-4 h-4"/>
            Back to Room Availability

        </a>

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">

            <div>
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#0E4C6B] mb-2">
                    Room Availability
                </p>

                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-[#0E2958]">
                    {{ $building->building_name }}
                </h1>

                <p class="text-sm text-gray-500 mt-2">
                    View classroom and facility availability and room information.
                </p>
            </div>

            <!-- STATUS LEGEND -->
            <div class="flex flex-wrap items-center gap-4 text-xs font-medium">

                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                    <span class="text-gray-600">Available</span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span>
                    <span class="text-gray-600">Reserved</span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                    <span class="text-gray-600">Occupied</span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-gray-400"></span>
                    <span class="text-gray-600">Inactive</span>
                </div>

            </div>

        </div>
    </div>


    <!-- ROOM GRID -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">

        @forelse($rooms as $room)

            @php
                $status = $room->availabilityStatus ?? 'available';

                $statusStyles = [
                    'occupied' => [
                        'border' => 'border-red-200 hover:border-red-300',
                        'accent' => 'bg-red-500',
                        'icon' => 'bg-red-50 text-red-600',
                        'badge' => 'bg-red-50 text-red-700',
                        'dot' => 'bg-red-500',
                        'label' => 'Occupied',
                    ],
                    'reserved' => [
                        'border' => 'border-yellow-200 hover:border-yellow-300',
                        'accent' => 'bg-yellow-500',
                        'icon' => 'bg-yellow-50 text-yellow-600',
                        'badge' => 'bg-yellow-50 text-yellow-700',
                        'dot' => 'bg-yellow-500',
                        'label' => 'Reserved',
                    ],
                    'inactive' => [
                        'border' => 'border-gray-200 hover:border-gray-300',
                        'accent' => 'bg-gray-400',
                        'icon' => 'bg-gray-100 text-gray-500',
                        'badge' => 'bg-gray-100 text-gray-600',
                        'dot' => 'bg-gray-400',
                        'label' => 'Inactive',
                    ],
                    'available' => [
                        'border' => 'border-green-200 hover:border-green-300',
                        'accent' => 'bg-green-500',
                        'icon' => 'bg-green-50 text-green-600',
                        'badge' => 'bg-green-50 text-green-700',
                        'dot' => 'bg-green-500',
                        'label' => 'Available',
                    ],
                ];

                $style = $statusStyles[$status] ?? $statusStyles['available'];

                $isFacility = !empty($room->facility_id);
            @endphp

            <a
                href="{{ route('student.rooms.show', $room) }}"
                class="group relative flex flex-col bg-white rounded-2xl border shadow-sm
                    transition-all duration-200 hover:-translate-y-1 hover:shadow-lg
                    {{ $style['border'] }}">

                <!-- STATUS ACCENT -->
                <div class="absolute top-0 left-5 right-5 h-[3px] rounded-b-full {{ $style['accent'] }}">
                </div>

                <div class="p-5 sm:p-6">

                    <!-- ROOM HEADER -->
                    <div class="flex items-start justify-between gap-3">

                        <div class="flex items-center gap-3 min-w-0">

                            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 {{ $style['icon'] }}">

                                @if($isFacility)
                                    <x-heroicon-o-map-pin class="w-6 h-6"/>
                                @else
                                    <x-heroicon-o-building-office-2 class="w-6 h-6"/>
                                @endif

                            </div>

                            <div class="min-w-0">

                                <h2 class="text-xl font-extrabold text-[#0E2958] truncate">
                                    {{ $room->room_number }}
                                </h2>

                                <p class="text-sm text-gray-500 truncate mt-0.5">
                                    {{ $room->room_name }}
                                </p>

                                @if($isFacility)
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-[#0E4C6B] mt-1">
                                        Facility
                                    </p>
                                @endif

                            </div>

                        </div>

                        <!-- STATUS BADGE -->
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wide flex-shrink-0 {{ $style['badge'] }}">

                            <span class="w-1.5 h-1.5 rounded-full {{ $style['dot'] }}"></span>

                            {{ $style['label'] }}

                        </span>

                    </div>


                    <!-- ROOM INFORMATION -->
                    <div class="grid grid-cols-2 gap-3 mt-7">

                        <div class="bg-gray-50 border border-gray-100 rounded-xl px-4 py-3">

                            <div class="flex items-center gap-2 text-gray-400 mb-1.5">
                                <x-heroicon-o-building-office class="w-4 h-4"/>
                                <span class="text-xs">Floor</span>
                            </div>

                            <p class="text-lg font-bold text-[#0E2958]">
                                {{ $room->floor ?? '—' }}
                            </p>

                        </div>

                        <div class="bg-gray-50 border border-gray-100 rounded-xl px-4 py-3">

                            <div class="flex items-center gap-2 text-gray-400 mb-1.5">
                                <x-heroicon-o-user-group class="w-4 h-4"/>
                                <span class="text-xs">Capacity</span>
                            </div>

                            <p class="text-lg font-bold text-[#0E2958]">
                                {{ $room->capacity ?? '—' }}
                            </p>

                        </div>

                    </div>


                    <!-- CURRENT USAGE PREVIEW -->
                    @if($isFacility && $status === 'occupied')

                        <div class="mt-4 rounded-xl bg-red-50 border border-red-100 p-3">

                            <p class="text-xs font-bold text-red-700">
                                Currently in use
                            </p>

                            @if($room->currentReservation)
                                <p class="text-sm font-semibold text-red-800 mt-1 truncate">
                                    {{ $room->currentReservation->event_name }}
                                </p>
                            @elseif($room->occupiedSchedule)
                                <p class="text-sm font-semibold text-red-800 mt-1 truncate">
                                    {{ $room->occupiedSchedule->subject_name }}
                                </p>
                            @endif

                        </div>

                    @elseif($isFacility && $status === 'reserved')

                        <div class="mt-4 rounded-xl bg-yellow-50 border border-yellow-100 p-3">

                            <p class="text-xs font-semibold text-yellow-800">
                                Upcoming usage scheduled
                            </p>

                        </div>

                    @elseif($isFacility && $status === 'inactive')

                        <div class="mt-4 rounded-xl bg-gray-50 border border-gray-200 p-3">

                            <p class="text-xs font-semibold text-gray-600">
                                This facility is currently inactive.
                            </p>

                        </div>

                    @endif


                    <!-- CARD FOOTER -->
                    <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-100">

                        <span class="text-sm font-semibold text-[#0E4C6B] group-hover:text-[#0B3D56] transition">
                            View room details
                        </span>

                        <div class="w-8 h-8 rounded-full bg-[#0E4C6B]/5 flex items-center justify-center
                            group-hover:bg-[#0E4C6B] transition-all duration-200">

                            <x-heroicon-o-arrow-right class="w-4 h-4 text-[#0E4C6B] group-hover:text-white transition"/>

                        </div>

                    </div>

                </div>

            </a>

        @empty

            <!-- EMPTY STATE -->
            <div class="col-span-full bg-white rounded-2xl border border-gray-200 p-12 text-center shadow-sm">

                <div class="w-16 h-16 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center mx-auto mb-5">

                    <x-heroicon-o-building-office-2 class="w-8 h-8 text-gray-400"/>

                </div>

                <h3 class="text-lg font-bold text-[#0E2958]">
                    No rooms found
                </h3>

                <p class="text-sm text-gray-500 mt-2">
                    This building currently has no rooms assigned.
                </p>

            </div>

        @endforelse

    </div>

</div>

@endsection
