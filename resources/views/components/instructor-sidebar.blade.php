<div x-data="{ open: false }">

    <!-- Mobile Top Bar -->
    <div
        class="lg:hidden fixed top-0 left-0 right-0 h-16
               bg-[#0E2958] text-white
               flex items-center justify-between
               px-4 shadow-lg z-50">

        <div class="flex items-center gap-3">

            <img
                src="{{ asset('images/logo.png') }}"
                alt="UCU Smart+"
                class="w-10 h-10 object-contain">

            <span class="font-bold text-lg">
                UCU SMART+
            </span>

        </div>


        <button
            @click="open = !open"
            class="p-2 rounded-lg hover:bg-[#163A74] transition">

            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-7 h-7"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">

                <path
                    x-show="!open"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"/>

                <path
                    x-show="open"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"/>

            </svg>

        </button>

    </div>


    <!-- Overlay -->
    <div
        x-show="open"
        x-transition.opacity
        @click="open = false"
        class="fixed inset-0 bg-black/50 z-40 lg:hidden">
    </div>


    <!-- Sidebar -->
    <aside
        :class="open ? 'translate-x-0' : '-translate-x-full'"
        class="fixed top-0 left-0
               w-72 h-screen
               bg-[#0E2958] text-white
               flex flex-col
               shadow-2xl z-50
               transform transition-transform duration-300
               lg:translate-x-0">


        <!-- Logo -->
        <div
            class="border-b border-white/10
                   px-6 py-8
                   mt-16 lg:mt-0">

            <div class="flex items-center gap-4">

                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="UCU Smart+"
                    class="w-16 h-16 object-contain">


                <div>

                    <h1 class="text-2xl font-extrabold leading-tight">
                        UCU SMART+
                    </h1>

                    <p class="text-sm text-blue-200">
                        Instructor Portal
                    </p>

                </div>

            </div>

        </div>


        <!-- Navigation -->
        <nav
            class="flex-1 px-4 py-6
                   space-y-2
                   overflow-y-auto">


            <!-- Dashboard -->
            <a
                href="{{ route('instructor.dashboard') }}"
                class="flex items-center gap-3
                       px-4 py-3
                       rounded-xl
                       transition
                       {{ request()->routeIs('instructor.dashboard')
                            ? 'bg-[#0E4C6B] shadow-lg'
                            : 'hover:bg-[#163A74]' }}">

                <x-heroicon-o-squares-2x2 class="w-6 h-6"/>

                <span>
                    Dashboard
                </span>

            </a>


            <!-- My Schedule -->
            <a
                href="{{ route('instructor.schedule') }}"
                class="flex items-center gap-3
                       px-4 py-3
                       rounded-xl
                       transition
                       {{ request()->routeIs('instructor.schedule')
                            ? 'bg-[#0E4C6B] shadow-lg'
                            : 'hover:bg-[#163A74]' }}">

                <x-heroicon-o-calendar-days class="w-6 h-6"/>

                <span>
                    My Schedule
                </span>

            </a>


            <!-- Room Availability -->
            <a
                href="{{ route('instructor.rooms') }}"
                class="flex items-center gap-3
                       px-4 py-3
                       rounded-xl
                       transition
                       {{ request()->routeIs('instructor.rooms*')
                            ? 'bg-[#0E4C6B] shadow-lg'
                            : 'hover:bg-[#163A74]' }}">

                <x-heroicon-o-home-modern class="w-6 h-6"/>

                <span>
                    Room Availability
                </span>

            </a>


            <!-- Campus Navigation -->
            <a
                href="#"
                class="flex items-center gap-3
                       px-4 py-3
                       rounded-xl
                       transition
                       hover:bg-[#163A74]">

                <x-heroicon-o-map class="w-6 h-6"/>

                <span>
                    Campus Navigation
                </span>

            </a>


            <!-- My Profile -->
            <a
    href="{{ route('instructor.profile') }}"
    class="flex items-center gap-3
           px-4 py-3
           rounded-xl
           transition
           {{ request()->routeIs('instructor.profile')
                ? 'bg-[#0E4C6B] shadow-lg'
                : 'hover:bg-[#163A74]' }}">

    <x-heroicon-o-user-circle class="w-6 h-6"/>

    <span>
        My Profile
    </span>

</a>


        <!-- Bottom -->
        <div
            class="border-t border-white/10
                   p-5">


            <!-- User -->
            <div
                class="flex items-center gap-4
                       mb-5">

                <div
                    class="w-12 h-12
                           rounded-full
                           bg-[#0E4C6B]
                           flex items-center
                           justify-center
                           font-bold text-lg">

                    {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}

                </div>


                <div class="min-w-0">

                    <p class="font-semibold break-words">

                        {{ Auth::user()->first_name }}
                        {{ Auth::user()->last_name }}

                    </p>

                    <p class="text-sm text-blue-200">
                        Instructor
                    </p>

                </div>

            </div>


            <!-- Logout -->
            <form
                method="POST"
                action="{{ route('logout') }}">

                @csrf

                <button
                    type="submit"
                    class="w-full
                           flex items-center
                           justify-center gap-3
                           bg-red-600
                           hover:bg-red-700
                           transition
                           py-3
                           rounded-xl">

                    <x-heroicon-o-arrow-left-on-rectangle
                        class="w-5 h-5"/>

                    Logout

                </button>

            </form>

        </div>

    </aside>

</div>