<div
    x-data="{
        open: false,
        mobileAppOpen: false
    }">

    <!-- ========================================================= -->
    <!-- MOBILE TOP BAR -->
    <!-- ========================================================= -->

    <div
        class="lg:hidden fixed top-0 left-0 right-0
               h-16 bg-[#0E2958] text-white
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
            type="button"
            @click="open = !open"
            class="p-2 rounded-lg
                   hover:bg-[#163A74]
                   transition">

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


    <!-- ========================================================= -->
    <!-- MOBILE OVERLAY -->
    <!-- ========================================================= -->

    <div
        x-show="open"
        x-transition.opacity
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
        :class="open ? 'translate-x-0' : '-translate-x-full'"
        class="fixed top-0 left-0
               w-72 h-screen
               bg-[#0E2958]
               text-white
               flex flex-col
               shadow-2xl
               z-50
               transform
               transition-transform
               duration-300
               lg:translate-x-0">


        <!-- ===================================================== -->
        <!-- LOGO -->
        <!-- ===================================================== -->

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

                    <h1
                        class="text-2xl
                               font-extrabold
                               leading-tight">

                        UCU SMART+

                    </h1>

                    <p class="text-sm text-blue-200">
                        Student Portal
                    </p>

                </div>

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- NAVIGATION -->
        <!-- ===================================================== -->

        <nav
            class="flex-1
                   px-4 py-6
                   space-y-2
                   overflow-y-auto">


            <!-- ================================================= -->
            <!-- DASHBOARD -->
            <!-- ================================================= -->

            <a
                href="{{ route('student.dashboard') }}"
                @click="open = false"
                class="flex items-center gap-3
                       px-4 py-3
                       rounded-xl
                       transition
                       {{ request()->routeIs('student.dashboard')
                            ? 'bg-[#0E4C6B] shadow-lg'
                            : 'hover:bg-[#163A74]' }}">

                <x-heroicon-o-squares-2x2
                    class="w-6 h-6"/>

                <span>
                    Dashboard
                </span>

            </a>


            <!-- ================================================= -->
            <!-- ROOM AVAILABILITY -->
            <!-- ================================================= -->

            <a
                href="{{ route('student.rooms') }}"
                @click="open = false"
                class="flex items-center gap-3
                       px-4 py-3
                       rounded-xl
                       transition
                       {{ request()->routeIs('student.rooms*')
                            ? 'bg-[#0E4C6B] shadow-lg'
                            : 'hover:bg-[#163A74]' }}">

                <x-heroicon-o-home-modern
                    class="w-6 h-6"/>

                <span>
                    Room Availability
                </span>

            </a>


            <!-- ================================================= -->
            <!-- UCU SMART+ MOBILE -->
            <!-- ================================================= -->

        <a
    href="{{ route('student.campus-navigation') }}"
    @click="open = false"
    class="w-full
           flex items-center gap-3
           px-4 py-3
           rounded-xl
           transition
           {{ request()->routeIs('student.campus-navigation')
                ? 'bg-[#0E4C6B] shadow-lg'
                : 'hover:bg-[#163A74]' }}">

    <x-heroicon-o-map
        class="w-6 h-6"/>

    <span>
        Campus Navigation
    </span>

</a>


            <!-- ================================================= -->
            <!-- MY PROFILE -->
            <!-- ================================================= -->

            <a
                href="{{ route('student.profile') }}"
                @click="open = false"
                class="flex items-center gap-3
                       px-4 py-3
                       rounded-xl
                       transition
                       {{ request()->routeIs('student.profile')
                            ? 'bg-[#0E4C6B] shadow-lg'
                            : 'hover:bg-[#163A74]' }}">

                <x-heroicon-o-user-circle
                    class="w-6 h-6"/>

                <span>
                    My Profile
                </span>

            </a>

        </nav>


        <!-- ===================================================== -->
        <!-- USER / LOGOUT -->
        <!-- ===================================================== -->

        <div
            class="border-t border-white/10
                   p-5">

            <div
                class="flex items-center gap-4
                       mb-5">

                <div
                    class="w-12 h-12
                           rounded-full
                           bg-[#0E4C6B]
                           flex items-center
                           justify-center
                           font-bold
                           text-lg">

                    {{ strtoupper(
                        substr(Auth::user()->first_name, 0, 1)
                    ) }}

                </div>

                <div class="min-w-0">

                    <p
                        class="font-semibold
                               break-words">

                        {{ Auth::user()->first_name }}
                        {{ Auth::user()->last_name }}

                    </p>

                    <p class="text-sm text-blue-200">
                        Student
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
                           py-3
                           rounded-xl
                           transition">

                    <x-heroicon-o-arrow-left-on-rectangle
                        class="w-5 h-5"/>

                    Logout

                </button>

            </form>

        </div>

    </aside>


    <!-- ========================================================= -->
    <!-- UCU SMART+ MOBILE MODAL -->
    <!-- ========================================================= -->

    <div
        x-show="mobileAppOpen"
        x-transition.opacity
        @keydown.escape.window="mobileAppOpen = false"
        class="fixed inset-0
               z-[100]
               flex items-center
               justify-center
               p-4
               bg-black/50"
        style="display: none;">

        <!-- Modal -->

        <div
            x-show="mobileAppOpen"
            x-transition
            @click.outside="mobileAppOpen = false"
            class="w-full
                   max-w-lg
                   bg-white
                   rounded-2xl
                   shadow-2xl
                   overflow-hidden">


            <!-- Header -->

            <div
                class="bg-[#0E2958]
                       px-6
                       py-6
                       text-white">

                <div
                    class="flex items-center
                           justify-between
                           gap-4">

                    <div class="flex items-center gap-4">

                        <div
                            class="w-12 h-12
                                   rounded-xl
                                   bg-white/10
                                   flex items-center
                                   justify-center">

                            <x-heroicon-o-map
                                class="w-6 h-6"/>

                        </div>

                        <div>

                            <h2
                                class="text-xl
                                       font-bold">

                                UCU Smart+ Mobile

                            </h2>

                            <p
                                class="text-sm
                                       text-blue-200">

                                Campus Navigation

                            </p>

                        </div>

                    </div>


                    <!-- Close -->

                    <button
                        type="button"
                        @click="mobileAppOpen = false"
                        class="p-2
                               rounded-lg
                               hover:bg-white/10
                               transition">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-6 h-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"/>

                        </svg>

                    </button>

                </div>

            </div>


            <!-- Content -->

            <div class="p-6 sm:p-8">

                <h3
                    class="text-xl
                           font-bold
                           text-[#0E2958]">

                    Navigate Around UCU

                </h3>


                <p
                    class="text-gray-500
                           mt-2
                           leading-relaxed">

                    Campus navigation is available through the
                    UCU Smart+ mobile application.

                </p>


                <!-- Features -->

                <div
                    class="mt-6
                           space-y-3">

                    <div
                        class="flex items-center gap-3
                               text-sm text-gray-600">

                        <div
                            class="w-8 h-8
                                   rounded-lg
                                   bg-[#0E4C6B]/10
                                   flex items-center
                                   justify-center
                                   flex-shrink-0">

                            <x-heroicon-o-map
                                class="w-4 h-4
                                       text-[#0E4C6B]"/>

                        </div>

                        <span>
                            Interactive campus map
                        </span>

                    </div>


                    <div
                        class="flex items-center gap-3
                               text-sm text-gray-600">

                        <div
                            class="w-8 h-8
                                   rounded-lg
                                   bg-[#0E4C6B]/10
                                   flex items-center
                                   justify-center
                                   flex-shrink-0">

                            <x-heroicon-o-magnifying-glass
                                class="w-4 h-4
                                       text-[#0E4C6B]"/>

                        </div>

                        <span>
                            Search buildings and facilities
                        </span>

                    </div>


                    <div
                        class="flex items-center gap-3
                               text-sm text-gray-600">

                        <div
                            class="w-8 h-8
                                   rounded-lg
                                   bg-[#0E4C6B]/10
                                   flex items-center
                                   justify-center
                                   flex-shrink-0">

                            <x-heroicon-o-map-pin
                                class="w-4 h-4
                                       text-[#0E4C6B]"/>

                        </div>

                        <span>
                            Select start and destination
                        </span>

                    </div>


                    <div
                        class="flex items-center gap-3
                               text-sm text-gray-600">

                        <div
                            class="w-8 h-8
                                   rounded-lg
                                   bg-[#0E4C6B]/10
                                   flex items-center
                                   justify-center
                                   flex-shrink-0">

                            <x-heroicon-o-arrow-path
                                class="w-4 h-4
                                       text-[#0E4C6B]"/>

                        </div>

                        <span>
                            Get directions around campus
                        </span>

                    </div>

                </div>


                <!-- Download -->

                <div
                    class="mt-7
                           pt-6
                           border-t border-gray-100">

                    <a
                        href="YOUR_FLUTTER_APP_LINK"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="w-full
                               inline-flex
                               items-center
                               justify-center
                               gap-2
                               bg-[#0E2958]
                               hover:bg-[#0B2147]
                               text-white
                               px-5
                               py-3.5
                               rounded-xl
                               font-semibold
                               transition">

                        <x-heroicon-o-arrow-down-tray
                            class="w-5 h-5"/>

                        Get the Mobile App

                    </a>


                    <p
                        class="text-xs
                               text-gray-400
                               text-center
                               mt-3">

                        Available for mobile devices.

                    </p>

                </div>

            </div>

        </div>

    </div>

</div>