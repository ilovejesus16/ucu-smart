@extends('layouts.instructor')

@section('title', $room->room_name)

@section('content')

<div class="max-w-5xl mx-auto">

    <!-- ========================================================= -->
    <!-- BACK -->
    <!-- ========================================================= -->

    <a
        href="{{ route('instructor.rooms.building', $room->building) }}"
        class="inline-flex items-center gap-2
               text-sm font-semibold
               text-[#0E4C6B]
               hover:text-[#0B3D56]
               transition">

        <x-heroicon-o-arrow-left
            class="w-4 h-4"/>

        Back to {{ $room->building->building_name }}

    </a>


    <!-- ========================================================= -->
    <!-- ROOM CARD -->
    <!-- ========================================================= -->

    <div
        class="bg-white
               rounded-2xl
               border border-gray-200
               shadow-sm
               mt-5
               overflow-hidden">


        <!-- ===================================================== -->
        <!-- ROOM HEADER -->
        <!-- ===================================================== -->

        <div
            class="bg-[#0E2958]
                   text-white
                   p-6 sm:p-8">

            <div class="flex items-start gap-4">

                <div
                    class="w-14 h-14
                           rounded-2xl
                           bg-white/10
                           flex items-center
                           justify-center
                           flex-shrink-0">

                    <x-heroicon-o-home-modern
                        class="w-7 h-7"/>

                </div>


                <div class="min-w-0">

                    <p
                        class="text-sm
                               text-blue-200
                               font-medium">

                        {{ $room->building->building_name }}

                    </p>

                    <h1
                        class="text-3xl sm:text-4xl
                               font-extrabold
                               mt-1
                               break-words">

                        {{ $room->room_number }}

                    </h1>

                    <p
                        class="text-blue-100
                               mt-1
                               text-lg
                               break-words">

                        {{ $room->room_name }}

                    </p>

                </div>

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- CONTENT -->
        <!-- ===================================================== -->

        <div class="p-6 sm:p-8">

            <div
                class="grid grid-cols-1
                       lg:grid-cols-2
                       gap-8">


                <!-- ================================================= -->
                <!-- ROOM INFORMATION -->
                <!-- ================================================= -->

                <div>

                    <h2
                        class="text-xl
                               font-bold
                               text-[#0E2958]
                               mb-5">

                        Room Information

                    </h2>


                    <div class="space-y-3">


                        <!-- Building -->

                        <div
                            class="flex items-center
                                   justify-between gap-4
                                   border-b border-gray-100
                                   pb-3">

                            <div
                                class="flex items-center gap-2
                                       text-gray-500">

                                <x-heroicon-o-building-office-2
                                    class="w-5 h-5"/>

                                Building

                            </div>

                            <span
                                class="font-semibold
                                       text-gray-800
                                       text-right">

                                {{ $room->building->building_name }}

                            </span>

                        </div>


                        <!-- Floor -->

                        <div
                            class="flex items-center
                                   justify-between gap-4
                                   border-b border-gray-100
                                   pb-3">

                            <div
                                class="flex items-center gap-2
                                       text-gray-500">

                                <x-heroicon-o-bars-3
                                    class="w-5 h-5"/>

                                Floor

                            </div>

                            <span
                                class="font-semibold
                                       text-gray-800">

                                {{ $room->floor }}

                            </span>

                        </div>


                        <!-- Capacity -->

                        <div
                            class="flex items-center
                                   justify-between gap-4
                                   border-b border-gray-100
                                   pb-3">

                            <div
                                class="flex items-center gap-2
                                       text-gray-500">

                                <x-heroicon-o-user-group
                                    class="w-5 h-5"/>

                                Capacity

                            </div>

                            <span
                                class="font-semibold
                                       text-gray-800">

                                {{ $room->capacity }}

                            </span>

                        </div>

                    </div>

                </div>


                <!-- ================================================= -->
                <!-- CURRENT STATUS -->
                <!-- ================================================= -->

                <div>

                    <h2
                        class="text-xl
                               font-bold
                               text-[#0E2958]
                               mb-5">

                        Current Status

                    </h2>


                    @if($schedule)

                        <!-- Occupied -->

                        <div
                            class="bg-red-50
                                   border border-red-200
                                   rounded-2xl
                                   p-5">

                            <div
                                class="flex items-center
                                       gap-3">

                                <div
                                    class="w-11 h-11
                                           rounded-xl
                                           bg-red-100
                                           flex items-center
                                           justify-center">

                                    <x-heroicon-o-x-circle
                                        class="w-6 h-6 text-red-600"/>

                                </div>


                                <div>

                                    <p
                                        class="font-bold
                                               text-red-700">

                                        Occupied

                                    </p>

                                    <p
                                        class="text-sm
                                               text-red-600
                                               mt-0.5">

                                        A class is currently in session.

                                    </p>

                                </div>

                            </div>


                            <!-- Class Information -->

                            <div
                                class="mt-5
                                       pt-5
                                       border-t
                                       border-red-200
                                       space-y-3">


                                <div>

                                    <p class="text-xs text-red-500">
                                        Subject
                                    </p>

                                    <p
                                        class="font-semibold
                                               text-red-800
                                               mt-0.5">

                                        {{ $schedule->subject_name }}

                                    </p>

                                    <p
                                        class="text-sm
                                               text-red-600">

                                        {{ $schedule->subject_code }}

                                    </p>

                                </div>


                                <div>

                                    <p class="text-xs text-red-500">
                                        Instructor
                                    </p>

                                    <p
                                        class="font-semibold
                                               text-red-800
                                               mt-0.5">

                                        {{ $schedule->instructor->first_name }}
                                        {{ $schedule->instructor->last_name }}

                                    </p>

                                </div>


                                <div>

                                    <p class="text-xs text-red-500">
                                        Time
                                    </p>

                                    <p
                                        class="font-semibold
                                               text-red-800
                                               mt-0.5">

                                        {{ date('g:i A', strtotime($schedule->start_time)) }}

                                        <span class="font-normal text-red-400">
                                            –
                                        </span>

                                        {{ date('g:i A', strtotime($schedule->end_time)) }}

                                    </p>

                                </div>

                            </div>

                        </div>


                    @else

                        <!-- Available -->

                        <div
                            class="bg-green-50
                                   border border-green-200
                                   rounded-2xl
                                   p-5">

                            <div
                                class="flex items-center
                                       gap-3">

                                <div
                                    class="w-11 h-11
                                           rounded-xl
                                           bg-green-100
                                           flex items-center
                                           justify-center">

                                    <x-heroicon-o-check-circle
                                        class="w-6 h-6 text-green-600"/>

                                </div>


                                <div>

                                    <p
                                        class="font-bold
                                               text-green-700">

                                        Available

                                    </p>

                                    <p
                                        class="text-sm
                                               text-green-600
                                               mt-0.5">

                                        No class is currently using this room.

                                    </p>

                                </div>

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection