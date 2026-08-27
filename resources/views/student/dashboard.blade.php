@extends('layouts.student')

@section('title', 'Student Dashboard')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- ========================================================= -->
    <!-- WELCOME -->
    <!-- ========================================================= -->

    <div class="mb-8">

        <h1 class="text-3xl sm:text-4xl font-extrabold text-[#0E2958]">

            Welcome back,

            <span class="text-[#0E4C6B]">
                {{ Auth::user()->first_name }}
                {{ Auth::user()->last_name }}
            </span>

        </h1>

        <p class="text-gray-500 mt-2">
            Find available classrooms and navigate around the UCU campus.
        </p>

    </div>


    <!-- ========================================================= -->
    <!-- MAIN FEATURES -->
    <!-- ========================================================= -->

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">


        <!-- ===================================================== -->
        <!-- ROOM AVAILABILITY -->
        <!-- ===================================================== -->

        <a
            href="{{ route('student.rooms') }}"
            class="group bg-white
                   rounded-2xl
                   border border-gray-200
                   shadow-sm
                   hover:shadow-md
                   hover:border-[#0E4C6B]/30
                   transition
                   p-6 sm:p-8">

            <div class="flex items-start gap-5">

                <div
                    class="w-14 h-14
                           rounded-2xl
                           bg-[#0E4C6B]/10
                           flex items-center
                           justify-center
                           flex-shrink-0">

                    <x-heroicon-o-home-modern
                        class="w-7 h-7 text-[#0E4C6B]"/>

                </div>


                <div class="min-w-0 flex-1">

                    <div
                        class="flex items-start
                               justify-between
                               gap-3">

                        <h2
                            class="text-xl
                                   sm:text-2xl
                                   font-bold
                                   text-[#0E2958]">

                            Room Availability

                        </h2>

                        <x-heroicon-o-arrow-right
                            class="w-5 h-5
                                   text-[#0E4C6B]
                                   group-hover:translate-x-1
                                   transition
                                   flex-shrink-0"/>

                    </div>


                    <p
                        class="text-gray-500
                               mt-2
                               leading-relaxed">

                        Check which classrooms are currently
                        available or occupied across the campus.

                    </p>


                    <div
                        class="mt-5
                               inline-flex
                               items-center gap-2
                               text-sm
                               font-semibold
                               text-[#0E4C6B]">

                        View Available Rooms

                        <x-heroicon-o-arrow-right
                            class="w-4 h-4
                                   group-hover:translate-x-1
                                   transition"/>

                    </div>

                </div>

            </div>

        </a>


        <!-- ===================================================== -->
        <!-- CAMPUS NAVIGATION -->
        <!-- ===================================================== -->

        <a
            href="#"
            class="group bg-white
                   rounded-2xl
                   border border-gray-200
                   shadow-sm
                   hover:shadow-md
                   hover:border-[#0E4C6B]/30
                   transition
                   p-6 sm:p-8">

            <div class="flex items-start gap-5">

                <div
                    class="w-14 h-14
                           rounded-2xl
                           bg-[#0E4C6B]/10
                           flex items-center
                           justify-center
                           flex-shrink-0">

                    <x-heroicon-o-map
                        class="w-7 h-7 text-[#0E4C6B]"/>

                </div>


                <div class="min-w-0 flex-1">

                    <div
                        class="flex items-start
                               justify-between
                               gap-3">

                        <h2
                            class="text-xl
                                   sm:text-2xl
                                   font-bold
                                   text-[#0E2958]">

                            Campus Navigation

                        </h2>

                        <x-heroicon-o-arrow-right
                            class="w-5 h-5
                                   text-[#0E4C6B]
                                   group-hover:translate-x-1
                                   transition
                                   flex-shrink-0"/>

                    </div>


                    <p
                        class="text-gray-500
                               mt-2
                               leading-relaxed">

                        Find buildings and classrooms around
                        the UCU campus using the navigation system.

                    </p>


                    <div
                        class="mt-5
                               inline-flex
                               items-center gap-2
                               text-sm
                               font-semibold
                               text-gray-400">

                        Coming Soon

                    </div>

                </div>

            </div>

        </a>

    </div>


    <!-- ========================================================= -->
    <!-- HOW IT WORKS -->
    <!-- ========================================================= -->

    <div
        class="bg-white
               rounded-2xl
               border border-gray-200
               shadow-sm
               p-6 sm:p-8">

        <div class="mb-6">

            <h2
                class="text-xl sm:text-2xl
                       font-bold
                       text-[#0E2958]">

                How UCU Smart+ Helps You

            </h2>

            <p class="text-gray-500 mt-1">
                Quickly find the information you need around campus.
            </p>

        </div>


        <div
            class="grid grid-cols-1
                   md:grid-cols-3
                   gap-5">


            <!-- Step 1 -->

            <div
                class="rounded-xl
                       bg-gray-50
                       border border-gray-100
                       p-5">

                <div
                    class="w-10 h-10
                           rounded-lg
                           bg-[#0E4C6B]/10
                           text-[#0E4C6B]
                           flex items-center
                           justify-center
                           font-bold">

                    1

                </div>

                <h3
                    class="font-bold
                           text-gray-800
                           mt-4">

                    Find a Building

                </h3>

                <p
                    class="text-sm
                           text-gray-500
                           mt-1
                           leading-relaxed">

                    Browse the available buildings on campus.

                </p>

            </div>


            <!-- Step 2 -->

            <div
                class="rounded-xl
                       bg-gray-50
                       border border-gray-100
                       p-5">

                <div
                    class="w-10 h-10
                           rounded-lg
                           bg-[#0E4C6B]/10
                           text-[#0E4C6B]
                           flex items-center
                           justify-center
                           font-bold">

                    2

                </div>

                <h3
                    class="font-bold
                           text-gray-800
                           mt-4">

                    Check a Room

                </h3>

                <p
                    class="text-sm
                           text-gray-500
                           mt-1
                           leading-relaxed">

                    View whether a classroom is currently
                    available or occupied.

                </p>

            </div>


            <!-- Step 3 -->

            <div
                class="rounded-xl
                       bg-gray-50
                       border border-gray-100
                       p-5">

                <div
                    class="w-10 h-10
                           rounded-lg
                           bg-[#0E4C6B]/10
                           text-[#0E4C6B]
                           flex items-center
                           justify-center
                           font-bold">

                    3

                </div>

                <h3
                    class="font-bold
                           text-gray-800
                           mt-4">

                    Navigate Campus

                </h3>

                <p
                    class="text-sm
                           text-gray-500
                           mt-1
                           leading-relaxed">

                    Use campus navigation to locate
                    buildings and classrooms.

                </p>

            </div>

        </div>

    </div>

</div>

@endsection