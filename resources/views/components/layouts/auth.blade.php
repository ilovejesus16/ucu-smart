<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <title>{{ $title ?? 'UCU Smart+' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="min-h-screen bg-cover bg-center relative"
    style="background-image:url('{{ asset('images/ucu-campus.jpg') }}');">

    <!-- Blue Overlay -->
    <div class="absolute inset-0 bg-[#0E4C6B]/55"></div>

    <!-- Main -->
    <div class="relative z-10 min-h-screen flex items-center justify-center p-4 sm:p-6">

        <div class="w-full max-w-7xl
            bg-white/20
            backdrop-blur-lg
            rounded-3xl lg:rounded-[55px]
            shadow-[0_30px_60px_rgba(0,0,0,0.25)]
            overflow-hidden
            grid
            grid-cols-1
            lg:grid-cols-[1fr_0.88fr]
            gap-6 lg:gap-8
            items-center
            p-4 sm:p-5">

            <!-- LEFT PANEL -->
            <div class="bg-[#0E2958] rounded-3xl lg:rounded-[45px] px-6 sm:px-8 lg:px-12 py-8 text-white flex flex-col justify-between">

                <div>

                    <div class="flex justify-center">

                        <img
                            src="{{ asset('images/logo.png') }}"
                            class="h-28 sm:h-40 lg:h-56 object-contain"
                            alt="UCU Smart+">

                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-center mt-2">

                        <span class="text-yellow-400">UCU</span>

                        SMART<span class="text-yellow-400">+</span>

                    </h1>

                    <p class="text-center text-sm sm:text-base lg:text-lg leading-7 lg:leading-8 mt-5 text-gray-200">

                        A Multiplatform Classroom Availability Monitoring
                        and Campus Navigation System for

                        <span class="text-yellow-400 font-semibold">

                            Urdaneta City University

                        </span>

                    </p>

                </div>

                <div class="space-y-4 mt-8">

                    <div class="bg-[#163A74] rounded-2xl p-4 sm:p-5 flex gap-4">

                        <div class="bg-blue-600 rounded-xl p-3 shrink-0">

                            <x-heroicon-s-calendar-days class="w-6 h-6 sm:w-7 sm:h-7 text-white"/>

                        </div>

                        <div>

                            <h3 class="font-bold text-base sm:text-lg">

                                Smart Scheduling

                            </h3>

                            <p class="text-gray-300 text-sm sm:text-base">

                                Upload, manage and confirm classroom schedules.

                            </p>

                        </div>

                    </div>

                    <div class="bg-[#163A74] rounded-2xl p-4 sm:p-5 flex gap-4">

                        <div class="bg-green-600 rounded-xl p-3 shrink-0">

                            <x-heroicon-s-signal class="w-6 h-6 sm:w-7 sm:h-7 text-white"/>

                        </div>

                        <div>

                            <h3 class="font-bold text-base sm:text-lg">

                                Real-Time Monitoring

                            </h3>

                            <p class="text-gray-300 text-sm sm:text-base">

                                Monitor classroom availability in real time.

                            </p>

                        </div>

                    </div>

                    <div class="bg-[#163A74] rounded-2xl p-4 sm:p-5 flex gap-4">

                        <div class="bg-orange-500 rounded-xl p-3 shrink-0">

                            <x-heroicon-s-map-pin class="w-6 h-6 sm:w-7 sm:h-7 text-white"/>

                        </div>

                        <div>

                            <h3 class="font-bold text-base sm:text-lg">

                                Campus Navigation

                            </h3>

                            <p class="text-gray-300 text-sm sm:text-base">

                                Easily locate buildings and classrooms.

                            </p>

                        </div>

                    </div>

                </div>

            </div>

            <!-- RIGHT PANEL -->
            <div class="flex justify-center items-center">

                <div class="w-full max-w-lg bg-white/90 backdrop-blur-xl rounded-3xl lg:rounded-[35px] shadow-2xl p-6 sm:p-8 lg:p-10">

                    {{ $slot }}

                </div>

            </div>

        </div>

    </div>

</body>
</html>