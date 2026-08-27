@extends('layouts.visitor')

@section('title', 'Campus Navigation')

@section('content')

<div class="max-w-[1500px] mx-auto">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="mb-6">

        <div class="flex items-center gap-3 mb-3">

            <div
                class="w-11 h-11
                       rounded-xl
                       bg-[#0E4C6B]/10
                       flex items-center
                       justify-center">

                <x-heroicon-o-map
                    class="w-6 h-6 text-[#0E4C6B]"
                />

            </div>

            <span
                class="text-sm
                       font-semibold
                       text-[#0E4C6B]
                       bg-[#0E4C6B]/10
                       px-3 py-1
                       rounded-full">

                Visitor Access

            </span>

        </div>


        <h1
            class="text-3xl sm:text-4xl
                   font-extrabold
                   text-[#0E2958]">

            Campus Navigation

        </h1>


        <p
            class="text-gray-500
                   mt-2
                   max-w-3xl">

            Find buildings, facilities, and destinations around
            Urdaneta City University using the interactive campus map.

        </p>

    </div>


    {{-- ========================================================= --}}
    {{-- START / DESTINATION --}}
    {{-- ========================================================= --}}

    <div
        class="bg-white
               border border-gray-200
               rounded-2xl
               shadow-sm
               p-5
               mb-5">

        <div
            class="grid grid-cols-1
                   lg:grid-cols-[1fr_auto_1fr_auto]
                   gap-4
                   items-end">


            {{-- START --}}

            <div>

                <label
                    for="startLocation"
                    class="block
                           text-xs
                           font-bold
                           text-gray-600
                           mb-2">

                    Start Location

                </label>


                <div class="relative">

                    <span
                        class="absolute
                               left-3
                               top-1/2
                               -translate-y-1/2
                               w-7
                               h-7
                               rounded-full
                               bg-green-100
                               text-green-700
                               flex
                               items-center
                               justify-center
                               text-xs
                               font-extrabold">

                        A

                    </span>


                    <select
                        id="startLocation"
                        class="w-full
                               pl-12
                               pr-4
                               py-3
                               rounded-xl
                               border
                               border-gray-200
                               bg-white
                               text-sm
                               text-gray-700
                               outline-none
                               focus:border-[#0E4C6B]
                               focus:ring-2
                               focus:ring-[#0E4C6B]/10">

                        <option value="">

                            Select starting location

                        </option>

                    </select>

                </div>

            </div>


            {{-- SWAP --}}

            <button
                type="button"
                id="swapLocations"
                class="w-10
                       h-10
                       rounded-xl
                       border
                       border-gray-200
                       bg-white
                       hover:bg-gray-50
                       text-gray-500
                       flex
                       items-center
                       justify-center
                       transition"
                title="Swap locations">

                <x-heroicon-o-arrows-right-left
                    class="w-4 h-4"
                />

            </button>


            {{-- DESTINATION --}}

            <div>

                <label
                    for="endLocation"
                    class="block
                           text-xs
                           font-bold
                           text-gray-600
                           mb-2">

                    Destination

                </label>


                <div class="relative">

                    <span
                        class="absolute
                               left-3
                               top-1/2
                               -translate-y-1/2
                               w-7
                               h-7
                               rounded-full
                               bg-red-100
                               text-red-600
                               flex
                               items-center
                               justify-center
                               text-xs
                               font-extrabold">

                        B

                    </span>


                    <select
                        id="endLocation"
                        class="w-full
                               pl-12
                               pr-4
                               py-3
                               rounded-xl
                               border
                               border-gray-200
                               bg-white
                               text-sm
                               text-gray-700
                               outline-none
                               focus:border-[#0E4C6B]
                               focus:ring-2
                               focus:ring-[#0E4C6B]/10">

                        <option value="">

                            Select destination

                        </option>

                    </select>

                </div>

            </div>


            {{-- GET DIRECTIONS --}}

            <button
                type="button"
                id="getDirections"
                class="h-12
                       px-6
                       rounded-xl
                       bg-[#0E4C6B]
                       hover:bg-[#0B3D56]
                       text-white
                       font-bold
                       text-sm
                       flex
                       items-center
                       justify-center
                       gap-2
                       transition
                       shadow-sm">

                <x-heroicon-o-map
                    class="w-5 h-5"
                />

                Get Directions

            </button>

        </div>


        {{-- ROUTE MESSAGE --}}

        <div
            id="routeMessage"
            class="hidden
                   mt-4
                   rounded-xl
                   bg-[#0E4C6B]/5
                   border
                   border-[#0E4C6B]/10
                   px-4
                   py-3">

            <div class="flex items-center gap-3">

                <div
                    class="w-8 h-8
                           rounded-lg
                           bg-white
                           flex
                           items-center
                           justify-center">

                    <x-heroicon-o-map
                        class="w-4 h-4 text-[#0E4C6B]"
                    />

                </div>


                <div>

                    <p
                        id="routeTitle"
                        class="text-sm
                               font-bold
                               text-[#0E2958]">

                    </p>

                    <p
                        id="routeDescription"
                        class="text-xs
                               text-gray-500
                               mt-0.5">

                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SEARCH --}}
    {{-- ========================================================= --}}

    <div
        class="bg-white
               border border-gray-200
               rounded-2xl
               shadow-sm
               p-4
               mb-5">

        <div class="relative">

            <x-heroicon-o-magnifying-glass
                class="absolute
                       left-4
                       top-1/2
                       -translate-y-1/2
                       w-5
                       h-5
                       text-gray-400"
            />


            <input
                id="locationSearch"
                type="text"
                autocomplete="off"
                placeholder="Search building or facility..."
                class="w-full
                       pl-12
                       pr-4
                       py-3.5
                       rounded-xl
                       border
                       border-gray-200
                       text-sm
                       outline-none
                       focus:border-[#0E4C6B]
                       focus:ring-2
                       focus:ring-[#0E4C6B]/10"
            >

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- MAIN --}}
    {{-- ========================================================= --}}

    <div
        class="grid grid-cols-1
               xl:grid-cols-[minmax(0,1fr)_350px]
               gap-5">


        {{-- ===================================================== --}}
        {{-- MAP CARD --}}
        {{-- ===================================================== --}}

        <div
            class="bg-white
                   border border-gray-200
                   rounded-2xl
                   shadow-sm
                   overflow-hidden">


            {{-- ================================================= --}}
            {{-- MAP HEADER --}}
            {{-- ================================================= --}}

            <div
                class="px-5
                       py-4
                       border-b
                       border-gray-200
                       flex
                       flex-col
                       sm:flex-row
                       sm:items-center
                       sm:justify-between
                       gap-3">

                <div>

                    <h2
                        class="font-bold
                               text-[#0E2958]">

                        UCU Campus Map

                    </h2>

                    <p
                        class="text-xs
                               text-gray-500
                               mt-1">

                        Site Development Plan

                    </p>

                </div>


                {{-- MAP CONTROLS --}}

                <div
                    class="flex
                           items-center
                           gap-2
                           flex-wrap">


                    <button
                        type="button"
                        id="zoomIn"
                        class="map-control"
                        title="Zoom in">

                        <x-heroicon-o-plus
                            class="w-4 h-4"
                        />

                    </button>


                    <button
                        type="button"
                        id="zoomOut"
                        class="map-control"
                        title="Zoom out">

                        <x-heroicon-o-minus
                            class="w-4 h-4"
                        />

                    </button>


                    <button
                        type="button"
                        id="rotateLeft"
                        class="map-control"
                        title="Rotate left">

                        ↶

                    </button>


                    <button
                        type="button"
                        id="rotateRight"
                        class="map-control"
                        title="Rotate right">

                        ↷

                    </button>


                    <button
                        type="button"
                        id="resetMap"
                        class="px-3
                               h-9
                               rounded-lg
                               border
                               border-gray-200
                               hover:bg-gray-50
                               text-xs
                               font-semibold
                               text-gray-600">

                        Reset

                    </button>


                    <button
                        type="button"
                        id="fullscreenMap"
                        class="map-control"
                        title="Fullscreen">

                        <x-heroicon-o-arrows-pointing-out
                            class="w-4 h-4"
                        />

                    </button>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- MAP VIEWPORT --}}
            {{-- ================================================= --}}

            <div
                id="mapViewport"
                class="relative
                       h-[600px]
                       sm:h-[680px]
                       lg:h-[760px]
                       bg-[#F3F5F6]
                       overflow-hidden
                       cursor-grab
                       select-none">


                {{-- ================================================= --}}
                {{-- MAP CANVAS --}}
                {{-- ================================================= --}}

                <div
                    id="mapCanvas"
                    class="absolute
                           left-1/2
                           top-1/2
                           origin-center
                           will-change-transform">


                    {{-- ================================================= --}}
                    {{-- YOUR PHOTOSHOP MAP --}}
                    {{-- ================================================= --}}

                    <div
                        id="mapImageWrapper"
                        class="relative
                               w-[950px]
                               max-w-none">

                        <img
                            id="campusMap"
                            src="{{ asset('images/ucu-campus-map.png') }}"
                            alt="Urdaneta City University Campus Map"
                            draggable="false"
                            class="block
                                   w-full
                                   h-auto
                                   pointer-events-none
                                   select-none"
                        />


                        {{-- ================================================= --}}
                        {{-- ROUTE SVG --}}
                        {{-- ================================================= --}}

                        <svg
                            id="routeLayer"
                            class="absolute
                                   inset-0
                                   w-full
                                   h-full
                                   pointer-events-none
                                   overflow-visible
                                   hidden"
                            viewBox="0 0 1000 1000"
                            preserveAspectRatio="none">

                            <defs>

                                <filter
                                    id="routeShadow"
                                    x="-50%"
                                    y="-50%"
                                    width="200%"
                                    height="200%">

                                    <feDropShadow
                                        dx="0"
                                        dy="2"
                                        stdDeviation="2"
                                        flood-opacity=".25"
                                    />

                                </filter>

                            </defs>


                            <path
                                id="routePath"
                                d=""
                                fill="none"
                                stroke="#0E4C6B"
                                stroke-width="8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-dasharray="14 10"
                                filter="url(#routeShadow)"
                            />

                        </svg>


                        {{-- ================================================= --}}
                        {{-- START MARKER --}}
                        {{-- ================================================= --}}

                        <div
                            id="startMarker"
                            class="route-marker
                                   start-marker
                                   hidden">

                            A

                        </div>


                        {{-- ================================================= --}}
                        {{-- END MARKER --}}
                        {{-- ================================================= --}}

                        <div
                            id="endMarker"
                            class="route-marker
                                   end-marker
                                   hidden">

                            B

                        </div>


                        {{-- ================================================= --}}
                        {{-- LOCATION MARKERS --}}
                        {{-- ================================================= --}}

                        @php

                            /*
                            |--------------------------------------------------------------------------
                            | These coordinates follow the numbered locations
                            | shown in the official site development plan.
                            |
                            | x = horizontal percentage
                            | y = vertical percentage
                            |
                            | They are intentionally kept separate from the
                            | database so we can later move them without
                            | touching the buildings table.
                            |--------------------------------------------------------------------------
                            */

                            $markers = [

                                1 => [
                                    'name' => 'DR. LEONCIO ANCHETA BUILDING I',
                                    'x' => 61.0,
                                    'y' => 10.0,
                                    'type' => 'academic',
                                ],

                                2 => [
                                    'name' => 'DR. LEONCIO ANCHETA BUILDING II',
                                    'x' => 69.0,
                                    'y' => 12.0,
                                    'type' => 'academic',
                                ],

                                3 => [
                                    'name' => 'HONASAN HALL',
                                    'x' => 65.0,
                                    'y' => 15.5,
                                    'type' => 'academic',
                                ],

                                4 => [
                                    'name' => 'BADAR BUILDING',
                                    'x' => 57.5,
                                    'y' => 16.0,
                                    'type' => 'academic',
                                ],

                                5 => [
                                    'name' => 'DR. SOLEDAD F. CARINGAL BUILDING',
                                    'x' => 60.0,
                                    'y' => 21.0,
                                    'type' => 'academic',
                                ],

                                6 => [
                                    'name' => 'NURSING BUILDING I',
                                    'x' => 72.0,
                                    'y' => 27.0,
                                    'type' => 'academic',
                                ],

                                7 => [
                                    'name' => 'NURSING BUILDING II',
                                    'x' => 70.0,
                                    'y' => 31.0,
                                    'type' => 'academic',
                                ],

                                8 => [
                                    'name' => "RESORT'S WORLD BUILDING",
                                    'x' => 59.0,
                                    'y' => 31.0,
                                    'type' => 'academic',
                                ],

                                9 => [
                                    'name' => 'DR. TEOFIDEZ E. CALVERO BUILDING',
                                    'x' => 50.0,
                                    'y' => 29.0,
                                    'type' => 'academic',
                                ],

                                10 => [
                                    'name' => 'DR. PEDRO T. ORATA BUILDING I',
                                    'x' => 40.0,
                                    'y' => 19.0,
                                    'type' => 'academic',
                                ],

                                11 => [
                                    'name' => 'HON. JULIO E. PARAYNO BUILDING',
                                    'x' => 34.0,
                                    'y' => 21.0,
                                    'type' => 'academic',
                                ],

                                12 => [
                                    'name' => 'DR. PEDRO T. ORATA BUILDING II',
                                    'x' => 42.0,
                                    'y' => 43.0,
                                    'type' => 'academic',
                                ],

                                13 => [
                                    'name' => 'GYMNASIUM',
                                    'x' => 51.0,
                                    'y' => 68.0,
                                    'type' => 'recreation',
                                ],

                                14 => [
                                    'name' => 'SEWER TREATMENT PLANT',
                                    'x' => 44.0,
                                    'y' => 84.0,
                                    'type' => 'facility',
                                ],

                                15 => [
                                    'name' => 'MINI GYMNASIUM',
                                    'x' => 43.0,
                                    'y' => 72.0,
                                    'type' => 'recreation',
                                ],

                                16 => [
                                    'name' => 'P.E. OFFICE',
                                    'x' => 52.0,
                                    'y' => 77.0,
                                    'type' => 'facility',
                                ],

                                17 => [
                                    'name' => 'FITNESS GYM II',
                                    'x' => 39.0,
                                    'y' => 62.0,
                                    'type' => 'recreation',
                                ],

                                18 => [
                                    'name' => 'FITNESS GYM I',
                                    'x' => 40.5,
                                    'y' => 58.0,
                                    'type' => 'recreation',
                                ],

                                19 => [
                                    'name' => 'WELLNESS SPA',
                                    'x' => 37.0,
                                    'y' => 56.0,
                                    'type' => 'facility',
                                ],

                                20 => [
                                    'name' => 'E.M.A.S.',
                                    'x' => 38.0,
                                    'y' => 59.0,
                                    'type' => 'facility',
                                ],

                                21 => [
                                    'name' => 'GREEN HOME',
                                    'x' => 33.0,
                                    'y' => 34.0,
                                    'type' => 'facility',
                                ],

                                22 => [
                                    'name' => 'GENERATOR SET',
                                    'x' => 30.0,
                                    'y' => 28.0,
                                    'type' => 'facility',
                                ],

                                23 => [
                                    'name' => 'ANIMAL CLINIC',
                                    'x' => 47.0,
                                    'y' => 34.0,
                                    'type' => 'facility',
                                ],

                                24 => [
                                    'name' => 'UNIVERSITY CLINIC',
                                    'x' => 46.0,
                                    'y' => 27.0,
                                    'type' => 'facility',
                                ],

                                25 => [
                                    'name' => 'MOCK HOTEL',
                                    'x' => 63.0,
                                    'y' => 26.0,
                                    'type' => 'academic',
                                ],

                                26 => [
                                    'name' => 'AUDIO VISUAL ROOM',
                                    'x' => 68.0,
                                    'y' => 26.0,
                                    'type' => 'academic',
                                ],

                                27 => [
                                    'name' => 'DR. ORATA PARK',
                                    'x' => 60.0,
                                    'y' => 28.0,
                                    'type' => 'facility',
                                ],

                                28 => [
                                    'name' => 'QUADRANGLE',
                                    'x' => 55.0,
                                    'y' => 27.0,
                                    'type' => 'facility',
                                ],

                                29 => [
                                    'name' => 'SQUARE GARDEN',
                                    'x' => 54.0,
                                    'y' => 16.0,
                                    'type' => 'facility',
                                ],

                                30 => [
                                    'name' => 'NDRRMO',
                                    'x' => 72.0,
                                    'y' => 13.0,
                                    'type' => 'facility',
                                ],

                                31 => [
                                    'name' => 'AIRPLANE BUILDING',
                                    'x' => 77.0,
                                    'y' => 14.0,
                                    'type' => 'academic',
                                ],

                                32 => [
                                    'name' => 'TURNSTILE',
                                    'x' => 80.0,
                                    'y' => 15.0,
                                    'type' => 'facility',
                                ],

                                33 => [
                                    'name' => 'ENTREP. & LAW BUILDING',
                                    'x' => 48.0,
                                    'y' => 10.0,
                                    'type' => 'academic',
                                ],

                                34 => [
                                    'name' => 'TOILET',
                                    'x' => 57.0,
                                    'y' => 78.0,
                                    'type' => 'facility',
                                ],

                                35 => [
                                    'name' => 'PARKING',
                                    'x' => 55.0,
                                    'y' => 44.0,
                                    'type' => 'facility',
                                ],

                            ];

                        @endphp


                        @foreach($markers as $number => $marker)

                            <button
                                type="button"
                                class="campus-marker
                                       marker-{{ $marker['type'] }}"
                                data-id="{{ $number }}"
                                data-name="{{ $marker['name'] }}"
                                data-x="{{ $marker['x'] }}"
                                data-y="{{ $marker['y'] }}"
                                style="
                                    left: {{ $marker['x'] }}%;
                                    top: {{ $marker['y'] }}%;
                                "
                                title="{{ $marker['name'] }}">

                                {{ $number }}

                            </button>

                        @endforeach


                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- MAP HELP --}}
                {{-- ================================================= --}}

                <div
                    class="absolute
                           left-4
                           top-4
                           z-30
                           bg-white/95
                           backdrop-blur
                           border
                           border-gray-200
                           rounded-xl
                           shadow-sm
                           px-4
                           py-3">

                    <div
                        class="flex
                               items-center
                               gap-2">

                        <x-heroicon-o-hand-raised
                            class="w-4 h-4 text-[#0E4C6B]"
                        />

                        <span
                            class="text-xs
                                   font-semibold
                                   text-gray-600">

                            Drag to move • Scroll to zoom

                        </span>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- MAP LEGEND --}}
                {{-- ================================================= --}}

                <div
                    class="absolute
                           left-4
                           bottom-4
                           z-30
                           bg-white/95
                           backdrop-blur
                           border
                           border-gray-200
                           rounded-xl
                           shadow-sm
                           px-4
                           py-3">

                    <div
                        class="flex
                               items-center
                               gap-4
                               text-[11px]">

                        <div class="flex items-center gap-1.5">

                            <span
                                class="legend-dot
                                       academic-dot">
                            </span>

                            Academic

                        </div>


                        <div class="flex items-center gap-1.5">

                            <span
                                class="legend-dot
                                       recreation-dot">
                            </span>

                            Recreation

                        </div>


                        <div class="flex items-center gap-1.5">

                            <span
                                class="legend-dot
                                       facility-dot">
                            </span>

                            Facility

                        </div>

                    </div>

                </div>


            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- RIGHT LEGEND / LOCATIONS --}}
        {{-- ===================================================== --}}

        <div
            class="bg-white
                   border border-gray-200
                   rounded-2xl
                   shadow-sm
                   overflow-hidden
                   h-fit">


            <div
                class="px-5
                       py-4
                       border-b
                       border-gray-200">

                <h2
                    class="font-bold
                           text-[#0E2958]">

                    Campus Locations

                </h2>


                <p
                    class="text-xs
                           text-gray-500
                           mt-1">

                    Select a location to view it on the map.

                </p>

            </div>


            {{-- LOCATION LIST --}}

            <div
                id="locationList"
                class="max-h-[650px]
                       overflow-y-auto">


                @foreach($markers as $number => $marker)

                    <button
                        type="button"
                        class="location-item"
                        data-id="{{ $number }}"
                        data-name="{{ strtolower($marker['name']) }}"
                    >

                        <span
                            class="location-number">

                            {{ $number }}

                        </span>


                        <span
                            class="location-name">

                            {{ $marker['name'] }}

                        </span>

                    </button>

                @endforeach


            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- BOTTOM INFORMATION --}}
    {{-- ========================================================= --}}

    <div
        class="grid grid-cols-1
               md:grid-cols-3
               gap-5
               mt-5">


        <div
            class="bg-white
                   border border-gray-200
                   rounded-2xl
                   p-5
                   shadow-sm">

            <div
                class="w-10 h-10
                       rounded-xl
                       bg-blue-50
                       flex items-center
                       justify-center
                       mb-4">

                <x-heroicon-o-building-office-2
                    class="w-5 h-5 text-[#0E4C6B]"
                />

            </div>


            <h3
                class="font-bold
                       text-[#0E2958]">

                Find Buildings

            </h3>


            <p
                class="text-sm
                       text-gray-500
                       mt-1">

                Locate academic buildings and important campus
                facilities.

            </p>

        </div>


        <div
            class="bg-white
                   border border-gray-200
                   rounded-2xl
                   p-5
                   shadow-sm">

            <div
                class="w-10 h-10
                       rounded-xl
                       bg-green-50
                       flex items-center
                       justify-center
                       mb-4">

                <x-heroicon-o-map-pin
                    class="w-5 h-5 text-green-600"
                />

            </div>


            <h3
                class="font-bold
                       text-[#0E2958]">

                Choose Destination

            </h3>


            <p
                class="text-sm
                       text-gray-500
                       mt-1">

                Select a starting point and destination to plan
                your campus navigation.

            </p>

        </div>


        <div
            class="bg-white
                   border border-gray-200
                   rounded-2xl
                   p-5
                   shadow-sm">

            <div
                class="w-10 h-10
                       rounded-xl
                       bg-orange-50
                       flex items-center
                       justify-center
                       mb-4">

                <x-heroicon-o-arrow-path
                    class="w-5 h-5 text-orange-500"
                />

            </div>


            <h3
                class="font-bold
                       text-[#0E2958]">

                Get Directions

            </h3>


            <p
                class="text-sm
                       text-gray-500
                       mt-1">

                Select your start and destination to display
                the navigation route.

            </p>

        </div>

    </div>

</div>


{{-- ============================================================= --}}
{{-- STYLES --}}
{{-- ============================================================= --}}

<style>

    /* =========================================================
       MAP CONTROL
    ========================================================= */

    .map-control {

        width: 36px;

        height: 36px;

        display: flex;

        align-items: center;

        justify-content: center;

        background: white;

        border: 1px solid #e5e7eb;

        border-radius: 9px;

        color: #475569;

        font-weight: 700;

        box-shadow:
            0 2px 6px rgba(0,0,0,.06);

        transition:
            .15s ease;

    }


    .map-control:hover {

        background: #f8fafc;

        color: #0E4C6B;

        border-color: #cbd5e1;

    }


    /* =========================================================
       CAMPUS MARKER
    ========================================================= */

    .campus-marker {

        position: absolute;

        width: 29px;

        height: 29px;

        border-radius: 9999px;

        border: 3px solid white;

        display: flex;

        align-items: center;

        justify-content: center;

        color: white;

        font-size: 9px;

        font-weight: 800;

        line-height: 1;

        cursor: pointer;

        z-index: 20;

        transform:
            translate(-50%, -50%);

        box-shadow:
            0 3px 10px rgba(0,0,0,.28);

        transition:
            transform .15s ease,
            box-shadow .15s ease,
            opacity .15s ease;

    }


    .campus-marker:hover {

        transform:
            translate(-50%, -50%)
            scale(1.25);

        box-shadow:
            0 0 0 5px
            rgba(14,76,107,.14),
            0 5px 15px
            rgba(0,0,0,.30);

    }


    .campus-marker.active {

        transform:
            translate(-50%, -50%)
            scale(1.4);

        box-shadow:
            0 0 0 6px
            rgba(14,76,107,.18),
            0 5px 18px
            rgba(0,0,0,.35);

    }


    /* =========================================================
       MARKER TYPES
    ========================================================= */

    .marker-academic {

        background: #0E4C6B;

    }


    .marker-recreation {

        background: #D97706;

    }


    .marker-facility {

        background: #059669;

    }


    /* =========================================================
       LOCATION LIST
    ========================================================= */

    .location-item {

        width: 100%;

        display: flex;

        align-items: center;

        gap: 12px;

        padding: 13px 17px;

        text-align: left;

        border-bottom:
            1px solid #f1f5f9;

        transition:
            background .15s ease;

    }


    .location-item:hover {

        background:
            rgba(14,76,107,.04);

    }


    .location-item.active {

        background:
            rgba(14,76,107,.09);

    }


    .location-number {

        width: 30px;

        height: 30px;

        flex-shrink: 0;

        border-radius: 9px;

        background: #eef4f7;

        color: #0E4C6B;

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 10px;

        font-weight: 800;

    }


    .location-item.active
    .location-number {

        background: #0E4C6B;

        color: white;

    }


    .location-name {

        font-size: 11px;

        line-height: 1.45;

        font-weight: 600;

        color: #334155;

    }


    /* =========================================================
       ROUTE MARKERS
    ========================================================= */

    .route-marker {

        position: absolute;

        width: 38px;

        height: 38px;

        border-radius: 9999px;

        border: 4px solid white;

        display: flex;

        align-items: center;

        justify-content: center;

        color: white;

        font-size: 12px;

        font-weight: 900;

        z-index: 50;

        transform:
            translate(-50%, -50%);

        box-shadow:
            0 4px 15px rgba(0,0,0,.30);

    }


    .start-marker {

        background: #16A34A;

    }


    .end-marker {

        background: #DC2626;

    }


    /* =========================================================
       LEGEND DOTS
    ========================================================= */

    .legend-dot {

        width: 9px;

        height: 9px;

        border-radius: 9999px;

        display: inline-block;

    }


    .academic-dot {

        background: #0E4C6B;

    }


    .recreation-dot {

        background: #D97706;

    }


    .facility-dot {

        background: #059669;

    }


    /* =========================================================
       ROUTE ANIMATION
    ========================================================= */

    #routePath {

        animation:
            routeDash 1.5s linear infinite;

    }


    @keyframes routeDash {

        to {

            stroke-dashoffset: -48;

        }

    }


    /* =========================================================
       MAP
    ========================================================= */

    #mapViewport {

        touch-action: none;

    }


    #mapCanvas {

        transition:
            transform .12s ease-out;

    }


    #routeLayer {

        z-index: 40;

    }

</style>


{{-- ============================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {


    /* =========================================================
       ELEMENTS
    ========================================================= */

    const viewport =
        document.getElementById('mapViewport');

    const canvas =
        document.getElementById('mapCanvas');

    const search =
        document.getElementById('locationSearch');

    const startSelect =
        document.getElementById('startLocation');

    const endSelect =
        document.getElementById('endLocation');

    const routeButton =
        document.getElementById('getDirections');

    const swapButton =
        document.getElementById('swapLocations');

    const routeLayer =
        document.getElementById('routeLayer');

    const routePath =
        document.getElementById('routePath');

    const routeMessage =
        document.getElementById('routeMessage');

    const routeTitle =
        document.getElementById('routeTitle');

    const routeDescription =
        document.getElementById('routeDescription');

    const startMarker =
        document.getElementById('startMarker');

    const endMarker =
        document.getElementById('endMarker');

    const status =
        document.getElementById('mapStatus');


    const markers =
        document.querySelectorAll(
            '.campus-marker'
        );


    const locations =
        document.querySelectorAll(
            '.location-item'
        );


    /* =========================================================
       MAP STATE
    ========================================================= */

    let scale = 1;

    let rotation = 0;

    let positionX = 0;

    let positionY = 0;

    let dragging = false;

    let startX = 0;

    let startY = 0;


    /* =========================================================
       LOCATION DATA
    ========================================================= */

    const locationData = {};


    markers.forEach(marker => {

        locationData[
            marker.dataset.id
        ] = {

            id:
                marker.dataset.id,

            name:
                marker.dataset.name,

            x:
                parseFloat(
                    marker.dataset.x
                ),

            y:
                parseFloat(
                    marker.dataset.y
                )

        };

    });


    /* =========================================================
       POPULATE SELECTS
    ========================================================= */

    Object.values(locationData)
        .forEach(location => {


            const startOption =
                document.createElement('option');


            startOption.value =
                location.id;


            startOption.textContent =
                `${location.id} — ${location.name}`;


            startSelect.appendChild(
                startOption
            );


            const endOption =
                document.createElement('option');


            endOption.value =
                location.id;


            endOption.textContent =
                `${location.id} — ${location.name}`;


            endSelect.appendChild(
                endOption
            );

        });


    /* =========================================================
       MAP TRANSFORM
    ========================================================= */

    function updateMap() {

        canvas.style.transform = `

            translate(
                calc(-50% + ${positionX}px),
                calc(-50% + ${positionY}px)
            )

            scale(${scale})

            rotate(${rotation}deg)

        `;

    }


    /* =========================================================
       ZOOM
    ========================================================= */

    function zoom(amount) {

        scale += amount;

        scale =
            Math.max(
                0.5,
                Math.min(
                    4,
                    scale
                )
            );

        updateMap();

    }


    /* =========================================================
       ROTATE
    ========================================================= */

    function rotateMap(amount) {

        rotation += amount;

        updateMap();

    }


    /* =========================================================
       RESET
    ========================================================= */

    function resetMap() {

        scale = 1;

        rotation = 0;

        positionX = 0;

        positionY = 0;

        clearRoute();

        clearSelections();

        updateMap();

    }


    /* =========================================================
       CLEAR SELECTIONS
    ========================================================= */

    function clearSelections() {

        markers.forEach(marker => {

            marker.classList.remove(
                'active'
            );

        });


        locations.forEach(location => {

            location.classList.remove(
                'active'
            );

        });

    }


    /* =========================================================
       SELECT LOCATION
    ========================================================= */

    function selectLocation(id) {


        const location =
            locationData[id];


        if (!location) {

            return;

        }


        markers.forEach(marker => {

            marker.classList.toggle(
                'active',
                marker.dataset.id ===
                String(id)
            );

        });


        locations.forEach(item => {

            item.classList.toggle(
                'active',
                item.dataset.id ===
                String(id)
            );

        });


        /*
        |--------------------------------------------------------------------------
        | Scroll selected item into view
        |--------------------------------------------------------------------------
        */

        const selectedItem =
            document.querySelector(
                `.location-item[data-id="${id}"]`
            );


        if (selectedItem) {

            selectedItem.scrollIntoView({

                behavior: 'smooth',

                block: 'nearest'

            });

        }

    }


    /* =========================================================
       MAP MARKER CLICK
    ========================================================= */

    markers.forEach(marker => {

        marker.addEventListener(
            'click',
            function (event) {

                event.stopPropagation();


                const id =
                    this.dataset.id;


                selectLocation(id);


                /*
                |--------------------------------------------------------------------------
                | If Start/Destination isn't selected,
                | put clicked location into the next
                | available field.
                |--------------------------------------------------------------------------
                */

                if (!startSelect.value) {

                    startSelect.value =
                        id;

                }

                else if (
                    !endSelect.value &&
                    startSelect.value !== id
                ) {

                    endSelect.value =
                        id;

                }

            }
        );

    });


    /* =========================================================
       LOCATION LIST CLICK
    ========================================================= */

    locations.forEach(item => {

        item.addEventListener(
            'click',
            function () {

                const id =
                    this.dataset.id;


                selectLocation(id);


                /*
                |--------------------------------------------------------------------------
                | Put clicked location into
                | Start or Destination.
                |--------------------------------------------------------------------------
                */

                if (!startSelect.value) {

                    startSelect.value =
                        id;

                }

                else if (
                    !endSelect.value &&
                    startSelect.value !== id
                ) {

                    endSelect.value =
                        id;

                }

            }
        );

    });


    /* =========================================================
       START SELECT CHANGE
    ========================================================= */

    startSelect.addEventListener(
        'change',
        function () {

            if (this.value) {

                selectLocation(
                    this.value
                );

            }

            clearRoute();

        }
    );


    /* =========================================================
       END SELECT CHANGE
    ========================================================= */

    endSelect.addEventListener(
        'change',
        function () {

            if (this.value) {

                selectLocation(
                    this.value
                );

            }

            clearRoute();

        }
    );


    /* =========================================================
       SWAP
    ========================================================= */

    swapButton.addEventListener(
        'click',
        function () {

            const start =
                startSelect.value;

            const end =
                endSelect.value;


            startSelect.value =
                end;

            endSelect.value =
                start;


            if (
                startSelect.value &&
                endSelect.value
            ) {

                drawRoute();

            }

        }
    );


    /* =========================================================
       GET DIRECTIONS
    ========================================================= */

    routeButton.addEventListener(
        'click',
        drawRoute
    );


    /* =========================================================
       DRAW ROUTE
    ========================================================= */

    function drawRoute() {


        const startId =
            startSelect.value;


        const endId =
            endSelect.value;


        if (!startId || !endId) {

            routeMessage.classList.remove(
                'hidden'
            );


            routeTitle.textContent =
                'Select both locations';


            routeDescription.textContent =
                'Choose a starting location and a destination before getting directions.';


            return;

        }


        if (startId === endId) {

            routeMessage.classList.remove(
                'hidden'
            );


            routeTitle.textContent =
                'Same location selected';


            routeDescription.textContent =
                'Your starting point and destination are the same location.';


            clearRoute();


            return;

        }


        const start =
            locationData[startId];


        const end =
            locationData[endId];


        if (!start || !end) {

            return;

        }


        /* =====================================================
           HIGHLIGHT
        ===================================================== */

        clearSelections();


        selectLocation(
            startId
        );


        const endItem =
            document.querySelector(
                `.location-item[data-id="${endId}"]`
            );


        const endMapMarker =
            document.querySelector(
                `.campus-marker[data-id="${endId}"]`
            );


        if (endItem) {

            endItem.classList.add(
                'active'
            );

        }


        if (endMapMarker) {

            endMapMarker.classList.add(
                'active'
            );

        }


        /* =====================================================
           POSITION A/B MARKERS
        ===================================================== */

        startMarker.style.left =
            `${start.x}%`;

        startMarker.style.top =
            `${start.y}%`;

        startMarker.classList.remove(
            'hidden'
        );


        endMarker.style.left =
            `${end.x}%`;

        endMarker.style.top =
            `${end.y}%`;

        endMarker.classList.remove(
            'hidden'
        );


        /* =====================================================
           ROUTE PATH
        ===================================================== */

        /*
        |--------------------------------------------------------------------------
        | For the current prototype we draw the route between
        | the selected locations.
        |
        | Later, this can be replaced by a campus road/path
        | network so the route follows the actual walkways.
        |--------------------------------------------------------------------------
        */

        const path = `

            M ${start.x * 10}
              ${start.y * 10}

            L ${end.x * 10}
              ${end.y * 10}

        `;


        routePath.setAttribute(
            'd',
            path
        );


        routeLayer.classList.remove(
            'hidden'
        );


        /* =====================================================
           MESSAGE
        ===================================================== */

        routeMessage.classList.remove(
            'hidden'
        );


        routeTitle.textContent =
            `${start.name} → ${end.name}`;


        routeDescription.textContent =
            'Start and destination selected. The route is displayed on the campus map.';

    }


    /* =========================================================
       CLEAR ROUTE
    ========================================================= */

    function clearRoute() {

        routeLayer.classList.add(
            'hidden'
        );


        startMarker.classList.add(
            'hidden'
        );


        endMarker.classList.add(
            'hidden'
        );


        routeMessage.classList.add(
            'hidden'
        );


        routePath.setAttribute(
            'd',
            ''
        );

    }


    /* =========================================================
       SEARCH
    ========================================================= */

    search.addEventListener(
        'input',
        function () {


            const value =
                this.value
                    .toLowerCase()
                    .trim();


            locations.forEach(item => {


                const name =
                    item.dataset.name;


                if (
                    value === '' ||
                    name.includes(value)
                ) {

                    item.classList.remove(
                        'hidden'
                    );

                }

                else {

                    item.classList.add(
                        'hidden'
                    );

                }

            });


            /*
            |--------------------------------------------------------------------------
            | Automatically highlight first match
            |--------------------------------------------------------------------------
            */

            if (value !== '') {


                const match =
                    Array.from(
                        locations
                    ).find(item =>
                        item.dataset.name
                            .includes(value)
                    );


                if (match) {

                    selectLocation(
                        match.dataset.id
                    );

                }

            }

        }
    );


    /* =========================================================
       MOUSE WHEEL ZOOM
    ========================================================= */

    viewport.addEventListener(
        'wheel',
        function (event) {

            event.preventDefault();


            if (event.deltaY < 0) {

                zoom(.12);

            }

            else {

                zoom(-.12);

            }

        },
        {
            passive: false
        }
    );


    /* =========================================================
       POINTER DOWN
    ========================================================= */

    viewport.addEventListener(
        'pointerdown',
        function (event) {


            /*
            |--------------------------------------------------------------------------
            | Don't start dragging when clicking controls,
            | markers, or other buttons.
            |--------------------------------------------------------------------------
            */

            if (
                event.target.closest(
                    'button'
                )
            ) {

                return;

            }


            dragging = true;


            viewport.setPointerCapture(
                event.pointerId
            );


            startX =
                event.clientX -
                positionX;


            startY =
                event.clientY -
                positionY;


            viewport.classList.remove(
                'cursor-grab'
            );


            viewport.classList.add(
                'cursor-grabbing'
            );

        }
    );


    /* =========================================================
       POINTER MOVE
    ========================================================= */

    viewport.addEventListener(
        'pointermove',
        function (event) {

            if (!dragging) {

                return;

            }


            positionX =
                event.clientX -
                startX;


            positionY =
                event.clientY -
                startY;


            updateMap();

        }
    );


    /* =========================================================
       STOP DRAG
    ========================================================= */

    function stopDragging() {

        dragging = false;


        viewport.classList.remove(
            'cursor-grabbing'
        );


        viewport.classList.add(
            'cursor-grab'
        );

    }


    viewport.addEventListener(
        'pointerup',
        stopDragging
    );


    viewport.addEventListener(
        'pointercancel',
        stopDragging
    );


    /* =========================================================
       FULLSCREEN
    ========================================================= */

    document
        .getElementById('fullscreenMap')
        .addEventListener(
            'click',
            function () {

                if (
                    !document.fullscreenElement
                ) {

                    viewport.requestFullscreen();

                }

                else {

                    document.exitFullscreen();

                }

            }
        );


    /* =========================================================
       BUTTONS
    ========================================================= */

    document
        .getElementById('zoomIn')
        .addEventListener(
            'click',
            () => zoom(.2)
        );


    document
        .getElementById('zoomOut')
        .addEventListener(
            'click',
            () => zoom(-.2)
        );


    document
        .getElementById('rotateLeft')
        .addEventListener(
            'click',
            () => rotateMap(-15)
        );


    document
        .getElementById('rotateRight')
        .addEventListener(
            'click',
            () => rotateMap(15)
        );


    document
        .getElementById('resetMap')
        .addEventListener(
            'click',
            resetMap
        );


    /* =========================================================
       INITIALIZE
    ========================================================= */

    updateMap();

});

</script>

@endsection