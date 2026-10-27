@extends('layouts.admin')

@section('title', 'Campus Map')

@section('content')
<div
    x-data="campusMapEditor()"
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
            ];
        })->values(),
    ];
})->values()) }}"
    class="mx-auto max-w-[1600px] space-y-1"
>

    <div class="mb-6">
        <div class="mb-1 flex items-center gap-3"><div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#0E2958] text-white shadow-sm"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6.5 8.5 4 15.5 6.5 21 4v13.5L15.5 20 8.5 17.5 3 20V6.5Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M8.5 4v13.5M15.5 6.5V20"/></svg></div><h1 class="text-3xl font-extrabold tracking-tight text-[#0E2958]">Campus Map</h1></div>
        <p class="text-gray-500 mt-2">
            Explore campus buildings and facilities. Select a numbered marker to view its details.
        </p>
    </div>

    <div class="mb-4" x-cloak x-show="message">
        <div class="rounded-xl border px-4 py-3 text-sm"
             :class="messageType === 'success'
                ? 'border-green-200 bg-green-50 text-green-700'
                : 'border-red-200 bg-red-50 text-red-700'"
             x-text="message">
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 xl:grid-cols-[minmax(0,1fr)_350px] 2xl:gap-6">

        {{-- MAP WORKSPACE --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm">

            <div class="flex flex-col gap-4 border-b border-slate-100 bg-white px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-bold tracking-tight text-[#0E2958]">Campus overview</h2>
                    <p class="text-xs text-gray-500 mt-1"
                       x-text="editMode
                           ? 'Manage Map mode is ON. Select a marker to resize it, or drag markers to arrange them.'
                           : 'Monitoring view · Select a marker to view building details.'">
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <button type="button"
                            @click="toggleEditMode()"
                            class="px-4 py-2 rounded-lg text-sm font-semibold border transition"
                            :class="editMode
                                ? 'bg-amber-500 border-amber-600 text-white hover:bg-amber-600'
                                : 'bg-[#0E2958] border-[#0E2958] text-white hover:bg-[#0B2148]'"
                            x-text="editMode ? 'Exit Edit Mode' : 'Edit Locations'">
                    </button>

                    <button type="button"
                            @click="zoomOut()"
                            class="w-10 h-10 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 font-bold">
                        −
                    </button>

                    <div class="min-w-[65px] text-center text-sm font-semibold text-gray-600"
                         x-text="Math.round(zoom * 100) + '%'">
                    </div>

                    <button type="button"
                            @click="zoomIn()"
                            class="w-10 h-10 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 font-bold">
                        +
                    </button>

                    <button type="button"
                            @click="resetView()"
                            class="px-4 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-sm font-semibold">
                        Reset View
                    </button>
                </div>
            </div>

            {{-- SELECTED MARKER SIZE CONTROLS --}}
            <div x-show="editMode && selectedMarkerId !== null"
                 x-cloak
                 class="px-5 py-3 border-b border-gray-200 bg-amber-50 flex flex-wrap items-center gap-3">

                <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold text-[#0E2958]">Selected location</p>
                    <p class="text-xs text-gray-600" x-text="selectedMarkerName"></p>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button"
                            @click="resizeSelected(-2)"
                            :disabled="saving || selectedMarkerSize <= 4"
                            class="w-9 h-9 rounded-lg border bg-white font-bold hover:bg-gray-100 disabled:opacity-50">
                        −
                    </button>

                    <span class="min-w-[90px] text-center text-sm font-semibold text-gray-700"
                          x-text="selectedMarkerSize + ' px'">
                    </span>

                    <button type="button"
                            @click="resizeSelected(2)"
                            :disabled="saving || selectedMarkerSize >= 64"
                            class="w-9 h-9 rounded-lg border bg-white font-bold hover:bg-gray-100 disabled:opacity-50">
                        +
                    </button>
                </div>

                <button type="button"
                        @click="removeSelectedMarker()"
                        :disabled="saving"
                        class="px-3 py-2 rounded-lg border border-red-200 bg-white text-sm font-semibold text-red-700 hover:bg-red-50 disabled:opacity-50">
                    Remove from Map
                </button>

                <button type="button"
                        @click="deselectMarker()"
                        class="px-3 py-2 rounded-lg border border-gray-200 bg-white text-sm font-semibold hover:bg-gray-100">
                    Deselect
                </button>
            </div>

            {{-- MAP VIEWPORT --}}
            <div
                x-ref="viewport"
                @pointerdown="startPan($event)"
                @pointermove="panMap($event)"
                @pointerup="stopPan($event)"
                @pointercancel="stopPan($event)"
                @wheel.prevent="handleWheelZoom($event)"
                class="relative w-full overflow-hidden select-none bg-[#EAF0F6]"
                :class="panning ? 'cursor-grabbing' : 'cursor-grab'"
                style="height: 700px; touch-action: none; background-color: #EAF0F6; background-image: linear-gradient(rgba(14, 41, 88, 0.055) 1px, transparent 1px), linear-gradient(90deg, rgba(14, 41, 88, 0.055) 1px, transparent 1px); background-size: 28px 28px;"
            >
                <div
                    x-ref="canvas"
                    class="absolute left-1/2 top-1/2 w-[700px] max-w-none"
                    :style="`
                        transform:
                            translate(calc(-50% + ${panX}px), calc(-50% + ${panY}px))
                            scale(${zoom});
                        transform-origin: center center;
                    `"
                >
                    <img
                        src="{{ asset('images/mapnl.png') }}"
                        alt="UCU Campus Map"
                        draggable="false"
                        class="block w-full h-auto pointer-events-none select-none"
                    >

                    {{-- PLACED MARKERS --}}
                    @foreach($locations as $location)
                        @if($location->map_x !== null && $location->map_y !== null)
                            <div
                                data-campus-marker
                                x-data="campusMarker(
                                    {{ $location->id }},
                                    {{ $location->number }},
                                    {{ $location->map_x }},
                                    {{ $location->map_y }},
                                    {{ $location->marker_size ?? 28 }},
                                    @js($location->name)
                                )"
                                class="group absolute z-50"
                                :style="`left:${x}%;top:${y}%;transform:translate(-50%,-50%);`"
                                @pointerdown.stop="startMarkerDrag($event)"
                                @click.stop="handleClick()"
                            >
                                <div
                                    class="rounded-full bg-[#0E4C6B] border-2 shadow-lg flex items-center justify-center text-white font-extrabold transition"
                                    :class="[
                                        editMode ? 'cursor-grab active:cursor-grabbing' : 'cursor-default',
                                        selected ? 'border-amber-300 ring-4 ring-amber-300/60' : 'border-white'
                                    ]"
                                    :style="`width:${size}px;height:${size}px;font-size:${Math.max(3, Math.round(size * 0.36))}px;`"
                                    :title="label"
                                >
                                    <span x-text="number"></span>
                                </div>

                                <div
                                    class="pointer-events-none absolute left-1/2 top-full z-[90] mt-2 -translate-x-1/2 whitespace-nowrap rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-[#0E2958] opacity-0 shadow-lg transition duration-150 group-hover:opacity-100 group-focus-within:opacity-100"
                                    x-text="label"
                                ></div>
                            </div>
                        @endif
                    @endforeach
                </div>

                {{-- NEW LOCATION DRAG INDICATOR --}}
                <div
                    x-show="draggingNew"
                    x-cloak
                    class="absolute inset-0 z-[80] pointer-events-none flex items-center justify-center"
                >
                    <div class="bg-[#0E2958]/90 text-white px-5 py-3 rounded-xl shadow-xl font-semibold">
                        Release to place <span x-text="draggingName"></span>
                    </div>
                </div>
            </div>

            {{-- MAP ACTIONS --}}
            <div class="flex flex-col gap-3 border-t border-slate-100 bg-slate-50/80 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-gray-500">
                    <span class="font-bold text-[#0E2958]" x-text="positionedCount"></span>
                    / {{ $locations->count() }} locations positioned.
                </p>

                <div class="flex flex-wrap gap-2">
                    <button
                        x-show="editMode"
                        x-cloak
                        type="button"
                        @click="resetPositions()"
                        :disabled="saving"
                        class="px-4 py-2 rounded-lg border border-red-200 bg-white text-red-600 hover:bg-red-50 text-sm font-semibold disabled:opacity-60"
                    >
                        Reset Positions
                    </button>

                    <button
                        x-show="editMode"
                        x-cloak
                        type="button"
                        @click="savePositions()"
                        :disabled="saving"
                        class="px-5 py-2 rounded-lg bg-[#0E2958] hover:bg-[#0B2148] text-white text-sm font-semibold disabled:opacity-60"
                    >
                        <span x-show="!saving">Save All Positions &amp; Sizes</span>
                        <span x-show="saving">Saving...</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- ADMIN BUILDING DETAILS + LOCATION TRAY --}}
        <div class="space-y-5">

            {{-- SELECTED BUILDING DETAILS --}}
            <div x-show="selectedLocation" x-cloak
                 class="h-fit overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm">
                <div class="flex items-start justify-between gap-3 border-b border-slate-100 px-5 py-4">
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-gray-400"
                           x-text="'CAMPUS LOCATION ' + (selectedLocation ? selectedLocation.number : '')"></p>
                        <h2
    class="text-xl font-bold text-gray-900"
    x-text="selectedLocation ? (selectedLocation.building ? selectedLocation.building.name : selectedLocation.name) : ''"
></h2>
                        
<p class="mt-1 text-sm text-gray-500"
   x-text="selectedLocation
       ? (selectedLocation.building
           ? 'Building Code: ' + selectedLocation.building.code
           : (selectedLocation.description || 'Campus location information'))
       : ''"></p>

                    </div>
                    <button type="button" @click="closeLocation()"
                            aria-label="Close building details"
                            class="w-8 h-8 shrink-0 rounded-lg border border-gray-200 hover:bg-gray-50">&times;</button>
                </div>

                <div class="p-5">
                    <template x-if="selectedLocation && selectedLocation.building">
                        <div>
              
                            
<div class="mb-4 rounded-lg border border-slate-200 bg-white p-3">
    <div class="mb-3 flex items-center justify-between gap-2">
        <h3 class="text-sm font-semibold text-gray-900">
            Building Information
        </h3>

        <button
            type="button"
            @click="editingBuilding = !editingBuilding"
            class="rounded-md bg-[#0E2958] px-2.5 py-1.5 text-xs font-semibold text-white transition hover:bg-[#0B2148]"
        >
            <span x-text="editingBuilding ? 'Cancel' : 'Edit Information'"></span>
        </button>
    </div>

    <div x-show="!editingBuilding" class="space-y-2 text-xs">
        <p><span class="font-semibold text-slate-700">Total Floor Area:</span>
            <span x-text="selectedLocation.building.total_floor_area != null && selectedLocation.building.total_floor_area !== '' ? selectedLocation.building.total_floor_area + ' m²' : ''"></span>
        </p>
        <p><span class="font-semibold text-slate-700">Year Constructed:</span>
            <span x-text="selectedLocation.building.year_constructed ?? ''"></span>
        </p>
        <p><span class="font-semibold text-slate-700">Classrooms:</span>
            <span x-text="selectedLocation.building.classroom_count ?? ''"></span>
        </p>
        <p><span class="font-semibold text-slate-700">Laboratories:</span>
            <span x-text="selectedLocation.building.laboratory_count ?? ''"></span>
        </p>
        <p><span class="font-semibold text-slate-700">Offices:</span>
            <span x-text="selectedLocation.building.office_count ?? ''"></span>
        </p>
        <p><span class="font-semibold text-slate-700">Restrooms:</span>
            <span x-text="selectedLocation.building.restroom_count ?? ''"></span>
        </p>
        <p><span class="font-semibold text-slate-700">Function Rooms:</span>
            <span x-text="selectedLocation.building.function_room_count ?? ''"></span>
        </p>
        <p>
            <span class="font-semibold text-slate-700">Description:</span>
            <span x-text="selectedLocation.building.description || ''"></span>
        </p>
    </div>

    <form
        x-show="editingBuilding"
        x-cloak
        :action="`/campus-map/buildings/${selectedLocation.building.id}`"
        method="POST"
        class="space-y-2"
    >
        @csrf
        @method('PATCH')

        <div>
            <label class="mb-1 block text-xs font-semibold text-slate-700">Building Name</label>
            <input
                type="text"
                name="building_name"
                :value="selectedLocation.building.name"
                required
                maxlength="255"
                class="w-full min-w-0 rounded-md border border-slate-300 px-2 py-1.5 text-xs focus:border-[#0E2958] focus:ring-1 focus:ring-[#0E2958]"
            >
        </div>

        <div class="grid grid-cols-2 gap-2">
            <div>
                <label class="mb-1 block text-xs font-semibold text-slate-700">Total Floor Area (m²)</label>
                <input type="number" name="total_floor_area" min="0" step="0.01"
                    :value="selectedLocation.building.total_floor_area ?? ''"
                    class="w-full min-w-0 rounded-md border border-slate-300 px-2 py-1.5 text-xs focus:border-[#0E2958] focus:ring-1 focus:ring-[#0E2958]">
            </div>

            <div>
                <label class="mb-1 block text-xs font-semibold text-slate-700">Year Constructed</label>
                <input type="number" name="year_constructed" min="1000" max="{{ date('Y') + 1 }}"
                    :value="selectedLocation.building.year_constructed ?? ''"
                    class="w-full min-w-0 rounded-md border border-slate-300 px-2 py-1.5 text-xs focus:border-[#0E2958] focus:ring-1 focus:ring-[#0E2958]">
            </div>

            <div>
                <label class="mb-1 block text-xs font-semibold text-slate-700">Classrooms</label>
                <input type="number" name="classroom_count" min="0"
                    :value="selectedLocation.building.classroom_count ?? ''"
                    class="w-full min-w-0 rounded-md border border-slate-300 px-2 py-1.5 text-xs focus:border-[#0E2958] focus:ring-1 focus:ring-[#0E2958]">
            </div>

            <div>
                <label class="mb-1 block text-xs font-semibold text-slate-700">Laboratories</label>
                <input type="number" name="laboratory_count" min="0"
                    :value="selectedLocation.building.laboratory_count ?? ''"
                    class="w-full min-w-0 rounded-md border border-slate-300 px-2 py-1.5 text-xs focus:border-[#0E2958] focus:ring-1 focus:ring-[#0E2958]">
            </div>

            <div>
                <label class="mb-1 block text-xs font-semibold text-slate-700">Offices</label>
                <input type="number" name="office_count" min="0"
                    :value="selectedLocation.building.office_count ?? ''"
                    class="w-full min-w-0 rounded-md border border-slate-300 px-2 py-1.5 text-xs focus:border-[#0E2958] focus:ring-1 focus:ring-[#0E2958]">
            </div>

            <div>
                <label class="mb-1 block text-xs font-semibold text-slate-700">Restrooms</label>
                <input type="number" name="restroom_count" min="0"
                    :value="selectedLocation.building.restroom_count ?? ''"
                    class="w-full min-w-0 rounded-md border border-slate-300 px-2 py-1.5 text-xs focus:border-[#0E2958] focus:ring-1 focus:ring-[#0E2958]">
            </div>

            <div class="col-span-2">
                <label class="mb-1 block text-xs font-semibold text-slate-700">Function Rooms</label>
                <input type="number" name="function_room_count" min="0"
                    :value="selectedLocation.building.function_room_count ?? ''"
                    class="w-full min-w-0 rounded-md border border-slate-300 px-2 py-1.5 text-xs focus:border-[#0E2958] focus:ring-1 focus:ring-[#0E2958]">
            </div>
        </div>

        <div>
            <label class="mb-1 block text-xs font-semibold text-slate-700">Description</label>
            <textarea name="description" rows="3" maxlength="5000"
                x-text="selectedLocation.building.description || ''"
                class="w-full min-w-0 rounded-md border border-slate-300 px-2 py-1.5 text-xs focus:border-[#0E2958] focus:ring-1 focus:ring-[#0E2958]"></textarea>
        </div>

        <button type="submit"
            class="w-full rounded-md bg-[#0E2958] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#0B2148]">
            Save Building Information
        </button>
    </form>
</div>

                            <div class="rounded-xl bg-gray-50 border border-gray-100 p-4">
                                <p class="text-sm text-gray-500">Total rooms</p>
                                <p class="text-3xl font-extrabold text-[#0E2958]"
                                   x-text="selectedLocation.building.rooms.length"></p>
                            </div>

                            <button type="button" @click="showRoomList = !showRoomList"
                                    class="mt-4 w-full px-4 py-3 rounded-lg bg-[#0E2958] text-white font-semibold text-sm hover:bg-[#0B2148]"
                                    x-text="showRoomList ? 'Hide Rooms' : 'View Rooms'"></button>

                            <div x-show="showRoomList" x-cloak class="mt-4 space-y-2 max-h-80 overflow-y-auto">
                                <template x-if="selectedLocation.building.rooms.length === 0">
                                    <p class="text-sm text-gray-500">No rooms are recorded for this building.</p>
                                </template>
                                <template x-for="room in selectedLocation.building.rooms" :key="room.id">
                                    <div class="border border-gray-200 rounded-lg p-3">
                                        <p class="font-semibold text-sm text-[#0E2958]"
                                           x-text="room.number || room.name || ('Room ID ' + room.id)"></p>
                                        <p x-show="room.name && room.number && room.name !== room.number"
                                           class="text-sm text-gray-600 mt-1" x-text="room.name"></p>
                                        <p class="text-xs text-gray-500 mt-1"
                                           x-text="'Floor: ' + (room.floor ?? 'Not specified')"></p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                    


<template x-if="selectedLocation && !selectedLocation.building">
    <div>
        <div class="mb-4 rounded-lg border border-slate-200 bg-white p-3">
            <div class="mb-3 flex items-center justify-between gap-2">
                <h3 class="text-sm font-semibold text-gray-900">
                    Building Information
                </h3>

                <button
                    type="button"
                    @click="editingLocation = !editingLocation"
                    class="rounded-md bg-[#0E2958] px-2.5 py-1.5 text-xs font-semibold text-white transition hover:bg-[#0B2148]"
                >
                    <span x-text="editingLocation ? 'Cancel' : 'Edit Information'"></span>
                </button>
            </div>

            <div x-show="!editingLocation" class="space-y-2 text-xs">
                <p>
                    <span class="font-semibold text-slate-700">Total Floor Area:</span>
                    <span x-text="selectedLocation.total_floor_area != null && selectedLocation.total_floor_area !== '' ? selectedLocation.total_floor_area + ' m²' : ''"></span>
                </p>

                <p>
                    <span class="font-semibold text-slate-700">Year Constructed:</span>
                    <span x-text="selectedLocation.year_constructed ?? ''"></span>
                </p>

                <p>
                    <span class="font-semibold text-slate-700">Classrooms:</span>
                    <span x-text="selectedLocation.classroom_count ?? ''"></span>
                </p>

                <p>
                    <span class="font-semibold text-slate-700">Laboratories:</span>
                    <span x-text="selectedLocation.laboratory_count ?? ''"></span>
                </p>

                <p>
                    <span class="font-semibold text-slate-700">Offices:</span>
                    <span x-text="selectedLocation.office_count ?? ''"></span>
                </p>

                <p>
                    <span class="font-semibold text-slate-700">Restrooms:</span>
                    <span x-text="selectedLocation.restroom_count ?? ''"></span>
                </p>

                <p>
                    <span class="font-semibold text-slate-700">Function Rooms:</span>
                    <span x-text="selectedLocation.function_room_count ?? ''"></span>
                </p>

                <p>
                    <span class="font-semibold text-slate-700">Description:</span>
                    <span x-text="selectedLocation.description || ''"></span>
                </p>
            </div>

            <form
                x-show="editingLocation"
                x-cloak
                :action="`/campus-map/locations/${selectedLocation.id}/update`"
                method="POST"
                class="space-y-2"
            >
                @csrf
                @method('PATCH')

                <div>
                    <label class="mb-1 block text-xs font-semibold text-slate-700">
                        Location Name
                    </label>
                    <input
                        type="text"
                        name="name"
                        :value="selectedLocation.name"
                        required
                        maxlength="255"
                        class="w-full min-w-0 rounded-md border border-slate-300 px-2 py-1.5 text-xs focus:border-[#0E2958] focus:ring-1 focus:ring-[#0E2958]"
                    >
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-700">
                            Total Floor Area (m²)
                        </label>
                        <input type="number" name="total_floor_area" min="0" step="0.01"
                            :value="selectedLocation.total_floor_area ?? ''"
                            class="w-full min-w-0 rounded-md border border-slate-300 px-2 py-1.5 text-xs focus:border-[#0E2958] focus:ring-1 focus:ring-[#0E2958]">
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-700">
                            Year Constructed
                        </label>
                        <input type="number" name="year_constructed" min="1000" max="{{ date('Y') + 1 }}"
                            :value="selectedLocation.year_constructed ?? ''"
                            class="w-full min-w-0 rounded-md border border-slate-300 px-2 py-1.5 text-xs focus:border-[#0E2958] focus:ring-1 focus:ring-[#0E2958]">
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-700">
                            Classrooms
                        </label>
                        <input type="number" name="classroom_count" min="0"
                            :value="selectedLocation.classroom_count ?? ''"
                            class="w-full min-w-0 rounded-md border border-slate-300 px-2 py-1.5 text-xs focus:border-[#0E2958] focus:ring-1 focus:ring-[#0E2958]">
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-700">
                            Laboratories
                        </label>
                        <input type="number" name="laboratory_count" min="0"
                            :value="selectedLocation.laboratory_count ?? ''"
                            class="w-full min-w-0 rounded-md border border-slate-300 px-2 py-1.5 text-xs focus:border-[#0E2958] focus:ring-1 focus:ring-[#0E2958]">
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-700">
                            Offices
                        </label>
                        <input type="number" name="office_count" min="0"
                            :value="selectedLocation.office_count ?? ''"
                            class="w-full min-w-0 rounded-md border border-slate-300 px-2 py-1.5 text-xs focus:border-[#0E2958] focus:ring-1 focus:ring-[#0E2958]">
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-700">
                            Restrooms
                        </label>
                        <input type="number" name="restroom_count" min="0"
                            :value="selectedLocation.restroom_count ?? ''"
                            class="w-full min-w-0 rounded-md border border-slate-300 px-2 py-1.5 text-xs focus:border-[#0E2958] focus:ring-1 focus:ring-[#0E2958]">
                    </div>

                    <div class="col-span-2">
                        <label class="mb-1 block text-xs font-semibold text-slate-700">
                            Function Rooms
                        </label>
                        <input type="number" name="function_room_count" min="0"
                            :value="selectedLocation.function_room_count ?? ''"
                            class="w-full min-w-0 rounded-md border border-slate-300 px-2 py-1.5 text-xs focus:border-[#0E2958] focus:ring-1 focus:ring-[#0E2958]">
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold text-slate-700">
                        Description
                    </label>
                    <textarea
                        name="description"
                        rows="3"
                        maxlength="5000"
                        x-text="selectedLocation.description || ''"
                        class="w-full min-w-0 rounded-md border border-slate-300 px-2 py-1.5 text-xs focus:border-[#0E2958] focus:ring-1 focus:ring-[#0E2958]"
                    ></textarea>
                </div>

                <button type="submit"
                    class="w-full rounded-md bg-[#0E2958] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#0B2148]">
                    Save Location Information
                </button>
            </form>
        </div>
    </div>
</template>




                </div>
            </div>

            {{-- LOCATION TRAY --}}
            <div class="h-fit overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="font-bold text-[#0E2958]">Campus Locations</h2>
                <p class="text-sm text-gray-500 mt-1">
                    Enable Edit Locations to place unpositioned locations onto the map.
                </p>
            </div>

            <div class="max-h-[700px] space-y-2 overflow-y-auto p-3">
                @foreach($locations as $location)
                    <div
                        data-location-tray-id="{{ $location->id }}"
                        @if($location->map_x === null || $location->map_y === null)
                            @pointerdown="if (editMode) startNewMarkerDrag($event, {{ $location->id }}, @js($location->number), @js($location->name))"
                            class="group flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-3 transition duration-150 select-none"
                            :class="editMode
                                ? 'cursor-grab border-slate-300 bg-slate-50/50 shadow-sm hover:-translate-y-px hover:border-sky-200 hover:bg-sky-50/60 hover:shadow-sm active:cursor-grabbing touch-none'
                                : 'cursor-default opacity-75 hover:opacity-100'"
                        @else
                            class="group flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50/40 p-3 transition duration-150 hover:border-emerald-200 hover:bg-emerald-50/70"
                        @endif
                    >
                        <div class="w-7 h-7 rounded-full bg-[#0E2958] text-white flex items-center justify-center font-bold text-[10px] shrink-0">
                            {{ $location->number }}
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="truncate font-semibold text-sm leading-tight text-[#0E2958]" title="{{ $location->name }}">
                                {{ $location->name }}
                            </p>
                            <p class="text-xs text-gray-400 mt-1">
                                {{ $location->type }}
                            </p>
                        </div>

                        <span
                            class="text-[10px] font-bold whitespace-nowrap"
                            :class="isPlaced({{ $location->id }}) ? 'text-green-600' : 'text-gray-400'"
                            x-text="isPlaced({{ $location->id }}) ? 'Placed' : 'Not placed'"
                        ></span>
                    </div>
                @endforeach
            </div>
            </div>{{-- end location tray --}}
        </div>{{-- end details/tray column --}}

    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('campusMapEditor', () => ({
        zoom: 1,
        editMode: false,
        panX: 0,
        panY: 0,
        panning: false,
        saving: false,

        draggingNew: false,
        draggingName: '',
        draggingNewId: null,
        draggingNewNumber: null,

        selectedMarkerId: null,
        selectedMarkerName: '',
        selectedMarkerSize: 28,

        startMouseX: 0,
        startMouseY: 0,
        startPanX: 0,
        startPanY: 0,

        message: '',
        messageType: 'success',
        newMarkerMoveHandler: null,
        newMarkerUpHandler: null,
        buildings: [],
        selectedLocation: null,
        showRoomList: false,
        editingBuilding: false,
        editingLocation: false,
        // Verified marker-to-building mappings only. Marker 21 is ambiguous (Green Home I/II).
        buildingMap: {
            1: 'B01', 2: 'B02', 3: 'B03', 4: 'B04', 5: 'B05',
            6: 'B06', 7: 'B07', 8: 'B08', 9: 'B09', 10: 'B10',
            11: 'B11', 12: 'B12', 13: 'B17', 15: 'B19',
            17: 'B14', 18: 'B13', 25: 'B18',
        },

        placedIds: @js($locations
            ->filter(fn ($location) => $location->map_x !== null && $location->map_y !== null)
            ->pluck('id')
            ->values()),

        init() {
            this.newMarkerMoveHandler = event => this.moveNewMarker(event);
            this.newMarkerUpHandler = event => this.dropNewMarker(event);
            try {
                this.buildings = JSON.parse(this.$el.dataset.buildings || '[]');
            } catch (error) {
                console.error('Unable to load campus building data:', error);
                this.buildings = [];
            }
        },

        
openLocation(number, name) {
    if (this.editMode) return;

    
const locations = @js($locations->map(function ($location) {
    return [
        'id' => $location->id,
        'number' => $location->number,
        'name' => $location->name,
        'description' => $location->description ?? '',
        'building_id' => $location->building_id,

        'total_floor_area' => $location->total_floor_area,
        'year_constructed' => $location->year_constructed,
        'classroom_count' => $location->classroom_count,
        'laboratory_count' => $location->laboratory_count,
        'office_count' => $location->office_count,
        'restroom_count' => $location->restroom_count,
        'function_room_count' => $location->function_room_count,
    ];
})->values());


    const matchedLocation = locations.find(
        item => Number(item.number) === Number(number)
    );

    // Prefer the actual database relationship.
    let building = matchedLocation?.building_id
        ? this.buildings.find(
            item => Number(item.id) === Number(matchedLocation.building_id)
        )
        : null;

    // Preserve the legacy mapping fallback.
    if (!building) {
        const code = this.buildingMap[Number(number)];
        building = code
            ? this.buildings.find(item => item.code === code)
            : null;
    }

    
this.selectedLocation = {
    id: matchedLocation?.id ?? null,
    number: Number(number),
    name: matchedLocation?.name ?? name,
    description: matchedLocation?.description ?? '',
    building: building || null,

    total_floor_area: matchedLocation?.total_floor_area ?? null,
    year_constructed: matchedLocation?.year_constructed ?? null,
    classroom_count: matchedLocation?.classroom_count ?? null,
    laboratory_count: matchedLocation?.laboratory_count ?? null,
    office_count: matchedLocation?.office_count ?? null,
    restroom_count: matchedLocation?.restroom_count ?? null,
    function_room_count: matchedLocation?.function_room_count ?? null
};


    this.showRoomList = false;
    this.editingBuilding = false;
    this.editingLocation = false;
},


        get positionedCount() {
            return this.placedIds.length;
        },

        showMessage(message, type = 'success') {
            this.message = message;
            this.messageType = type;
        },

        markers() {
            return Array.from(
                this.$refs.canvas.querySelectorAll('[data-campus-marker]')
            );
        },

        markerData(id) {
            const node = this.markers().find(node => {
                return Number(Alpine.$data(node).id) === Number(id);
            });

            return node ? Alpine.$data(node) : null;
        },

        isPlaced(id) {
            return this.placedIds.includes(Number(id));
        },

        toggleEditMode() {
            this.editMode = !this.editMode;
            this.panning = false;

            if (!this.editMode) {
                this.draggingNew = false;
                this.draggingNewId = null;
                this.draggingNewNumber = null;
                this.draggingName = '';

                this.deselectMarker();

                document.removeEventListener(
                    'pointermove',
                    this.newMarkerMoveHandler
                );
                document.removeEventListener(
                    'pointerup',
                    this.newMarkerUpHandler
                );
                document.removeEventListener(
                    'pointercancel',
                    this.newMarkerUpHandler
                );

                this.markers().forEach(node => {
                    Alpine.$data(node).stopMarkerDrag();
                });
            }

            this.showMessage(
                this.editMode
                    ? 'Edit Mode enabled. Select a marker to resize it, drag markers, or place locations.'
                    : 'Edit Mode disabled. Markers are locked.',
                'success'
            );
        },

        selectMarker(id, name, size) {
            if (!this.editMode) return;

            this.selectedMarkerId = Number(id);
            this.selectedMarkerName = name;
            this.selectedMarkerSize = size;

            this.markers().forEach(node => {
                const marker = Alpine.$data(node);
                marker.selected = Number(marker.id) === Number(id);
            });
        },

        deselectMarker() {
            this.selectedMarkerId = null;
            this.selectedMarkerName = '';
            this.selectedMarkerSize = 28;

            this.markers().forEach(node => {
                Alpine.$data(node).selected = false;
            });
        },

        resizeSelected(delta) {
            if (
                !this.editMode ||
                this.saving ||
                this.selectedMarkerId === null
            ) {
                return;
            }

            const marker = this.markerData(this.selectedMarkerId);
            if (!marker) return;

            marker.size = Math.max(
                4,
                Math.min(64, marker.size + delta)
            );

            this.selectedMarkerSize = marker.size;
        },

        handleWheelZoom(event) {
            const step = event.deltaY < 0 ? 0.1 : -0.1;

            this.zoom = Math.max(
                0.5,
                Math.min(2.5, Number((this.zoom + step).toFixed(2)))
            );
        },

        zoomIn() {
            this.zoom = Math.min(this.zoom + 0.25, 2.5);
        },

        zoomOut() {
            this.zoom = Math.max(this.zoom - 0.25, 0.5);
        },

        resetView() {
            this.zoom = 1;
            this.panX = 0;
            this.panY = 0;
        },

        startPan(event) {
            if (
                this.draggingNew ||
                event.button !== 0 ||
                event.target.closest('[data-campus-marker]')
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
            if (!this.panning || this.draggingNew) return;

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

        startNewMarkerDrag(event, id, number, name) {
            if (!this.editMode || event.button !== 0 || this.saving) {
                return;
            }

            event.preventDefault();

            this.draggingNew = true;
            this.draggingNewId = id;
            this.draggingNewNumber = number;
            this.draggingName = name;

            document.addEventListener(
                'pointermove',
                this.newMarkerMoveHandler
            );

            document.addEventListener(
                'pointerup',
                this.newMarkerUpHandler,
                { once: true }
            );

            document.addEventListener(
                'pointercancel',
                this.newMarkerUpHandler,
                { once: true }
            );
        },

        moveNewMarker() {},

        async dropNewMarker(event) {
            if (!this.draggingNew) return;

            const id = this.draggingNewId;
            const number = this.draggingNewNumber;
            const name = this.draggingName;
            const rect = this.$refs.canvas.getBoundingClientRect();

            const inside =
                event.clientX >= rect.left &&
                event.clientX <= rect.right &&
                event.clientY >= rect.top &&
                event.clientY <= rect.bottom;

            this.draggingNew = false;
            this.draggingNewId = null;
            this.draggingNewNumber = null;
            this.draggingName = '';

            document.removeEventListener(
                'pointermove',
                this.newMarkerMoveHandler
            );
            document.removeEventListener(
                'pointerup',
                this.newMarkerUpHandler
            );
            document.removeEventListener(
                'pointercancel',
                this.newMarkerUpHandler
            );

            if (!inside || rect.width <= 0 || rect.height <= 0) {
                return;
            }

            const position = {
                id: id,
                map_x: Math.max(
                    0,
                    Math.min(
                        100,
                        ((event.clientX - rect.left) / rect.width) * 100
                    )
                ),
                map_y: Math.max(
                    0,
                    Math.min(
                        100,
                        ((event.clientY - rect.top) / rect.height) * 100
                    )
                ),
                marker_size: 28
            };

            const success = await this.sendPositions(
                [position],
                `Placed ${name}.`
            );

            if (!success) return;

            if (!this.isPlaced(id)) {
                this.placedIds = [...this.placedIds, Number(id)];
            }

            const node = document.createElement('div');

            node.setAttribute('data-campus-marker', '');

            node.setAttribute(
                'x-data',
                `campusMarker(${Number(id)}, ${Number(number)}, ${position.map_x}, ${position.map_y}, 28, ${JSON.stringify(name)})`
            );

            node.className = 'group absolute z-50';

            node.setAttribute(
                ':style',
                '`left:${x}%;top:${y}%;transform:translate(-50%, -50%)`'
            );

            const markerCircle = document.createElement('div');

            markerCircle.className =
                'rounded-full bg-[#0E4C6B] border-2 border-white shadow-lg flex items-center justify-center text-white font-extrabold transition';

            markerCircle.style.width = '28px';
            markerCircle.style.height = '28px';
            markerCircle.style.fontSize = '10px';
            markerCircle.textContent = number;

            markerCircle.setAttribute(
                ':class',
                "[editMode ? 'cursor-grab active:cursor-grabbing' : 'cursor-default', selected ? 'border-amber-300 ring-4 ring-amber-300/60' : 'border-white']"
            );

            markerCircle.setAttribute(
                ':style',
                '`width:${size}px;height:${size}px;font-size:${Math.max(3, Math.round(size * 0.36))}px;`'
            );

            const label = document.createElement('div');

            label.className =
                'pointer-events-none absolute left-1/2 top-full z-[90] mt-2 -translate-x-1/2 whitespace-nowrap rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-[#0E2958] opacity-0 shadow-lg transition duration-150 group-hover:opacity-100 group-focus-within:opacity-100';

            label.textContent = name;

            node.appendChild(markerCircle);
            node.appendChild(label);

            this.$refs.canvas.appendChild(node);
            Alpine.initTree(node);

            node.addEventListener('pointerdown', event => {
                event.stopPropagation();

                const marker = Alpine.$data(node);
                if (marker) {
                    marker.startMarkerDrag(event);
                }
            });

            node.addEventListener('click', event => {
                event.stopPropagation();

                const marker = Alpine.$data(node);
                if (marker) {
                    marker.handleClick();
                }
            });

            const trayItem = this.$root.querySelector(
                `[data-location-tray-id="${id}"]`
            );

            if (trayItem) {
                trayItem.remove();
            }

            this.showMessage(
                `${name} placed. Continue editing, then save all positions and sizes.`,
                'success'
            );
        },

        async sendPositions(positions, successMessage) {
            this.saving = true;
            this.message = '';

            try {
                const response = await fetch(
                    @js(route('admin.campus-map.positions')),
                    {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': @js(csrf_token())
                        },
                        body: JSON.stringify({ positions })
                    }
                );

                const result = await response.json().catch(() => ({}));

                if (!response.ok) {
                    const errors = result.errors
                        ? Object.values(result.errors).flat().join(' ')
                        : '';

                    throw new Error(
                        errors ||
                        result.message ||
                        `Saving failed (HTTP ${response.status}).`
                    );
                }

                this.showMessage(
                    result.message || successMessage,
                    'success'
                );

                return true;
            } catch (error) {
                console.error('Campus map save error:', error);

                this.showMessage(
                    error.message || 'Unable to save map positions.',
                    'error'
                );

                return false;
            } finally {
                this.saving = false;
            }
        },

        async savePositions() {
            if (!this.editMode || this.saving) return;

            const positions = this.markers().map(node => {
                const marker = Alpine.$data(node);

                return {
                    id: marker.id,
                    map_x: Number(marker.x.toFixed(4)),
                    map_y: Number(marker.y.toFixed(4)),
                    marker_size: marker.size
                };
            });

            if (positions.length === 0) {
                this.showMessage(
                    'There are no positioned markers to save.',
                    'error'
                );
                return;
            }

            await this.sendPositions(
                positions,
                'Campus map positions and sizes saved successfully.'
            );
        },

        async removeSelectedMarker() {
            if (!this.editMode || this.saving || this.selectedMarkerId === null) return;

            const markerId = Number(this.selectedMarkerId);
            const markerName = this.selectedMarkerName || 'this location';
            const confirmed = confirm(
                `Remove "${markerName}" from the map? The campus location record will be kept, and you can place it again later.`
            );

            if (!confirmed) return;

            this.saving = true;
            this.message = '';

            try {
                const response = await fetch(
                    @js(route('admin.campus-map.remove-position', ['location' => '__LOCATION_ID__'])).replace('__LOCATION_ID__', markerId),
                    {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': @js(csrf_token())
                        }
                    }
                );

                const result = await response.json().catch(() => ({}));

                if (!response.ok) {
                    throw new Error(
                        result.message || `Unable to remove marker (HTTP ${response.status}).`
                    );
                }

                window.location.reload();
            } catch (error) {
                console.error('Campus map remove marker error:', error);
                this.showMessage(
                    error.message || 'Unable to remove this marker from the map.',
                    'error'
                );
            } finally {
                this.saving = false;
            }
        },

        async resetPositions() {
            if (!this.editMode || this.saving) return;

            const confirmed = confirm(
                'This will remove every saved marker position from the database. Continue?'
            );

            if (!confirmed) return;

            this.saving = true;
            this.message = '';

            try {
                const response = await fetch(
                    @js(route('admin.campus-map.reset')),
                    {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': @js(csrf_token())
                        }
                    }
                );

                const result = await response.json().catch(() => ({}));

                if (!response.ok) {
                    throw new Error(
                        result.message ||
                        `Reset failed (HTTP ${response.status}).`
                    );
                }

                window.location.reload();
            } catch (error) {
                console.error('Campus map reset error:', error);

                this.showMessage(
                    error.message || 'Unable to reset map positions.',
                    'error'
                );
            } finally {
                this.saving = false;
            }
        }
    }));

    Alpine.data(
        'campusMarker',
        (id, number, initialX, initialY, initialSize = 28, label = '') => ({
            id: Number(id),
            number: Number(number),
            x: Number(initialX),
            y: Number(initialY),
            size: Number(initialSize),
            label: label,

            selected: false,
            dragging: false,

            startMouseX: 0,
            startMouseY: 0,
            startX: 0,
            startY: 0,

            moveHandler: null,
            upHandler: null,

            init() {
                this.moveHandler = event => this.moveMarker(event);
                this.upHandler = () => this.stopMarkerDrag();
            },

            editor() {
                let element = this.$root.parentElement;

                while (element) {
                    if (
                        element.hasAttribute('x-data') &&
                        element.getAttribute('x-data').startsWith('campusMapEditor')
                    ) {
                        return Alpine.$data(element);
                    }

                    element = element.parentElement;
                }

                return null;
            },

            selectMarker() {
                const editor = this.editor();

                if (!editor || !editor.editMode) return;

                editor.selectMarker(
                    this.id,
                    this.label,
                    this.size
                );
            },

            handleClick() {
                const editor = this.editor();
                if (!editor) return;
                if (editor.editMode) {
                    this.selectMarker();
                } else {
                    editor.openLocation(this.number, this.label);
                }
            },

            startMarkerDrag(event) {
                const editor = this.editor();

                if (
                    !editor ||
                    !editor.editMode ||
                    event.button !== 0 ||
                    editor.saving
                ) {
                    return;
                }

                event.preventDefault();
                event.stopPropagation();

                this.selectMarker();

                this.dragging = true;

                this.startMouseX = event.clientX;
                this.startMouseY = event.clientY;

                this.startX = this.x;
                this.startY = this.y;

                document.removeEventListener(
                    'pointermove',
                    this.moveHandler
                );
                document.removeEventListener(
                    'pointerup',
                    this.upHandler
                );
                document.removeEventListener(
                    'pointercancel',
                    this.upHandler
                );

                document.addEventListener(
                    'pointermove',
                    this.moveHandler
                );

                document.addEventListener(
                    'pointerup',
                    this.upHandler,
                    { once: true }
                );

                document.addEventListener(
                    'pointercancel',
                    this.upHandler,
                    { once: true }
                );
            },

            moveMarker(event) {
                if (!this.dragging) return;

                const editor = this.editor();

                if (!editor || !editor.editMode) {
                    this.stopMarkerDrag();
                    return;
                }

                const canvas = editor.$refs.canvas;
                const rect = canvas.getBoundingClientRect();

                if (rect.width <= 0 || rect.height <= 0) return;

                this.x = Math.max(
                    0,
                    Math.min(
                        100,
                        this.startX +
                        ((event.clientX - this.startMouseX) / rect.width) * 100
                    )
                );

                this.y = Math.max(
                    0,
                    Math.min(
                        100,
                        this.startY +
                        ((event.clientY - this.startMouseY) / rect.height) * 100
                    )
                );
            },

            stopMarkerDrag() {
                this.dragging = false;

                document.removeEventListener(
                    'pointermove',
                    this.moveHandler
                );

                document.removeEventListener(
                    'pointerup',
                    this.upHandler
                );

                document.removeEventListener(
                    'pointercancel',
                    this.upHandler
                );
            }
        })
    );
});
</script>
@endsection
