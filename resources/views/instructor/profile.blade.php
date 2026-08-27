@extends('layouts.instructor')

@section('title', 'My Profile')

@section('content')

<div class="max-w-5xl mx-auto">

    <!-- ========================================================= -->
    <!-- PAGE HEADER -->
    <!-- ========================================================= -->

    <div class="mb-8">

        <h1 class="text-3xl sm:text-4xl font-extrabold text-[#0E2958]">
            My Profile
        </h1>

        <p class="text-gray-500 mt-2">
            View your instructor account information.
        </p>

    </div>


    <!-- ========================================================= -->
    <!-- PROFILE CARD -->
    <!-- ========================================================= -->

    <div
        class="bg-white
               rounded-2xl
               border border-gray-200
               shadow-sm
               overflow-hidden">


        <!-- Profile Header -->

        <div
            class="bg-[#0E2958]
                   px-6 sm:px-8
                   py-8">

            <div
                class="flex flex-col
                       sm:flex-row
                       sm:items-center
                       gap-5">


                <!-- Avatar -->

                <div
                    class="w-20 h-20
                           rounded-2xl
                           bg-[#0E4C6B]
                           border border-white/20
                           flex items-center
                           justify-center
                           text-white
                           text-3xl
                           font-bold
                           flex-shrink-0">

                    {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}

                </div>


                <!-- Name -->

                <div class="min-w-0">

                    <p class="text-blue-200 text-sm font-medium">
                        Instructor Account
                    </p>

                    <h2
                        class="text-2xl sm:text-3xl
                               font-extrabold
                               text-white
                               mt-1
                               break-words">

                        {{ Auth::user()->first_name }}
                        {{ Auth::user()->last_name }}

                    </h2>

                    <p class="text-blue-100 mt-1">
                        UCU Smart+ Instructor Portal
                    </p>

                </div>

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- ACCOUNT INFORMATION -->
        <!-- ===================================================== -->

        <div class="p-6 sm:p-8">

            <h3
                class="text-xl
                       font-bold
                       text-[#0E2958]
                       mb-6">

                Account Information

            </h3>


            <div
                class="grid grid-cols-1
                       md:grid-cols-2
                       gap-5">


                <!-- First Name -->

                <div>

                    <label
                        class="block
                               text-sm
                               font-semibold
                               text-gray-600
                               mb-2">

                        First Name

                    </label>

                    <div
                        class="w-full
                               bg-gray-50
                               border border-gray-200
                               rounded-xl
                               px-4 py-3
                               text-gray-800">

                        {{ Auth::user()->first_name }}

                    </div>

                </div>


                <!-- Last Name -->

                <div>

                    <label
                        class="block
                               text-sm
                               font-semibold
                               text-gray-600
                               mb-2">

                        Last Name

                    </label>

                    <div
                        class="w-full
                               bg-gray-50
                               border border-gray-200
                               rounded-xl
                               px-4 py-3
                               text-gray-800">

                        {{ Auth::user()->last_name }}

                    </div>

                </div>


                <!-- Employee ID -->

                <div>

                    <label
                        class="block
                               text-sm
                               font-semibold
                               text-gray-600
                               mb-2">

                        Employee ID

                    </label>

                    <div
                        class="w-full
                               bg-gray-50
                               border border-gray-200
                               rounded-xl
                               px-4 py-3
                               text-gray-800">

                        {{ Auth::user()->employee_id ?? 'Not provided' }}

                    </div>

                </div>


                <!-- Email -->

                <div>

                    <label
                        class="block
                               text-sm
                               font-semibold
                               text-gray-600
                               mb-2">

                        Email Address

                    </label>

                    <div
                        class="w-full
                               bg-gray-50
                               border border-gray-200
                               rounded-xl
                               px-4 py-3
                               text-gray-800
                               break-all">

                        {{ Auth::user()->email }}

                    </div>

                </div>


                


                <!-- Department -->

                <div>

                    <label
                        class="block
                               text-sm
                               font-semibold
                               text-gray-600
                               mb-2">

                        Department

                    </label>

                    <div
                        class="w-full
                               bg-gray-50
                               border border-gray-200
                               rounded-xl
                               px-4 py-3
                               text-gray-800">

                        {{ Auth::user()->department ?? 'Not provided' }}

                    </div>

                </div>

            </div>


            <!-- ================================================= -->
            <!-- ACCOUNT STATUS -->
            <!-- ================================================= -->

            <div
                class="mt-8
                       pt-6
                       border-t border-gray-200">

                <h3
                    class="text-lg
                           font-bold
                           text-[#0E2958]
                           mb-4">

                    Account Status

                </h3>


                <div
                    class="flex flex-col
                           sm:flex-row
                           sm:items-center
                           sm:justify-between
                           gap-4
                           bg-gray-50
                           border border-gray-200
                           rounded-xl
                           p-4">

                    <div
                        class="flex items-center
                               gap-3">

                        <div
                            class="w-10 h-10
                                   rounded-lg
                                   bg-[#0E4C6B]/10
                                   flex items-center
                                   justify-center">

                            <x-heroicon-o-shield-check
                                class="w-5 h-5 text-[#0E4C6B]"/>

                        </div>

                        <div>

                            <p class="font-semibold text-gray-800">
                                Instructor Account
                            </p>

                            <p class="text-sm text-gray-500">
                                Your account is currently active.
                            </p>

                        </div>

                    </div>


                    <span
                        class="inline-flex
                               items-center gap-2
                               bg-green-50
                               text-green-700
                               border border-green-200
                               px-3 py-1.5
                               rounded-full
                               text-xs
                               font-semibold
                               self-start
                               sm:self-auto">

                        <span
                            class="w-2 h-2
                                   rounded-full
                                   bg-green-500">
                        </span>

                        {{ ucfirst(Auth::user()->status ?? 'Active') }}

                    </span>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection