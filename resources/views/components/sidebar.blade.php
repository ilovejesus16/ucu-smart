<div
    x-data="{ open: false }"
    x-cloak
    @keydown.escape.window="open = false"
>

    <!-- ========================================================= -->
    <!-- MOBILE TOP BAR -->
    <!-- ========================================================= -->

    <header
        class="lg:hidden fixed top-0 left-0 right-0 h-16
               bg-[#0E2958] text-white
               flex items-center justify-between
               px-4 shadow-lg z-[60]">

        <!-- Logo / Brand -->

        <div class="flex items-center gap-3">

            <img
                src="{{ asset('images/logo.png') }}"
                alt="UCU Smart+"
                class="w-10 h-10 object-contain">

            <div>

                <p class="font-extrabold text-lg leading-tight">
                    UCU SMART+
                </p>

                <p class="text-xs text-blue-200">
                    Administrator Panel
                </p>

            </div>

        </div>


        <!-- Mobile Menu Button -->

        <button
            type="button"
            @click="open = !open"
            class="flex items-center justify-center
                   w-11 h-11 rounded-xl
                   hover:bg-[#163A74]
                   active:bg-[#1B477F]
                   transition">

            <!-- Hamburger -->

            <svg
                x-show="!open"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="2"
                stroke="currentColor"
                class="w-7 h-7">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M4 6h16M4 12h16M4 18h16"/>

            </svg>


            <!-- X -->

            <svg
                x-show="open"
                x-cloak
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="2"
                stroke="currentColor"
                class="w-7 h-7">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M6 6l12 12M18 6L6 18"/>

            </svg>

        </button>

    </header>


    <!-- ========================================================= -->
    <!-- MOBILE OVERLAY -->
    <!-- ========================================================= -->

    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition-opacity duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false"
        class="fixed inset-0
               bg-black/50
               z-40
               lg:hidden">
    </div>


    <!-- ========================================================= -->
    <!-- SIDEBAR -->
    <!-- ========================================================= -->

    <aside
        :class="open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed
               top-0
               left-0
               bottom-0

               w-72

               bg-[#0E2958]
               text-white

               flex
               flex-col

               shadow-2xl

               z-50

               transform
               transition-transform
               duration-300
               ease-in-out

               lg:translate-x-0
               lg:z-50">


        <!-- ===================================================== -->
        <!-- SIDEBAR BRAND -->
        <!-- ===================================================== -->

        <div
            class="flex-shrink-0
                   border-b
                   border-white/10
                   px-6
                   py-7
                   lg:py-8">


            <div class="flex items-center gap-4">

                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="UCU Smart+"
                    class="w-16 h-16 object-contain">


                <div class="min-w-0">

                    <h1
                        class="text-2xl
                               font-extrabold
                               leading-tight
                               tracking-tight">

                        UCU SMART+

                    </h1>

                    <p class="text-sm text-blue-200">

                        Administrator Panel

                    </p>

                </div>

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- NAVIGATION -->
        <!-- ===================================================== -->

        <nav
            class="flex-1
                   min-h-0
                   overflow-y-auto
                   px-4
                   py-6
                   space-y-2">


            <!-- Dashboard -->

            <a
                href="{{ route('admin.dashboard') }}"
                @click="open = false"
                class="flex items-center gap-3
                       px-4 py-3
                       rounded-xl
                       transition

                       {{ request()->routeIs('admin.dashboard')
                            ? 'bg-[#0E4C6B] shadow-lg'
                            : 'hover:bg-[#163A74]' }}">

                <x-heroicon-o-squares-2x2
                    class="w-6 h-6 flex-shrink-0"/>

                <span>
                    Dashboard
                </span>

            </a>


            <!-- User Management -->

            <a
                href="{{ route('admin.users') }}"
                @click="open = false"
                class="flex items-center gap-3
                       px-4 py-3
                       rounded-xl
                       transition

                       {{ request()->routeIs('admin.users*')
                            ? 'bg-[#0E4C6B] shadow-lg'
                            : 'hover:bg-[#163A74]' }}">

                <x-heroicon-o-users
                    class="w-6 h-6 flex-shrink-0"/>

                <span>
                    User Management
                </span>

            </a>


            <!-- Buildings -->

            <a
                href="{{ route('buildings.index') }}"
                @click="open = false"
                class="flex items-center gap-3
                       px-4 py-3
                       rounded-xl
                       transition

                       {{ request()->routeIs('buildings*')
                            ? 'bg-[#0E4C6B] shadow-lg'
                            : 'hover:bg-[#163A74]' }}">

                <x-heroicon-o-building-office-2
                    class="w-6 h-6 flex-shrink-0"/>

                <span>
                    Buildings
                </span>

            </a>


            <!-- Rooms -->

            <a
                href="{{ route('rooms.index') }}"
                @click="open = false"
                class="flex items-center gap-3
                       px-4 py-3
                       rounded-xl
                       transition

                       {{ request()->routeIs('rooms*')
                            ? 'bg-[#0E4C6B] shadow-lg'
                            : 'hover:bg-[#163A74]' }}">

                <x-heroicon-o-home-modern
                    class="w-6 h-6 flex-shrink-0"/>

                <span>
                    Rooms
                </span>

            </a>


            <!-- Schedules -->

            <a
                href="{{ route('admin.schedules') }}"
                @click="open = false"
                class="flex items-center gap-3
                       px-4 py-3
                       rounded-xl
                       transition

                       {{ request()->routeIs('admin.schedules*')
                            ? 'bg-[#0E4C6B] shadow-lg'
                            : 'hover:bg-[#163A74]' }}">

                <x-heroicon-o-calendar-days
                    class="w-6 h-6 flex-shrink-0"/>

                <span>
                    Schedules
                </span>

            </a>
<!-- Reports -->

<a
    href="{{ route('admin.reports') }}"
    @click="open = false"
    class="flex items-center gap-3
           px-4 py-3
           rounded-xl
           transition
           {{ request()->routeIs('admin.reports*')
                ? 'bg-[#0E4C6B] shadow-lg'
                : 'hover:bg-[#163A74]' }}">

    <x-heroicon-o-chart-bar
        class="w-6 h-6 flex-shrink-0"/>

    <span>
        Reports
    </span>

</a>
</nav>
           

        <!-- ===================================================== -->
        <!-- USER / LOGOUT -->
        <!-- ===================================================== -->

        <div
            class="flex-shrink-0
                   border-t
                   border-white/10
                   p-5">


            <!-- User -->

            <div
                class="flex
                       items-center
                       gap-4
                       mb-5
                       min-w-0">


                <div
                    class="w-12
                           h-12
                           flex-shrink-0
                           rounded-full
                           bg-[#0E4C6B]
                           flex
                           items-center
                           justify-center
                           font-bold
                           text-lg">

                    {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}

                </div>


                <div class="min-w-0">

                    <p
                        class="font-semibold
                               truncate">

                        {{ Auth::user()->first_name }}
                        {{ Auth::user()->last_name }}

                    </p>

                    <p class="text-sm text-blue-200">

                        Administrator

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
                           flex
                           items-center
                           justify-center
                           gap-3
                           bg-red-600
                           hover:bg-red-700
                           active:bg-red-800
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


<!-- ============================================================= -->
<!-- RESPONSIVE LAYOUT FIX -->
<!-- ============================================================= -->

<style>

    [x-cloak] {
        display: none !important;
    }

</style>

