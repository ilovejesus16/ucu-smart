@extends('layouts.student')

@section('title', $building->building_name)

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- ========================================================= -->
    <!-- PAGE HEADER -->
    <!-- ========================================================= -->

    <div class="mb-8">

        <a
            href="{{ route('student.rooms') }}"
            class="inline-flex items-center gap-2
                   text-sm font-semibold
                   text-[#0E4C6B]
                   hover:text-[#0B3D56]
                   transition mb-4">

            <x-heroicon-o-arrow-left
                class="w-4 h-4"/>

            Back to Room Availability

        </a>


        <h1
            class="text-3xl sm:text-4xl
                   font-extrabold
                   text-[#0E2958]
                   break-words">

            {{ $building->building_name }}

        </h1>


        <p class="text-gray-500 mt-2">
            Select a classroom to view its current availability.
        </p>

    </div>


    <!-- ========================================================= -->
    <!-- ROOMS -->
    <!-- ========================================================= -->

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

        @forelse($rooms as $room)

            <a
                href="{{ route('student.rooms.show', $room) }}"
                class="group bg-white
                       rounded-2xl
                       border border-gray-200
                       shadow-sm
                       hover:shadow-md
                       hover:border-[#0E4C6B]/30
                       transition
                       p-5 sm:p-6">


                <!-- Room Header -->

                <div
                    class="flex items-start
                           gap-4">

                    <div
                        class="w-11 h-11
                               rounded-xl
                               bg-[#0E4C6B]/10
                               flex items-center
                               justify-center
                               flex-shrink-0">

                        <x-heroicon-o-home-modern
                            class="w-6 h-6
                                   text-[#0E4C6B]"/>

                    </div>


                    <div class="min-w-0">

                        <h2
                            class="text-xl
                                   font-bold
                                   text-[#0E2958]
                                   break-words">

                            {{ $room->room_number }}

                        </h2>

                        <p
                            class="text-sm
                                   text-gray-500
                                   mt-0.5
                                   break-words">

                            {{ $room->room_name }}

                        </p>

                    </div>

                </div>


                <!-- Room Information -->

                <div
                    class="mt-6
                           grid grid-cols-2
                           gap-3">


                    <!-- Floor -->

                    <div
                        class="bg-gray-50
                               border border-gray-100
                               rounded-xl
                               p-3">

                        <p class="text-xs text-gray-500">
                            Floor
                        </p>

                        <p
                            class="font-semibold
                                   text-gray-800
                                   mt-1">

                            {{ $room->floor }}

                        </p>

                    </div>


                    <!-- Capacity -->

                    <div
                        class="bg-gray-50
                               border border-gray-100
                               rounded-xl
                               p-3">

                        <p class="text-xs text-gray-500">
                            Capacity
                        </p>

                        <p
                            class="font-semibold
                                   text-gray-800
                                   mt-1">

                            {{ $room->capacity }}

                        </p>

                    </div>

                </div>


                <!-- View -->

                <div
                    class="mt-5
                           pt-4
                           border-t border-gray-100
                           flex items-center
                           justify-between">

                    <span
                        class="text-sm
                               font-semibold
                               text-gray-500">

                        Check availability

                    </span>


                    <span
                        class="text-[#0E4C6B]
                               group-hover:translate-x-1
                               transition">

                        <x-heroicon-o-arrow-right
                            class="w-5 h-5"/>

                    </span>

                </div>

            </a>

        @empty

            <!-- Empty State -->

            <div
                class="col-span-full
                       bg-white
                       rounded-2xl
                       border border-gray-200
                       shadow-sm
                       p-12
                       text-center">

                <div
                    class="w-16 h-16
                           rounded-2xl
                           bg-gray-100
                           flex items-center
                           justify-center
                           mx-auto mb-4">

                    <x-heroicon-o-home-modern
                        class="w-8 h-8
                               text-gray-400"/>

                </div>


                <h3
                    class="text-lg
                           font-semibold
                           text-gray-700">

                    No rooms found

                </h3>


                <p class="text-sm text-gray-500 mt-1">
                    This building currently has no rooms assigned.
                </p>

            </div>

        @endforelse

    </div>

</div>

@endsection