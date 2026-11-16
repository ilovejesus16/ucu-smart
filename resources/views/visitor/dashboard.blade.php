
@php
    $navigationLayout = 'layouts.visitor';
    $navigationAccess = 'Visitor Access';

    if (request()->routeIs('student.campus-navigation')) {
        $navigationLayout = 'layouts.student';
        $navigationAccess = 'Student Access';
    } elseif (request()->routeIs('instructor.campus-navigation')) {
        $navigationLayout = 'layouts.instructor';
        $navigationAccess = 'Instructor Access';
    }
@endphp

@extends($navigationLayout)

@section('title', 'Campus Map')

@section('content')

<div
    x-data="campusMapViewer()"
    data-buildings="{{ json_encode($buildings->map(function ($building) {
        return [
            'id' => $building->id,
            'code' => $building->building_code,
            'name' => $building->building_name,
            'description' => $building->description,
            'total_floor_area' => $building->total_floor_area,
            'year_constructed' => $building->year_constructed,
            'classroom_count' => $building->classroom_count,
            'laboratory_count' => $building->laboratory_count,
            'office_count' => $building->office_count,
            'restroom_count' => $building->restroom_count,
            'function_room_count' => $building->function_room_count,
            'rooms' => $building->rooms->map(function ($room) {
                return [
                    'id' => $room->id,
                    'number' => $room->room_number,
                    'name' => $room->room_name,
                    'floor' => $room->floor,
                    'college_assignments' => $room->collegeAssignments->map(function ($assignment) {
                        return [
                            'college_code' => $assignment->college->college_code,
                            'college_name' => $assignment->college->college_name,
                            'color' => $assignment->college->color,
                            'is_primary' => $assignment->is_primary,
                            'room_type' => $assignment->room_type,
                            'remarks' => $assignment->remarks,
                        ];
                    })->values(),
                ];
            })->values(),
        ];
    })->values()) }}"
    data-locations="{{ json_encode($locations->map(function ($location) {
        return [
            'id' => $location->id,
            'number' => $location->number,
            'name' => $location->name,
            'type' => $location->type,
            'description' => $location->description,
            'building_id' => $location->building_id,
            'map_x' => $location->map_x,
            'map_y' => $location->map_y,
            'marker_size' => $location->marker_size ?? 28,
            'total_floor_area' => $location->total_floor_area,
            'year_constructed' => $location->year_constructed,
            'classroom_count' => $location->classroom_count,
            'laboratory_count' => $location->laboratory_count,
            'office_count' => $location->office_count,
            'restroom_count' => $location->restroom_count,
            'function_room_count' => $location->function_room_count,
        ];
    })->values()) }}"
    class="mx-auto max-w-[1600px] space-y-6"
>

    {{-- PAGE HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <div class="mb-2 flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#0E4C6B]/10">
                    <x-heroicon-o-map class="h-6 w-6 text-[#0E4C6B]" />
                </div>

                <span class="rounded-full bg-[#0E4C6B]/10 px-3 py-1 text-xs font-bold text-[#0E4C6B]">
                    {{ $navigationAccess }}
                </span>
            </div>

            <h1 class="text-3xl font-extrabold tracking-tight text-[#0E2958] sm:text-4xl">
                Campus Map
            </h1>

            <p class="mt-2 max-w-3xl text-sm text-gray-500">
                Explore Urdaneta City University, discover campus buildings,
                and view room and college information.
            </p>
        </div>

        <div class="flex items-center gap-2 self-start rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2.5">
            <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
            <span class="text-xs font-bold text-emerald-700">View-only access</span>
        </div>

    </div>


    {{-- MAP AND DETAILS --}}
    <div class="grid grid-cols-1 gap-5 xl:grid-cols-[minmax(0,1fr)_350px]">

        {{-- MAP WORKSPACE --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

            {{-- MAP HEADER --}}
            <div class="flex flex-col gap-4 border-b border-gray-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <h2 class="font-bold text-[#0E2958]">
                        UCU Campus Overview
                    </h2>

                    <p class="mt-1 text-xs text-gray-500">
                        Select a numbered marker to explore its location.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">

                    <button
                        type="button"
                        @click="zoomOut()"
                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 transition hover:border-[#0E4C6B] hover:text-[#0E4C6B]"
                        title="Zoom out"
                    >
                        <x-heroicon-o-minus class="h-4 w-4" />
                    </button>

                    <span
                        class="min-w-[55px] text-center text-xs font-bold tabular-nums text-gray-600"
                        x-text="Math.round(zoom * 100) + '%'"
                    ></span>

                    <button
                        type="button"
                        @click="zoomIn()"
                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 transition hover:border-[#0E4C6B] hover:text-[#0E4C6B]"
                        title="Zoom in"
                    >
                        <x-heroicon-o-plus class="h-4 w-4" />
                    </button>

                    <button
                        type="button"
                        @click="resetView()"
                        class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-600 transition hover:bg-gray-50"
                    >
                        Reset View
                    </button>

                    <button
                        type="button"
                        @click="toggleFullscreen()"
                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 transition hover:border-[#0E4C6B] hover:text-[#0E4C6B]"
                        title="Fullscreen"
                    >
                        <x-heroicon-o-arrows-pointing-out class="h-4 w-4" />
                    </button>

                </div>
            </div>


            {{-- MAP DISPLAY MODE --}}
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 bg-gray-50/70 px-5 py-3">

                <div class="flex rounded-lg border border-gray-200 bg-white p-1">

                    <button
                        type="button"
                        @click="mapMode = 'normal'; selectedCollege = null"
                        class="rounded-md px-3 py-2 text-xs font-semibold transition"
                        :class="mapMode === 'normal'
                            ? 'bg-[#0E2958] text-white shadow-sm'
                            : 'text-gray-600 hover:bg-gray-50'"
                    >
                        Normal
                    </button>

                    <button
                        type="button"
                        @click="mapMode = 'college'; selectedCollege = null"
                        class="rounded-md px-3 py-2 text-xs font-semibold transition"
                        :class="mapMode === 'college'
                            ? 'bg-[#0E2958] text-white shadow-sm'
                            : 'text-gray-600 hover:bg-gray-50'"
                    >
                        By College
                    </button>

                </div>

                <p class="text-[11px] text-gray-500">
                    <span x-text="positionedLocations.length"></span>
                    mapped locations
                </p>

            </div>


            {{-- MAP VIEWPORT --}}
            <div
                x-ref="viewport"
                @pointerdown="startPan($event)"
                @pointermove="panMap($event)"
                @pointerup="stopPan($event)"
                @pointercancel="stopPan($event)"
                @wheel.prevent="handleWheelZoom($event)"
                class="relative w-full select-none overflow-hidden bg-[#EAF0F6]"
                :class="panning ? 'cursor-grabbing' : 'cursor-grab'"
                style="height: 620px; touch-action: none; background-image: linear-gradient(rgba(14,41,88,.055) 1px, transparent 1px), linear-gradient(90deg, rgba(14,41,88,.055) 1px, transparent 1px); background-size: 28px 28px;"
            >

                {{-- FLOATING COLLEGE LEGEND --}}
                <div
                    x-show="mapMode === 'college'"
                    x-cloak
                    @pointerdown.stop
                    @click.stop
                    class="absolute right-4 top-4 z-[100] max-h-[85%] w-52 overflow-y-auto rounded-xl border border-gray-200 bg-white/95 p-4 shadow-xl backdrop-blur-sm"
                >

                    <div class="mb-3 flex items-center justify-between gap-2">
                        <h3 class="text-sm font-bold text-[#0E2958]">
                            College Legend
                        </h3>

                        <button
                            type="button"
                            @click="selectedCollege = null"
                            class="text-xs font-semibold text-[#0E4C6B] hover:underline"
                        >
                            Show All
                        </button>
                    </div>

                    <div class="space-y-2">

                        @foreach($colleges as $college)
                            <button
                                type="button"
                                @click="selectedCollege = selectedCollege === @js($college->college_code) ? null : @js($college->college_code)"
                                class="flex w-full items-center gap-2 rounded-lg border bg-white px-3 py-2 text-left text-xs font-semibold transition hover:bg-gray-50"
                                :class="selectedCollege === @js($college->college_code) ? 'ring-2 ring-offset-1' : 'opacity-80 hover:opacity-100'"
                                style="border-color: {{ $college->color }}; color: {{ $college->color }};"
                            >
                                <span class="h-3 w-3 shrink-0 rounded-full"
                                      style="background-color: {{ $college->color }}"></span>

                                {{ $college->college_code }}

                                <span
                                    x-show="selectedCollege === @js($college->college_code)"
                                    class="ml-auto"
                                >
                                    ✓
                                </span>
                            </button>
                        @endforeach

                    </div>
                </div>


                {{-- MAP CANVAS --}}
                <div
                    x-ref="canvas"
                    class="absolute left-1/2 top-1/2 w-[700px] max-w-none"
                    :style="`
                        transform: translate(calc(-50% + ${panX}px), calc(-50% + ${panY}px)) scale(${zoom});
                        transform-origin: center center;
                    `"
                >

                    <img
                        src="{{ asset('images/mapnl.png') }}"
                        alt="Urdaneta City University Campus Map"
                        draggable="false"
                        class="pointer-events-none block h-auto w-full select-none"
                    >

                    {{-- DATABASE-BASED MARKERS --}}
                    <template x-for="location in positionedLocations" :key="location.id">

                        <button
                            type="button"
                            @pointerdown.stop
                            @click.stop="openLocation(location)"
                            class="group absolute z-50 flex -translate-x-1/2 -translate-y-1/2 items-center justify-center rounded-full border-2 border-white font-extrabold text-white shadow-lg transition hover:z-[60] hover:scale-110"
                            :class="selectedLocation && selectedLocation.id === location.id
                                ? 'ring-4 ring-amber-300/70'
                                : ''"
                            :style="markerStyle(location)"
                            :title="location.name"
                        >
                            <span x-text="location.number"></span>

                            <span
                                class="pointer-events-none absolute left-1/2 top-full z-[90] mt-2 -translate-x-1/2 whitespace-nowrap rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-[#0E2958] opacity-0 shadow-lg transition group-hover:opacity-100"
                                x-text="location.name"
                            ></span>
                        </button>

                    </template>

                </div>


                {{-- MAP INFO --}}
                <div class="pointer-events-none absolute left-4 top-4 rounded-xl border border-gray-200 bg-white/95 px-4 py-3 shadow-sm backdrop-blur">
                    <p class="text-xs font-bold text-[#0E2958]">
                        UCU Campus Map
                    </p>

                    <p class="mt-1 text-[11px] text-gray-500">
                        Drag to move · Scroll to zoom
                    </p>
                </div>


                {{-- ZOOM LEVEL --}}
                <div class="pointer-events-none absolute bottom-4 left-4 rounded-lg border border-gray-200 bg-white/95 px-3 py-2 shadow-sm">
                    <span
                        class="text-[11px] font-bold text-gray-600"
                        x-text="Math.round(zoom * 100) + '%'"
                    ></span>
                </div>

            </div>


            {{-- MAP FOOTER --}}
            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 bg-gray-50/70 px-5 py-3">

                <p class="text-xs text-gray-500">
                    <span class="font-bold text-[#0E2958]" x-text="positionedLocations.length"></span>
                    locations displayed on the map
                </p>

                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700">
                    <x-heroicon-o-eye class="h-4 w-4" />
                    View only
                </span>

            </div>

        </div>


        {{-- RIGHT INFORMATION PANEL --}}
        <div class="space-y-5">

            {{-- SELECTED LOCATION DETAILS --}}
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

                <div class="border-b border-gray-100 px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#0E4C6B]/10">
                            <x-heroicon-o-building-office-2 class="h-5 w-5 text-[#0E4C6B]" />
                        </div>

                        <div>
                            <h2 class="font-bold text-[#0E2958]">
                                Location Information
                            </h2>

                            <p class="mt-0.5 text-xs text-gray-500">
                                Building and facility details
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-5">

                    <template x-if="!selectedLocation">
                        <div class="py-8 text-center">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-50">
                                <x-heroicon-o-map-pin class="h-7 w-7 text-gray-400" />
                            </div>

                            <h3 class="mt-4 text-sm font-bold text-[#0E2958]">
                                Explore the campus
                            </h3>

                            <p class="mx-auto mt-2 max-w-[230px] text-xs leading-5 text-gray-500">
                                Select any numbered marker on the map to view its information.
                            </p>
                        </div>
                    </template>


                    <template x-if="selectedLocation">

                        <div>

                            <p
                                class="text-[11px] font-bold uppercase tracking-wider text-[#0E4C6B]"
                                x-text="'Campus Location ' + selectedLocation.number"
                            ></p>

                            <h3
                                class="mt-1 text-lg font-extrabold leading-snug text-[#0E2958]"
                                x-text="selectedLocation.building ? selectedLocation.building.name : selectedLocation.name"
                            ></h3>

                            <p
                                class="mt-2 text-xs leading-5 text-gray-500"
                                x-text="selectedLocation.building
                                    ? 'Building Code: ' + selectedLocation.building.code
                                    : (selectedLocation.description || selectedLocation.type || 'Campus facility or landmark')"
                            ></p>


                            {{-- BUILDING INFORMATION --}}
                            <template x-if="selectedLocation.building">

                                <div class="mt-5 space-y-4">

                                    <div class="grid grid-cols-2 gap-2">

                                        <div class="rounded-xl bg-gray-50 p-3">
                                            <p class="text-[11px] text-gray-500">
                                                Total Rooms
                                            </p>

                                            <p
                                                class="mt-1 text-2xl font-extrabold text-[#0E2958]"
                                                x-text="selectedLocation.building.rooms.length"
                                            ></p>
                                        </div>

                                        <div class="rounded-xl bg-gray-50 p-3">
                                            <p class="text-[11px] text-gray-500">
                                                Floor Area
                                            </p>

                                            <p
                                                class="mt-1 text-lg font-extrabold text-[#0E2958]"
                                                x-text="selectedLocation.building.total_floor_area
                                                    ? selectedLocation.building.total_floor_area + ' m²'
                                                    : '—'"
                                            ></p>
                                        </div>

                                    </div>


                                    <div class="rounded-xl border border-gray-200 p-3">

                                        <h4 class="mb-3 text-xs font-bold uppercase tracking-wide text-gray-500">
                                            Building Information
                                        </h4>

                                        <div class="space-y-2 text-xs">

                                            <p class="flex justify-between gap-3">
                                                <span class="text-gray-500">Year Constructed</span>
                                                <span class="font-semibold text-gray-800" x-text="selectedLocation.building.year_constructed || '—'"></span>
                                            </p>

                                            <p class="flex justify-between gap-3">
                                                <span class="text-gray-500">Classrooms</span>
                                                <span class="font-semibold text-gray-800" x-text="selectedLocation.building.classroom_count ?? '—'"></span>
                                            </p>

                                            <p class="flex justify-between gap-3">
                                                <span class="text-gray-500">Laboratories</span>
                                                <span class="font-semibold text-gray-800" x-text="selectedLocation.building.laboratory_count ?? '—'"></span>
                                            </p>

                                            <p class="flex justify-between gap-3">
                                                <span class="text-gray-500">Offices</span>
                                                <span class="font-semibold text-gray-800" x-text="selectedLocation.building.office_count ?? '—'"></span>
                                            </p>

                                            <p class="flex justify-between gap-3">
                                                <span class="text-gray-500">Restrooms</span>
                                                <span class="font-semibold text-gray-800" x-text="selectedLocation.building.restroom_count ?? '—'"></span>
                                            </p>

                                            <p class="flex justify-between gap-3">
                                                <span class="text-gray-500">Function Rooms</span>
                                                <span class="font-semibold text-gray-800" x-text="selectedLocation.building.function_room_count ?? '—'"></span>
                                            </p>

                                        </div>

                                        <template x-if="selectedLocation.building.description">
                                            <p class="mt-3 border-t border-gray-100 pt-3 text-xs leading-5 text-gray-600"
                                               x-text="selectedLocation.building.description"></p>
                                        </template>

                                    </div>


                                    {{-- ROOMS --}}
                                    <div>

                                        <div class="mb-3 flex items-center justify-between">
                                            <h4 class="text-sm font-bold text-[#0E2958]">
                                                Registered Rooms
                                            </h4>

                                            <span class="rounded-full bg-[#0E4C6B]/10 px-2.5 py-1 text-[10px] font-bold text-[#0E4C6B]"
                                                  x-text="selectedLocation.building.rooms.length + ' rooms'"></span>
                                        </div>

                                        <div class="max-h-[350px] space-y-2 overflow-y-auto pr-1">

                                            <template x-if="selectedLocation.building.rooms.length === 0">
                                                <p class="rounded-lg bg-gray-50 p-3 text-xs text-gray-500">
                                                    No rooms have been registered for this building.
                                                </p>
                                            </template>

                                            <template x-for="room in selectedLocation.building.rooms" :key="room.id">

                                                <div class="rounded-xl border border-gray-200 p-3">

                                                    <p class="text-sm font-bold text-[#0E2958]"
                                                       x-text="room.number || room.name || ('Room ' + room.id)"></p>

                                                    <p x-show="room.name && room.number && room.name !== room.number"
                                                       class="mt-1 text-xs text-gray-500"
                                                       x-text="room.name"></p>

                                                    <p class="mt-1 text-xs text-gray-500"
                                                       x-text="'Floor: ' + (room.floor ?? 'Not specified')"></p>

                                                    <template x-if="room.college_assignments && room.college_assignments.length">

                                                        <div class="mt-3">
                                                            <p class="mb-2 text-[10px] font-bold uppercase tracking-wide text-gray-400">
                                                                Assigned Colleges
                                                            </p>

                                                            <div class="flex flex-wrap gap-1.5">

                                                                <template x-for="assignment in room.college_assignments" :key="assignment.college_code">

                                                                    <span
                                                                        class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-[10px] font-bold"
                                                                        :style="`background:${assignment.color}18;color:${assignment.color};border:1px solid ${assignment.color}55;`"
                                                                    >
                                                                        <span class="h-1.5 w-1.5 rounded-full"
                                                                              :style="`background:${assignment.color}`"></span>

                                                                        <span x-text="assignment.college_code"></span>

                                                                        <span class="opacity-70"
                                                                              x-text="assignment.is_primary ? 'Primary' : 'Shared'"></span>
                                                                    </span>

                                                                </template>

                                                            </div>
                                                        </div>

                                                    </template>

                                                </div>

                                            </template>

                                        </div>

                                    </div>

                                </div>

                            </template>


                            {{-- NON-BUILDING LOCATION --}}
                            <template x-if="!selectedLocation.building">
                                <div class="mt-5 rounded-xl border border-gray-200 bg-gray-50 p-4">

                                    <div class="flex items-center gap-2">
                                        <x-heroicon-o-map-pin class="h-4 w-4 text-[#0E4C6B]" />

                                        <p class="text-xs font-bold text-[#0E2958]">
                                            Campus Facility / Landmark
                                        </p>
                                    </div>

                                    <p class="mt-2 text-xs leading-5 text-gray-500"
                                       x-text="selectedLocation.description || 'Additional building information is not available for this location.'"></p>

                                </div>
                            </template>


                            <button
                                type="button"
                                @click="selectedLocation = null"
                                class="mt-5 w-full rounded-lg border border-gray-200 px-4 py-2.5 text-xs font-semibold text-gray-600 transition hover:bg-gray-50"
                            >
                                Close Details
                            </button>

                        </div>

                    </template>

                </div>
            </div>


            {{-- MAP GUIDE --}}
            <div class="overflow-hidden rounded-2xl bg-[#0E2958] p-5 text-white shadow-sm">

                <div class="flex items-start gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/10">
                        <x-heroicon-o-information-circle class="h-5 w-5" />
                    </div>

                    <div>
                        <h3 class="text-sm font-bold">
                            Campus Map Guide
                        </h3>

                        <p class="mt-2 text-xs leading-5 text-blue-100">
                            Select a numbered marker to explore a building or facility.
                            Use By College to identify locations associated with each college.
                            Interactive navigation and route guidance are available through the UCU Smart+ mobile application.
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<script>
document.addEventListener('alpine:init', () => {

    Alpine.data('campusMapViewer', () => ({

        zoom: 1,
        panX: 0,
        panY: 0,
        panning: false,

        startMouseX: 0,
        startMouseY: 0,
        startPanX: 0,
        startPanY: 0,

        mapMode: 'normal',
        selectedCollege: null,

        buildings: [],
        locations: [],
        selectedLocation: null,

        buildingMap: {
            1: 'B01', 2: 'B02', 3: 'B03', 4: 'B04', 5: 'B05',
            6: 'B06', 7: 'B07', 8: 'B08', 9: 'B09', 10: 'B10',
            11: 'B11', 12: 'B12', 13: 'B17', 15: 'B19',
            17: 'B14', 18: 'B13', 25: 'B18',
        },

        init() {
            try {
                this.buildings = JSON.parse(this.$el.dataset.buildings || '[]');
                this.locations = JSON.parse(this.$el.dataset.locations || '[]');
            } catch (error) {
                console.error('Unable to load campus map data:', error);
                this.buildings = [];
                this.locations = [];
            }
        },

        get positionedLocations() {
            return this.locations.filter(location =>
                location.map_x !== null &&
                location.map_y !== null
            );
        },

        buildingForLocation(location) {
            if (location.building_id) {
                const linkedBuilding = this.buildings.find(building =>
                    Number(building.id) === Number(location.building_id)
                );

                if (linkedBuilding) return linkedBuilding;
            }

            const code = this.buildingMap[Number(location.number)];

            if (!code) return null;

            return this.buildings.find(building =>
                building.code === code
            ) || null;
        },

        collegeAssignments(location) {
            const building = this.buildingForLocation(location);

            if (!building) return [];

            const assignments = (building.rooms || [])
                .flatMap(room => room.college_assignments || [])
                .filter(assignment => assignment.is_primary === true);

            return [...new Map(
                assignments.map(assignment => [
                    assignment.college_code,
                    assignment
                ])
            ).values()];
        },

        markerCollegeColors(location) {
            return this.collegeAssignments(location)
                .map(assignment => assignment.color)
                .filter(Boolean);
        },

        markerHasCollege(location, collegeCode) {
            return this.collegeAssignments(location)
                .some(assignment => assignment.college_code === collegeCode);
        },

        markerStyle(location) {
            const size = Number(location.marker_size || 28);

            if (this.mapMode !== 'college') {
                return `
                    left:${location.map_x}%;
                    top:${location.map_y}%;
                    width:${size}px;
                    height:${size}px;
                    font-size:${Math.max(3, Math.round(size * 0.36))}px;
                    background:#0E4C6B;
                `;
            }

            const colors = this.markerCollegeColors(location);

            let background = '#6B7280';

            if (colors.length > 0) {
                const segment = 100 / colors.length;

                const gradients = colors.map((color, index) => {
                    const start = index * segment;
                    const end = (index + 1) * segment;

                    return `${color} ${start}% ${end}%`;
                });

                background = `conic-gradient(${gradients.join(', ')})`;
            }

            const isDimmed = this.selectedCollege &&
                !this.markerHasCollege(location, this.selectedCollege);

            return `
                left:${location.map_x}%;
                top:${location.map_y}%;
                width:${size}px;
                height:${size}px;
                font-size:${Math.max(3, Math.round(size * 0.36))}px;
                background:${background};
                opacity:${isDimmed ? 0.2 : 1};
            `;
        },

        openLocation(location) {
            const building = this.buildingForLocation(location);

            this.selectedLocation = {
                ...location,
                building: building || null
            };
        },

        zoomIn() {
            this.zoom = Math.min(
                Number((this.zoom + 0.25).toFixed(2)),
                2.5
            );
        },

        zoomOut() {
            this.zoom = Math.max(
                Number((this.zoom - 0.25).toFixed(2)),
                0.5
            );
        },

        resetView() {
            this.zoom = 1;
            this.panX = 0;
            this.panY = 0;
        },

        handleWheelZoom(event) {
            const step = event.deltaY < 0 ? 0.1 : -0.1;

            this.zoom = Math.max(
                0.5,
                Math.min(2.5, Number((this.zoom + step).toFixed(2)))
            );
        },

        startPan(event) {
            if (
                event.button !== 0 ||
                event.target.closest('button')
            ) {
                return;
            }

            this.panning = true;

            this.startMouseX = event.clientX;
            this.startMouseY = event.clientY;

            this.startPanX = this.panX;
            this.startPanY = this.panY;

            try {
                this.$refs.viewport.setPointerCapture(event.pointerId);
            } catch (error) {}
        },

        panMap(event) {
            if (!this.panning) return;

            this.panX =
                this.startPanX + event.clientX - this.startMouseX;

            this.panY =
                this.startPanY + event.clientY - this.startMouseY;
        },

        stopPan(event) {
            this.panning = false;

            try {
                if (
                    event &&
                    this.$refs.viewport.hasPointerCapture(event.pointerId)
                ) {
                    this.$refs.viewport.releasePointerCapture(event.pointerId);
                }
            } catch (error) {}
        },

        async toggleFullscreen() {
            try {
                if (!document.fullscreenElement) {
                    await this.$refs.viewport.requestFullscreen();
                } else {
                    await document.exitFullscreen();
                }
            } catch (error) {
                console.error('Fullscreen error:', error);
            }
        }

    }));

});
</script>

@endsection
