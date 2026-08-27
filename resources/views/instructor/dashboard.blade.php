@extends('layouts.instructor')

@section('title', 'Instructor Dashboard')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- ========================================================= -->
    <!-- WELCOME -->
    <!-- ========================================================= -->

    <div class="mb-8">

        <h1 class="text-3xl sm:text-4xl font-extrabold text-[#0E2958] leading-tight">

            Welcome back,

            <span class="text-[#0E4C6B] break-words">
                {{ Auth::user()->first_name }}
                {{ Auth::user()->last_name }}
            </span>

        </h1>

        <p class="text-gray-500 mt-2">
            Here's an overview of today's classes and classroom availability.
        </p>

    </div>


    <!-- ========================================================= -->
    <!-- STATISTICS -->
    <!-- ========================================================= -->

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">


        <!-- Today's Classes -->

        <div
            class="bg-white rounded-2xl border border-gray-200
                   shadow-sm hover:shadow-md
                   transition p-6">

            <div class="flex items-start justify-between gap-4">

                <div>

                    <p class="text-sm font-medium text-gray-500">
                        Today's Classes
                    </p>

                    <h2 class="text-3xl font-extrabold text-[#0E2958] mt-3">
                        {{ $todayClasses }}
                    </h2>

                </div>


                <div
                    class="w-12 h-12 rounded-xl
                           bg-[#0E4C6B]/10
                           flex items-center justify-center">

                    <x-heroicon-o-calendar-days
                        class="w-6 h-6 text-[#0E4C6B]"/>

                </div>

            </div>

        </div>


        <!-- Available Rooms -->

        <div
            class="bg-white rounded-2xl border border-gray-200
                   shadow-sm hover:shadow-md
                   transition p-6">

            <div class="flex items-start justify-between gap-4">

                <div>

                    <p class="text-sm font-medium text-gray-500">
                        Available Rooms
                    </p>

                    <h2 class="text-3xl font-extrabold text-[#0E2958] mt-3">
                        {{ $availableRooms }}
                    </h2>

                </div>


                <div
                    class="w-12 h-12 rounded-xl
                           bg-[#0E4C6B]/10
                           flex items-center justify-center">

                    <x-heroicon-o-home-modern
                        class="w-6 h-6 text-[#0E4C6B]"/>

                </div>

            </div>

        </div>


        <!-- Next Class -->

        <div
            class="bg-white rounded-2xl border border-gray-200
                   shadow-sm hover:shadow-md
                   transition p-6">

            <div class="flex items-start justify-between gap-4">

                <div class="min-w-0">

                    <p class="text-sm font-medium text-gray-500">
                        Next Class
                    </p>

                    <h2 class="text-2xl font-extrabold text-[#0E2958] mt-3">

                        {{ $nextClass
                            ? date('g:i A', strtotime($nextClass->start_time))
                            : '--'
                        }}

                    </h2>

                    <p class="text-sm text-gray-500 mt-1 truncate">

                        {{ $nextClass
                            ? $nextClass->room->room_name
                            : 'No upcoming class'
                        }}

                    </p>

                </div>


                <div
                    class="w-12 h-12 rounded-xl
                           bg-[#0E4C6B]/10
                           flex items-center justify-center
                           flex-shrink-0">

                    <x-heroicon-o-clock
                        class="w-6 h-6 text-[#0E4C6B]"/>

                </div>

            </div>

        </div>


        <!-- Current Semester -->

        <div
            class="bg-white rounded-2xl border border-gray-200
                   shadow-sm hover:shadow-md
                   transition p-6">

            <div class="flex items-start justify-between gap-4">

                <div class="min-w-0">

                    <p class="text-sm font-medium text-gray-500">
                        Current Semester
                    </p>

                    <h2
                        class="text-xl font-extrabold
                               text-[#0E2958]
                               mt-3 break-words">

                        {{ $semester ?? 'N/A' }}

                    </h2>

                </div>


                <div
                    class="w-12 h-12 rounded-xl
                           bg-[#0E4C6B]/10
                           flex items-center justify-center
                           flex-shrink-0">

                    <x-heroicon-o-academic-cap
                        class="w-6 h-6 text-[#0E4C6B]"/>

                </div>

            </div>

        </div>

    </div>


    <!-- ========================================================= -->
    <!-- MAIN CONTENT -->
    <!-- ========================================================= -->

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">


        <!-- ===================================================== -->
        <!-- TODAY'S SCHEDULE -->
        <!-- ===================================================== -->

        <div class="xl:col-span-2">

            <div
                class="bg-white rounded-2xl
                       border border-gray-200
                       shadow-sm overflow-hidden">


                <!-- Header -->

                <div
                    class="px-5 sm:px-6 py-5
                           border-b border-gray-200
                           flex items-center justify-between gap-4">

                    <div>

                        <h2 class="text-xl font-bold text-[#0E2958]">
                            Today's Schedule
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Your classes scheduled for today.
                        </p>

                    </div>


                    <a
                        href="{{ route('instructor.schedule') }}"
                        class="hidden sm:inline-flex
                               items-center gap-2
                               text-sm font-semibold
                               text-[#0E4C6B]
                               hover:text-[#0B3D56]
                               transition">

                        View All

                        <x-heroicon-o-arrow-right
                            class="w-4 h-4"/>

                    </a>

                </div>


                <!-- Schedule -->

                <div class="p-5 sm:p-6">

                    @forelse($todaySchedules as $schedule)

                        <div
                            class="group
                                   border border-gray-200
                                   rounded-xl
                                   p-5
                                   mb-4 last:mb-0
                                   hover:border-[#0E4C6B]/30
                                   hover:bg-[#0E4C6B]/[0.02]
                                   transition">


                            <div
                                class="flex flex-col
                                       sm:flex-row
                                       sm:items-start
                                       sm:justify-between
                                       gap-4">


                                <!-- Subject -->

                                <div class="min-w-0">

                                    <div class="flex items-center gap-3">

                                        <div
                                            class="w-10 h-10
                                                   rounded-lg
                                                   bg-[#0E4C6B]/10
                                                   flex items-center
                                                   justify-center
                                                   flex-shrink-0">

                                            <x-heroicon-o-book-open
                                                class="w-5 h-5 text-[#0E4C6B]"/>

                                        </div>


                                        <div class="min-w-0">

                                            <h3
                                                class="font-bold
                                                       text-gray-800
                                                       text-lg
                                                       break-words">

                                                {{ $schedule->subject_name }}

                                            </h3>

                                            <p class="text-sm text-gray-500 mt-0.5">

                                                {{ $schedule->subject_code }}

                                            </p>

                                        </div>

                                    </div>

                                </div>


                                <!-- Time -->

                                <div
                                    class="inline-flex
                                           items-center gap-2
                                           bg-[#0E4C6B]/10
                                           text-[#0E4C6B]
                                           px-3 py-2
                                           rounded-lg
                                           text-sm
                                           font-semibold
                                           whitespace-nowrap
                                           self-start">

                                    <x-heroicon-o-clock
                                        class="w-4 h-4"/>

                                    {{ date('g:i A', strtotime($schedule->start_time)) }}

                                    <span class="text-gray-400">
                                        –
                                    </span>

                                    {{ date('g:i A', strtotime($schedule->end_time)) }}

                                </div>

                            </div>


                            <!-- Bottom Info -->

                            <div
                                class="mt-5 pt-4
                                       border-t border-gray-100
                                       flex flex-col
                                       sm:flex-row
                                       sm:items-center
                                       sm:justify-between
                                       gap-3">


                                <!-- Room -->

                                <div
                                    class="flex items-center gap-2
                                           text-sm text-gray-600">

                                    <x-heroicon-o-map-pin
                                        class="w-5 h-5 text-[#0E4C6B]"/>

                                    <span>

                                        {{ $schedule->room->room_name }}

                                        <span class="text-gray-400">
                                            •
                                        </span>

                                        Room
                                        {{ $schedule->room->room_number }}

                                    </span>

                                </div>


                                <!-- Status -->

                                <div>

                                    @if($schedule->status == 'pending')

                                        <span
                                            class="inline-flex
                                                   items-center gap-2
                                                   bg-yellow-50
                                                   text-yellow-700
                                                   border border-yellow-200
                                                   px-3 py-1.5
                                                   rounded-full
                                                   text-xs
                                                   font-semibold">

                                            <span
                                                class="w-2 h-2
                                                       rounded-full
                                                       bg-yellow-500">
                                            </span>

                                            Pending

                                        </span>


                                    @elseif($schedule->status == 'scheduled')

                                        <span
                                            class="inline-flex
                                                   items-center gap-2
                                                   bg-blue-50
                                                   text-blue-700
                                                   border border-blue-200
                                                   px-3 py-1.5
                                                   rounded-full
                                                   text-xs
                                                   font-semibold">

                                            <span
                                                class="w-2 h-2
                                                       rounded-full
                                                       bg-blue-500">
                                            </span>

                                            Scheduled

                                        </span>


                                    @elseif($schedule->status == 'in_progress')

                                        <span
                                            class="inline-flex
                                                   items-center gap-2
                                                   bg-red-50
                                                   text-red-700
                                                   border border-red-200
                                                   px-3 py-1.5
                                                   rounded-full
                                                   text-xs
                                                   font-semibold">

                                            <span
                                                class="w-2 h-2
                                                       rounded-full
                                                       bg-red-500">
                                            </span>

                                            In Progress

                                        </span>


                                    @elseif($schedule->status == 'completed')

                                        <span
                                            class="inline-flex
                                                   items-center gap-2
                                                   bg-green-50
                                                   text-green-700
                                                   border border-green-200
                                                   px-3 py-1.5
                                                   rounded-full
                                                   text-xs
                                                   font-semibold">

                                            <span
                                                class="w-2 h-2
                                                       rounded-full
                                                       bg-green-500">
                                            </span>

                                            Completed

                                        </span>


                                    @elseif($schedule->status == 'cancelled')

                                        <span
                                            class="inline-flex
                                                   items-center gap-2
                                                   bg-gray-100
                                                   text-gray-600
                                                   border border-gray-200
                                                   px-3 py-1.5
                                                   rounded-full
                                                   text-xs
                                                   font-semibold">

                                            <span
                                                class="w-2 h-2
                                                       rounded-full
                                                       bg-gray-400">
                                            </span>

                                            Cancelled

                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>


                    @empty

                        <div
                            class="py-14
                                   text-center
                                   flex flex-col
                                   items-center">

                            <div
                                class="w-14 h-14
                                       rounded-2xl
                                       bg-gray-100
                                       flex items-center
                                       justify-center
                                       mb-4">

                                <x-heroicon-o-calendar-days
                                    class="w-7 h-7 text-gray-400"/>

                            </div>

                            <h3
                                class="font-semibold
                                       text-gray-700">

                                No classes scheduled today

                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                You don't have any classes assigned for today.
                            </p>

                        </div>

                    @endforelse


                    <!-- Mobile View All -->

                    <div class="mt-5 sm:hidden">

                        <a
                            href="{{ route('instructor.schedule') }}"
                            class="w-full
                                   inline-flex
                                   items-center
                                   justify-center
                                   gap-2
                                   rounded-xl
                                   border border-gray-300
                                   px-4 py-3
                                   text-sm font-semibold
                                   text-gray-700
                                   hover:bg-gray-50
                                   transition">

                            View My Schedule

                            <x-heroicon-o-arrow-right
                                class="w-4 h-4"/>

                        </a>

                    </div>

                </div>

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- RIGHT COLUMN -->
        <!-- ===================================================== -->

        <div class="space-y-6">


            <!-- ================================================= -->
            <!-- QUICK ACTIONS -->
            <!-- ================================================= -->

            <div
                class="bg-white
                       rounded-2xl
                       border border-gray-200
                       shadow-sm
                       overflow-hidden">


                <div
                    class="px-5 sm:px-6 py-5
                           border-b border-gray-200">

                    <h2 class="font-bold text-xl text-[#0E2958]">
                        Quick Actions
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Frequently used instructor tools.
                    </p>

                </div>


                <div class="p-5 space-y-3">


                    <!-- My Schedule -->

                    <a
                        href="{{ route('instructor.schedule') }}"
                        class="group
                               w-full
                               flex items-center
                               justify-between
                               gap-4
                               rounded-xl
                               border border-gray-200
                               px-4 py-3.5
                               hover:border-[#0E4C6B]
                               hover:bg-[#0E4C6B]/5
                               transition">

                        <div class="flex items-center gap-3">

                            <div
                                class="w-10 h-10
                                       rounded-lg
                                       bg-[#0E4C6B]/10
                                       flex items-center
                                       justify-center">

                                <x-heroicon-o-calendar-days
                                    class="w-5 h-5 text-[#0E4C6B]"/>

                            </div>

                            <span
                                class="font-semibold
                                       text-gray-700
                                       group-hover:text-[#0E4C6B]">

                                View My Schedule

                            </span>

                        </div>


                        <x-heroicon-o-chevron-right
                            class="w-5 h-5 text-gray-400
                                   group-hover:text-[#0E4C6B]
                                   transition"/>

                    </a>


                    <!-- Room Availability -->

                    <a
                        href="{{ route('instructor.rooms') }}"
                        class="group
                               w-full
                               flex items-center
                               justify-between
                               gap-4
                               rounded-xl
                               border border-gray-200
                               px-4 py-3.5
                               hover:border-[#0E4C6B]
                               hover:bg-[#0E4C6B]/5
                               transition">

                        <div class="flex items-center gap-3">

                            <div
                                class="w-10 h-10
                                       rounded-lg
                                       bg-[#0E4C6B]/10
                                       flex items-center
                                       justify-center">

                                <x-heroicon-o-home-modern
                                    class="w-5 h-5 text-[#0E4C6B]"/>

                            </div>

                            <span
                                class="font-semibold
                                       text-gray-700
                                       group-hover:text-[#0E4C6B]">

                                Room Availability

                            </span>

                        </div>


                        <x-heroicon-o-chevron-right
                            class="w-5 h-5 text-gray-400
                                   group-hover:text-[#0E4C6B]
                                   transition"/>

                    </a>


                    <!-- Campus Navigation -->

                    <a
                        href="#"
                        class="group
                               w-full
                               flex items-center
                               justify-between
                               gap-4
                               rounded-xl
                               border border-gray-200
                               px-4 py-3.5
                               hover:border-[#0E4C6B]
                               hover:bg-[#0E4C6B]/5
                               transition">

                        <div class="flex items-center gap-3">

                            <div
                                class="w-10 h-10
                                       rounded-lg
                                       bg-[#0E4C6B]/10
                                       flex items-center
                                       justify-center">

                                <x-heroicon-o-map
                                    class="w-5 h-5 text-[#0E4C6B]"/>

                            </div>

                            <span
                                class="font-semibold
                                       text-gray-700
                                       group-hover:text-[#0E4C6B]">

                                Campus Navigation

                            </span>

                        </div>


                        <x-heroicon-o-chevron-right
                            class="w-5 h-5 text-gray-400
                                   group-hover:text-[#0E4C6B]
                                   transition"/>

                    </a>

                </div>

            </div>


            <!-- ================================================= -->
            <!-- ANNOUNCEMENTS -->
            <!-- ================================================= -->

            <div
                class="bg-white
                       rounded-2xl
                       border border-gray-200
                       shadow-sm
                       overflow-hidden">


                <div
                    class="px-5 sm:px-6 py-5
                           border-b border-gray-200">

                    <h2 class="font-bold text-xl text-[#0E2958]">
                        Announcements
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Important information for instructors.
                    </p>

                </div>


                <div class="p-5 space-y-5">


                    <!-- Schedule Reminder -->

                    <div
                        class="flex gap-3">

                        <div
                            class="w-1
                                   rounded-full
                                   bg-[#0E4C6B]
                                   flex-shrink-0">
                        </div>

                        <div>

                            <h3
                                class="font-semibold
                                       text-gray-800">

                                Schedule Reminder

                            </h3>

                            <p
                                class="text-sm
                                       text-gray-500
                                       mt-1
                                       leading-relaxed">

                                Please confirm your assigned classes before conducting your session.

                            </p>

                        </div>

                    </div>


                    <!-- Room Availability -->

                    <div
                        class="flex gap-3">

                        <div
                            class="w-1
                                   rounded-full
                                   bg-[#0E4C6B]
                                   flex-shrink-0">
                        </div>

                        <div>

                            <h3
                                class="font-semibold
                                       text-gray-800">

                                Room Availability

                            </h3>

                            <p
                                class="text-sm
                                       text-gray-500
                                       mt-1
                                       leading-relaxed">

                                Check classroom availability before transferring to another room.

                            </p>

                        </div>

                    </div>


                    <!-- Campus Navigation -->

                    <div
                        class="flex gap-3">

                        <div
                            class="w-1
                                   rounded-full
                                   bg-[#0E4C6B]
                                   flex-shrink-0">
                        </div>

                        <div>

                            <h3
                                class="font-semibold
                                       text-gray-800">

                                Campus Navigation

                            </h3>

                            <p
                                class="text-sm
                                       text-gray-500
                                       mt-1
                                       leading-relaxed">

                                The interactive campus navigation feature will be available soon.

                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection