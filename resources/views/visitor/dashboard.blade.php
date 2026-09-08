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

                {{ $navigationAccess }}

            </span>

        </div>


        <h1
            class="text-3xl sm:text-4xl
                   font-extrabold
                   text-[#0E2958]">

            Campus Map

        </h1>


        <p
            class="text-gray-500
                   mt-2
                   max-w-3xl">

            View the Urdaneta City University campus layout and
            identify buildings, facilities, and other campus locations
            using the numbered legend.

        </p>

    </div>


    {{-- ========================================================= --}}
    {{-- MAIN CONTENT --}}
    {{-- ========================================================= --}}

    <div
        class="grid
               grid-cols-1
               xl:grid-cols-[1fr_360px]
               gap-5">


        {{-- ===================================================== --}}
        {{-- MAP --}}
        {{-- ===================================================== --}}

        <div
            class="bg-white
                   border border-gray-200
                   rounded-2xl
                   shadow-sm
                   overflow-hidden">


            {{-- MAP HEADER --}}

            <div
                class="px-5 py-4
                       border-b border-gray-200
                       flex flex-col
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

                        2D Campus Reference Map

                    </p>

                </div>


                {{-- MAP CONTROLS --}}

                <div
                    class="flex
                           items-center
                           gap-2">

                    <button
                        type="button"
                        id="zoomIn"
                        class="map-control"
                        title="Zoom in"
                        aria-label="Zoom in">

                        <x-heroicon-o-plus
                            class="w-4 h-4"
                        />

                    </button>


                    <button
                        type="button"
                        id="zoomOut"
                        class="map-control"
                        title="Zoom out"
                        aria-label="Zoom out">

                        <x-heroicon-o-minus
                            class="w-4 h-4"
                        />

                    </button>


                    <button
                        type="button"
                        id="resetMap"
                        class="reset-control">

                        Reset

                    </button>


                    <button
                        type="button"
                        id="fullscreenMap"
                        class="map-control"
                        title="Fullscreen"
                        aria-label="Fullscreen">

                        <x-heroicon-o-arrows-pointing-out
                            class="w-4 h-4"
                        />

                    </button>

                </div>

            </div>


            {{-- MAP VIEWPORT --}}

            <div
                id="mapViewport"
                class="relative
                       h-[560px]
                       sm:h-[650px]
                       lg:h-[760px]
                       bg-[#eef2f4]
                       overflow-hidden
                       cursor-grab
                       select-none">


                {{-- Background --}}

                <div
                    class="absolute
                           inset-0
                           opacity-30
                           pointer-events-none"
                    style="
                        background-image:
                            linear-gradient(#d7dde1 1px, transparent 1px),
                            linear-gradient(90deg, #d7dde1 1px, transparent 1px);
                        background-size: 40px 40px;
                    ">
                </div>


                {{-- MAP CONTENT --}}

                <div
                    id="mapContent"
                    class="absolute
                           left-1/2
                           top-1/2
                           origin-center
                           will-change-transform">


                    {{-- UPDATED PNG MAP --}}

                    <img
                        id="campusMap"
                        src="{{ asset('images/ucu-campus-map.png') }}"
                        alt="Urdaneta City University Campus Map"
                        draggable="false"
                        class="block
                               w-[850px]
                               sm:w-[1050px]
                               lg:w-[1200px]
                               max-w-none
                               object-contain
                               transition-[filter]
                               duration-200"
                    >

                </div>


                {{-- MAP INFO --}}

                <div
                    class="absolute
                           top-4
                           left-4
                           bg-white/95
                           backdrop-blur
                           border border-gray-200
                           rounded-xl
                           shadow-sm
                           px-4
                           py-3
                           pointer-events-none">

                    <p
                        class="text-xs
                               font-semibold
                               text-[#0E2958]">

                        UCU Campus Map

                    </p>

                    <p
                        class="text-[11px]
                               text-gray-500
                               mt-1">

                        Drag to move • Scroll to zoom

                    </p>

                </div>


                {{-- ZOOM LEVEL --}}

                <div
                    class="absolute
                           bottom-4
                           left-4
                           bg-white/95
                           backdrop-blur
                           border border-gray-200
                           rounded-lg
                           shadow-sm
                           px-3
                           py-2
                           pointer-events-none">

                    <span
                        id="zoomLevel"
                        class="text-[11px]
                               font-semibold
                               text-gray-600">

                        100%

                    </span>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- LEGEND --}}
        {{-- ===================================================== --}}

        <div
            class="bg-white
                   border border-gray-200
                   rounded-2xl
                   shadow-sm
                   overflow-hidden
                   h-fit">


            {{-- LEGEND HEADER --}}

            <div
                class="px-5 py-4
                       border-b border-gray-200">

                <div
                    class="flex
                           items-center
                           gap-3">

                    <div
                        class="w-10 h-10
                               rounded-xl
                               bg-[#0E4C6B]/10
                               flex
                               items-center
                               justify-center">

                        <x-heroicon-o-list-bullet
                            class="w-5 h-5
                                   text-[#0E4C6B]"
                        />

                    </div>


                    <div>

                        <h2
                            class="font-bold
                                   text-[#0E2958]">

                            Campus Legend

                        </h2>

                        <p
                            class="text-xs
                                   text-gray-500
                                   mt-1">

                            Building and facility reference

                        </p>

                    </div>

                </div>

            </div>


            {{-- LEGEND LIST --}}

            <div
                class="max-h-[700px]
                       overflow-y-auto">


                @php

                    $locations = [

                        1 => 'DR. LEONCIO ANCHETA BUILDING I',

                        2 => 'DR. LEONCIO ANCHETA BUILDING II',

                        3 => 'HONASAN HALL',

                        4 => 'BADAR BUILDING',

                        5 => 'DR. SOLEDAD F. CARINGAL BUILDING',

                        6 => 'NURSING BUILDING I',

                        7 => 'NURSING BUILDING II',

                        8 => "RESORT'S WORLD BUILDING",

                        9 => 'DR. TEOFIDEZ E. CALVERO BUILDING',

                        10 => 'DR. PEDRO T. ORATA BUILDING I',

                        11 => 'HON. JULIO E. PARAYNO BUILDING',

                        12 => 'DR. PEDRO T. ORATA BUILDING II',

                        13 => 'GYMNASIUM',

                        14 => 'SEWER TREATMENT PLANT',

                        15 => 'MINI GYMNASIUM',

                        16 => 'P.E. OFFICE',

                        17 => 'FITNESS GYM II',

                        18 => 'FITNESS GYM I',

                        19 => 'WELLNESS SPA',

                        20 => 'E.M.A.S.',

                        21 => 'GREEN HOME',

                        22 => 'GENERATOR SET',

                        23 => 'ANIMAL CLINIC',

                        24 => 'UNIVERSITY CLINIC',

                        25 => 'MOCK HOTEL',

                        26 => 'AUDIO VISUAL ROOM',

                        27 => 'DR. ORATA PARK',

                        28 => 'QUADRANGLE',

                        29 => 'SQUARE GARDEN',

                        30 => 'NDRRMO',

                        31 => 'AIRPLANE BUILDING',

                        32 => 'TURNSTILE',

                        33 => 'ENTREP. & LAW BUILDING',

                        34 => 'TOILET',

                        35 => 'PARKING',

                    ];

                @endphp


                @foreach ($locations as $number => $location)

                    <div
                        class="legend-item">

                        <span
                            class="legend-number">

                            {{ $number }}

                        </span>


                        <span
                            class="legend-name">

                            {{ $location }}

                        </span>

                    </div>

                @endforeach

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- INFORMATION BAR --}}
    {{-- ========================================================= --}}

    <div
        class="mt-5
               bg-[#0E2958]
               rounded-2xl
               p-5
               text-white">

        <div
            class="flex
                   items-center
                   gap-4">

            <div
                class="w-11 h-11
                       rounded-xl
                       bg-white/10
                       flex
                       items-center
                       justify-center
                       flex-shrink-0">

                <x-heroicon-o-map
                    class="w-6 h-6"
                />

            </div>


            <div>

                <h3
                    class="font-bold">

                    Campus Reference Map

                </h3>

                <p
                    class="text-sm
                           text-blue-100
                           mt-1">

                    Use the numbered legend to identify buildings,
                    facilities, and other locations around the UCU campus.
                    Interactive navigation and route guidance are available
                    through the UCU Smart+ mobile application.

                </p>

            </div>

        </div>

    </div>

</div>


{{-- ============================================================= --}}
{{-- STYLES --}}
{{-- ============================================================= --}}

<style>

    /*
    |--------------------------------------------------------------------------
    | MAP CONTROLS
    |--------------------------------------------------------------------------
    */

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

        box-shadow:
            0 2px 6px rgba(0, 0, 0, .06);

        transition:
            all .15s ease;

    }


    .map-control:hover {

        background: #f8fafc;

        color: #0E4C6B;

        border-color: #0E4C6B;

        transform: translateY(-1px);

        box-shadow:
            0 4px 10px rgba(14, 76, 107, .12);

    }


    .map-control:active {

        transform:
            translateY(0)
            scale(.96);

    }


    /*
    |--------------------------------------------------------------------------
    | RESET
    |--------------------------------------------------------------------------
    */

    .reset-control {

        height: 36px;

        padding:
            0 13px;

        border-radius: 9px;

        border:
            1px solid #e5e7eb;

        background: white;

        color: #475569;

        font-size: 12px;

        font-weight: 600;

        transition:
            all .15s ease;

    }


    .reset-control:hover {

        background: #f8fafc;

        color: #0E4C6B;

        border-color: #cbd5e1;

        transform:
            translateY(-1px);

    }


    /*
    |--------------------------------------------------------------------------
    | MAP
    |--------------------------------------------------------------------------
    */

    #campusMap {

        transform-origin:
            center center;

        user-select: none;

        -webkit-user-drag: none;

        pointer-events: none;

    }


    /*
    |--------------------------------------------------------------------------
    | LEGEND
    |--------------------------------------------------------------------------
    */

    .legend-item {

        display: flex;

        align-items: center;

        gap: 12px;

        padding:
            12px 18px;

        border-bottom:
            1px solid #f1f5f9;

        transition:
            background .15s ease;

    }


    .legend-item:hover {

        background:
            rgba(14, 76, 107, .035);

    }


    .legend-number {

        width: 31px;
        height: 31px;

        display: flex;

        align-items: center;

        justify-content: center;

        flex-shrink: 0;

        border-radius: 9px;

        background:
            #0E4C6B;

        color: white;

        font-size: 11px;

        font-weight: 800;

        box-shadow:
            0 2px 5px rgba(14, 76, 107, .18);

    }


    .legend-name {

        font-size: 12px;

        line-height: 1.4;

        font-weight: 600;

        color: #334155;

    }


    /*
    |--------------------------------------------------------------------------
    | SCROLLBAR
    |--------------------------------------------------------------------------
    */

    .max-h-\[700px\]::-webkit-scrollbar {

        width: 6px;

    }


    .max-h-\[700px\]::-webkit-scrollbar-track {

        background:
            #f8fafc;

    }


    .max-h-\[700px\]::-webkit-scrollbar-thumb {

        background:
            #cbd5e1;

        border-radius:
            999px;

    }


    .max-h-\[700px\]::-webkit-scrollbar-thumb:hover {

        background:
            #94a3b8;

    }


    /*
    |--------------------------------------------------------------------------
    | FULLSCREEN
    |--------------------------------------------------------------------------
    */

    #mapViewport:fullscreen {

        width: 100vw;

        height: 100vh;

        border-radius: 0;

    }


    #mapViewport:fullscreen #mapContent {

        transform-origin:
            center center;

    }

</style>


{{-- ============================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ============================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        /*
        |--------------------------------------------------------------------------
        | ELEMENTS
        |--------------------------------------------------------------------------
        */

        const viewport =
            document.getElementById(
                'mapViewport'
            );


        const content =
            document.getElementById(
                'mapContent'
            );


        const map =
            document.getElementById(
                'campusMap'
            );


        const zoomLevel =
            document.getElementById(
                'zoomLevel'
            );


        const zoomInButton =
            document.getElementById(
                'zoomIn'
            );


        const zoomOutButton =
            document.getElementById(
                'zoomOut'
            );


        const resetButton =
            document.getElementById(
                'resetMap'
            );


        const fullscreenButton =
            document.getElementById(
                'fullscreenMap'
            );


        /*
        |--------------------------------------------------------------------------
        | MAP STATE
        |--------------------------------------------------------------------------
        */

        let scale = 1;

        let positionX = 0;

        let positionY = 0;

        let dragging = false;

        let startX = 0;

        let startY = 0;


        const MIN_SCALE = 0.5;

        const MAX_SCALE = 4;


        /*
        |--------------------------------------------------------------------------
        | TRANSFORM
        |--------------------------------------------------------------------------
        */

        function updateTransform() {

            content.style.transform = `
                translate(
                    calc(-50% + ${positionX}px),
                    calc(-50% + ${positionY}px)
                )
                scale(${scale})
            `;


            zoomLevel.textContent =
                Math.round(scale * 100) + '%';

        }


        /*
        |--------------------------------------------------------------------------
        | ZOOM
        |--------------------------------------------------------------------------
        */

        function zoom(amount) {

            scale += amount;


            scale =
                Math.max(
                    MIN_SCALE,
                    Math.min(
                        MAX_SCALE,
                        scale
                    )
                );


            updateTransform();

        }


        /*
        |--------------------------------------------------------------------------
        | RESET
        |--------------------------------------------------------------------------
        */

        function resetMap() {

            scale = 1;

            positionX = 0;

            positionY = 0;

            updateTransform();

        }


        /*
        |--------------------------------------------------------------------------
        | BUTTONS
        |--------------------------------------------------------------------------
        */

        zoomInButton.addEventListener(
            'click',
            function () {

                zoom(.2);

            }
        );


        zoomOutButton.addEventListener(
            'click',
            function () {

                zoom(-.2);

            }
        );


        resetButton.addEventListener(
            'click',
            resetMap
        );


        /*
        |--------------------------------------------------------------------------
        | FULLSCREEN
        |--------------------------------------------------------------------------
        */

        fullscreenButton.addEventListener(
            'click',
            function () {

                if (
                    !document.fullscreenElement
                ) {

                    viewport.requestFullscreen();

                } else {

                    document.exitFullscreen();

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | MOUSE WHEEL ZOOM
        |--------------------------------------------------------------------------
        */

        viewport.addEventListener(
            'wheel',
            function (event) {

                event.preventDefault();


                if (
                    event.deltaY < 0
                ) {

                    zoom(.15);

                } else {

                    zoom(-.15);

                }

            },
            {
                passive: false
            }
        );


        /*
        |--------------------------------------------------------------------------
        | DRAG / PAN
        |--------------------------------------------------------------------------
        */

        viewport.addEventListener(
            'pointerdown',
            function (event) {

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


                updateTransform();

            }
        );


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


        /*
        |--------------------------------------------------------------------------
        | INITIAL MAP
        |--------------------------------------------------------------------------
        */

        updateTransform();

    }
);

</script>

@endsection