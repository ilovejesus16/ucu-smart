@extends('layouts.admin')

@section('title', 'Room Monitoring')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    <!-- Page Header -->
    <div class="mb-8">

        <div class="flex items-center gap-3">

            <div class="w-12 h-12 rounded-xl bg-[#0E4C6B]/10
                        flex items-center justify-center">

                <x-heroicon-o-clipboard-document-check
                    class="w-7 h-7 text-[#0E4C6B]"/>

            </div>

            <div>

                <h1 class="text-3xl sm:text-4xl font-extrabold text-[#0E2958]">
                    Room Monitoring
                </h1>

                <p class="text-gray-500 mt-1">
                    Monitor classroom usage and verify instructor attendance.
                </p>

            </div>

        </div>

    </div>


    <!-- Information -->
    <div class="bg-[#0E4C6B]/5 border border-[#0E4C6B]/10
                rounded-2xl p-5">

        <div class="flex items-start gap-3">

            <x-heroicon-o-information-circle
                class="w-6 h-6 text-[#0E4C6B] flex-shrink-0"/>

            <div>

                <h2 class="font-bold text-[#0E2958]">
                    Attendance Monitoring
                </h2>

                <p class="text-sm text-gray-600 mt-1 leading-relaxed">
                    Select a building to view its classrooms, scheduled
                    instructors, and attendance monitoring records.
                </p>

            </div>

        </div>

    </div>


    <!-- Buildings -->
    <div>

        <div class="flex items-center justify-between mb-5">

            <h2 class="text-xl font-bold text-[#0E2958]">
                Campus Buildings
            </h2>

            <span class="text-sm text-gray-500">
                {{ $buildings->count() }}
                {{ Str::plural('Building', $buildings->count()) }}
            </span>

        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

            @forelse($buildings as $building)

                <a
                    href="{{ route('admin.room-monitoring.buildings', $building) }}"
                    class="group bg-white rounded-2xl border border-gray-200
                           shadow-sm hover:shadow-md
                           hover:border-[#0E4C6B]/30
                           transition overflow-hidden flex flex-col">

                    <!-- Building Image -->

                    @if($building->image)

                        <div class="h-52 overflow-hidden">

                            <img
                                src="{{ asset('storage/' . $building->image) }}"
                                alt="{{ $building->building_name }}"
                                class="w-full h-full object-cover
                                       group-hover:scale-105
                                       transition duration-500">

                        </div>

                    @else

                        <div class="h-52 bg-gray-100
                                    flex items-center justify-center">

                            <div class="text-center">

                                <x-heroicon-o-building-office-2
                                    class="w-12 h-12 text-gray-300 mx-auto"/>

                                <p class="text-sm text-gray-400 mt-2">
                                    No image available
                                </p>

                            </div>

                        </div>

                    @endif


                    <!-- Content -->

                    <div class="p-5 sm:p-6 flex flex-col flex-1">

                        <h3 class="text-xl font-bold text-[#0E2958] break-words">
                            {{ $building->building_name }}
                        </h3>

                        <p class="text-sm text-gray-500 mt-2
                                  leading-relaxed line-clamp-2">

                            {{ $building->description ?: 'No description available.' }}

                        </p>


                        <!-- Bottom -->

                        <div class="mt-6 pt-5 border-t border-gray-100
                                    flex items-center justify-between gap-3">

                            <span class="inline-flex items-center gap-2
                                         bg-[#0E4C6B]/10 text-[#0E4C6B]
                                         px-3 py-1.5 rounded-lg
                                         text-sm font-semibold">

                                <x-heroicon-o-home-modern class="w-4 h-4"/>

                                {{ $building->rooms_count }}

                                {{ Str::plural('Room', $building->rooms_count) }}

                            </span>


                            <span class="inline-flex items-center gap-1
                                         text-[#0E4C6B] font-semibold text-sm
                                         group-hover:translate-x-1 transition">

                                Monitor Building

                                <x-heroicon-o-arrow-right class="w-4 h-4"/>

                            </span>

                        </div>

                    </div>

                </a>

            @empty

                <div class="col-span-full bg-white rounded-2xl
                            border border-gray-200 shadow-sm
                            p-12 text-center">

                    <div class="w-16 h-16 rounded-2xl bg-gray-100
                                flex items-center justify-center
                                mx-auto mb-4">

                        <x-heroicon-o-building-office-2
                            class="w-8 h-8 text-gray-400"/>

                    </div>

                    <h3 class="text-lg font-semibold text-gray-700">
                        No buildings available
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        There are currently no buildings to monitor.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection