<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Forgot Password | UCU Smart+</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="min-h-screen bg-cover bg-center relative"
    style="background-image:url('{{ asset('images/ucu-campus.jpg') }}');">

    <!-- Blue Overlay -->
    <div class="absolute inset-0 bg-[#0E4C6B]/55"></div>

    <!-- Content -->
    <div class="relative z-10 min-h-screen backdrop-blur-[2px] flex items-center justify-center p-4 sm:p-6">

        <div class="w-full max-w-7xl
                    bg-white/25 backdrop-blur-lg
                    rounded-3xl lg:rounded-[55px]
                    shadow-[0_30px_60px_rgba(0,0,0,0.25)]
                    overflow-hidden
                    grid
                    grid-cols-1
                    lg:grid-cols-[1fr_0.88fr]
                    gap-6">

            <!-- LEFT PANEL -->
            <div class="bg-[#0E2958] rounded-3xl lg:rounded-none px-6 sm:px-8 lg:px-12 py-8 text-white flex flex-col justify-between">

                <div class="flex justify-center">
                    <img
                        src="{{ asset('images/logo.png') }}"
                        alt="UCU Smart+"
                        class="h-28 sm:h-40 lg:h-60 w-auto object-contain">
                </div>

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-center mt-4">
                    <span class="text-yellow-400">UCU</span>
                    SMART<span class="text-yellow-400">+</span>
                </h1>

                <p class="text-center text-sm sm:text-base lg:text-xl text-gray-200 leading-relaxed mt-5">
                    A Multiplatform Classroom Availability Monitoring and Campus Navigation System for
                    <span class="text-yellow-400 font-semibold">
                        Urdaneta City University
                    </span>
                </p>

                <div class="space-y-4 mt-8">

                    <div class="bg-[#163A74] rounded-2xl p-4 sm:p-5 flex gap-4">
                        <div class="bg-blue-600 p-3 rounded-xl shrink-0">
                            <x-heroicon-s-calendar-days class="w-6 h-6 sm:w-7 sm:h-7 text-white"/>
                        </div>

                        <div>
                            <h3 class="font-bold text-base sm:text-lg">Smart Scheduling</h3>
                            <p class="text-sm sm:text-base text-gray-300">
                                Upload, manage and confirm classroom schedules.
                            </p>
                        </div>
                    </div>

                    <div class="bg-[#163A74] rounded-2xl p-4 sm:p-5 flex gap-4">
                        <div class="bg-green-600 p-3 rounded-xl shrink-0">
                            <x-heroicon-s-signal class="w-6 h-6 sm:w-7 sm:h-7 text-white"/>
                        </div>

                        <div>
                            <h3 class="font-bold text-base sm:text-lg">Real-Time Monitoring</h3>
                            <p class="text-sm sm:text-base text-gray-300">
                                Monitor classroom availability in real time.
                            </p>
                        </div>
                    </div>

                    <div class="bg-[#163A74] rounded-2xl p-4 sm:p-5 flex gap-4">
                        <div class="bg-orange-500 p-3 rounded-xl shrink-0">
                            <x-heroicon-s-map-pin class="w-6 h-6 sm:w-7 sm:h-7 text-white"/>
                        </div>

                        <div>
                            <h3 class="font-bold text-base sm:text-lg">Campus Navigation</h3>
                            <p class="text-sm sm:text-base text-gray-300">
                                Easily locate buildings and classrooms.
                            </p>
                        </div>
                    </div>

                </div>

            </div>

            <!-- RIGHT PANEL -->

            <div class="flex justify-center items-center p-4 sm:p-6">

                <div class="w-full max-w-lg bg-white/90 backdrop-blur-xl rounded-3xl lg:rounded-[35px] shadow-2xl p-6 sm:p-8 lg:p-12">

                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-center text-[#0E2958]">
                        Forgot Password
                    </h2>

                    <p class="text-center text-sm sm:text-base text-gray-500 mt-3 mb-8">
                        Enter your email address and we'll send you a password reset link.
                    </p>

                    <x-auth-session-status class="mb-6" :status="session('status')" />

                    @if ($errors->any())
                        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">
                            @foreach ($errors->all() as $error)
                                <p class="text-red-700 text-sm break-words">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-6">

                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Email Address
                            </label>

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                class="w-full rounded-xl border border-gray-300 px-4 sm:px-5 py-4 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

                        </div>

                        <button
                            type="submit"
                            class="w-full bg-[#0E4C6B] hover:bg-[#0B3D56] text-white font-bold py-4 rounded-xl transition">

                            SEND RESET LINK

                        </button>

                        <div class="mt-6 text-center">

                            <a
                                href="{{ route('login') }}"
                                class="text-[#0E4C6B] font-semibold hover:underline">

                                ← Back to Login

                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</body>
</html>