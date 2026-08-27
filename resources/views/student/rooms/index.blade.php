@extends('layouts.student')

@section('title', 'Room Availability')

@section('content')

<div
    class="max-w-7xl mx-auto"
    x-data="{ search: '' }">

    <!-- ========================================================= -->
    <!-- PAGE HEADER -->
    <!-- ========================================================= -->

    <div class="mb-8">

        <h1 class="text-3xl sm:text-4xl font-extrabold text-[#0E2958]">
            Room Availability
        </h1>

        <p class="text-gray-500 mt-2">
            Search for a building or classroom to check its availability.
        </p>

    </div>


  <!-- ========================================================= -->
<!-- SEARCH -->
<!-- ========================================================= -->

<div
    class="bg-white
           rounded-2xl
           border border-gray-200
           shadow-sm
           p-4 sm:p-5
           mb-6">

    <div class="relative">

        <!-- Search Icon -->

        <div
            class="absolute
                   left-3
                   top-1/2
                   -translate-y-1/2
                   z-10
                   pointer-events-none">

            <x-heroicon-o-magnifying-glass
                class="w-5 h-5 text-gray-400"/>

        </div>


        <!-- Search Input -->

        <input
            type="text"
            x-model="search"
            placeholder="Search building, room number, or room name..."
            style="padding-left: 3rem;"
            class="w-full
                   pr-12
                   py-3.5
                   bg-gray-50
                   border border-gray-200
                   rounded-xl
                   text-gray-800
                   placeholder-gray-400
                   focus:bg-white
                   focus:border-[#0E4C6B]
                   focus:ring-2
                   focus:ring-[#0E4C6B]/10
                   outline-none
                   transition">


        <!-- Clear Search -->

        <button
            type="button"
            x-show="search.length > 0"
            @click="search = ''"
            class="absolute
                   right-4
                   top-1/2
                   -translate-y-1/2
                   text-gray-400
                   hover:text-gray-700
                   transition">

            <x-heroicon-o-x-mark
                class="w-5 h-5"/>

        </button>

    </div>


    <p class="text-sm text-gray-500 mt-3">

        Search by building name, room number, or room name.

    </p>

</div>


    <!-- ========================================================= -->
    <!-- BUILDINGS -->
    <!-- ========================================================= -->

    <div
        class="grid
               grid-cols-1
               md:grid-cols-2
               xl:grid-cols-3
               gap-5">


        @forelse($buildings as $building)

            <!-- ================================================= -->
            <!-- BUILDING CARD -->
            <!-- ================================================= -->

            <a
                href="{{ route('student.rooms.building', $building) }}"

                x-show="
                    search.trim() === '' ||

                    '{{ strtolower($building->building_name) }}'
                        .includes(search.trim().toLowerCase()) ||

                    '{{ strtolower($building->rooms->pluck('room_number')->join(' ')) }}'
                        .includes(search.trim().toLowerCase()) ||

                    '{{ strtolower($building->rooms->pluck('room_name')->join(' ')) }}'
                        .includes(search.trim().toLowerCase())
                "

                x-transition

                class="group
                       bg-white
                       rounded-2xl
                       border border-gray-200
                       shadow-sm
                       hover:shadow-md
                       hover:border-[#0E4C6B]/30
                       transition
                       overflow-hidden
                       flex flex-col">


                <!-- ============================================= -->
                <!-- BUILDING IMAGE -->
                <!-- ============================================= -->

                @if($building->image)

                    <div class="h-52 overflow-hidden">

                        <img
                            src="{{ asset('storage/' . $building->image) }}"
                            alt="{{ $building->building_name }}"
                            class="w-full
                                   h-full
                                   object-cover
                                   group-hover:scale-105
                                   transition
                                   duration-500">

                    </div>

                @else

                    <div
                        class="h-52
                               bg-gray-100
                               flex
                               items-center
                               justify-center">

                        <div class="text-center">

                            <x-heroicon-o-building-office-2
                                class="w-12 h-12
                                       text-gray-300
                                       mx-auto"/>

                            <p
                                class="text-sm
                                       text-gray-400
                                       mt-2">

                                No image available

                            </p>

                        </div>

                    </div>

                @endif


                <!-- ============================================= -->
                <!-- BUILDING CONTENT -->
                <!-- ============================================= -->

                <div
                    class="p-5 sm:p-6
                           flex flex-col
                           flex-1">


                    <!-- Building Name -->

                    <h2
                        class="text-xl
                               font-bold
                               text-[#0E2958]
                               break-words">

                        {{ $building->building_name }}

                    </h2>


                    <!-- Description -->

                    <p
                        class="text-sm
                               text-gray-500
                               mt-2
                               leading-relaxed
                               line-clamp-2">

                        {{ $building->description ?: 'No description available.' }}

                    </p>


                    <!-- ========================================= -->
                    <!-- BOTTOM -->
                    <!-- ========================================= -->

                    <div
                        class="mt-6
                               pt-5
                               border-t border-gray-100
                               flex items-center
                               justify-between
                               gap-3">


                        <!-- Room Count -->

                        <span
                            class="inline-flex
                                   items-center
                                   gap-2
                                   bg-[#0E4C6B]/10
                                   text-[#0E4C6B]
                                   px-3
                                   py-1.5
                                   rounded-lg
                                   text-sm
                                   font-semibold">

                            <x-heroicon-o-home-modern
                                class="w-4 h-4"/>

                            {{ $building->rooms_count }}

                            {{ Str::plural(
                                'Room',
                                $building->rooms_count
                            ) }}

                        </span>


                        <!-- View Building -->

                        <span
                            class="inline-flex
                                   items-center
                                   gap-1
                                   text-[#0E4C6B]
                                   font-semibold
                                   text-sm
                                   group-hover:translate-x-1
                                   transition">

                            View Building

                            <x-heroicon-o-arrow-right
                                class="w-4 h-4"/>

                        </span>

                    </div>

                </div>

            </a>

        @empty

            <!-- ================================================= -->
            <!-- NO BUILDINGS -->
            <!-- ================================================= -->

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

                    <x-heroicon-o-building-office-2
                        class="w-8 h-8
                               text-gray-400"/>

                </div>


                <h3
                    class="text-lg
                           font-semibold
                           text-gray-700">

                    No buildings available

                </h3>


                <p
                    class="text-sm
                           text-gray-500
                           mt-1">

                    There are currently no buildings to display.

                </p>

            </div>

        @endforelse


        <!-- ========================================================= -->
        <!-- NO SEARCH MATCH -->
        <!-- ========================================================= -->

        <div
            x-show="
                search.trim() !== '' &&
                !Array.from(
                    $el.parentElement.querySelectorAll('a')
                ).some(
                    el => el.style.display !== 'none'
                )
            "
            class="col-span-full
                   bg-white
                   rounded-2xl
                   border border-gray-200
                   shadow-sm
                   p-12
                   text-center"
            style="display: none;">

            <div
                class="w-16 h-16
                       rounded-2xl
                       bg-gray-100
                       flex items-center
                       justify-center
                       mx-auto mb-4">

                <x-heroicon-o-magnifying-glass
                    class="w-8 h-8
                           text-gray-400"/>

            </div>


            <h3
                class="text-lg
                       font-semibold
                       text-gray-700">

                No matching rooms found

            </h3>


            <p
                class="text-sm
                       text-gray-500
                       mt-1">

                Try a different building name, room number, or room name.

            </p>

        </div>

    </div>

</div>

@endsection