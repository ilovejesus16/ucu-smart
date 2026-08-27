@extends('layouts.student')

@section('title', 'My Profile')

@section('content')

<div
    class="max-w-5xl mx-auto"
    x-data="{ passwordModal: {{ $errors->any() ? 'true' : 'false' }} }">

    <!-- ========================================================= -->
    <!-- PAGE HEADER -->
    <!-- ========================================================= -->

    <div class="mb-8">

        <h1 class="text-3xl sm:text-4xl font-extrabold text-[#0E2958]">
            My Profile
        </h1>

        <p class="text-gray-500 mt-2">
            View your student account information.
        </p>

    </div>


    <!-- ========================================================= -->
    <!-- SUCCESS MESSAGE -->
    <!-- ========================================================= -->

    @if(session('password_success'))

        <div
            class="mb-6
                   bg-green-50
                   border border-green-200
                   rounded-xl
                   p-4
                   flex items-start gap-3">

            <div
                class="w-9 h-9
                       rounded-lg
                       bg-green-100
                       flex items-center
                       justify-center
                       flex-shrink-0">

                <x-heroicon-o-check-circle
                    class="w-5 h-5 text-green-600"/>

            </div>

            <div>

                <p class="font-semibold text-green-700">
                    Password Updated
                </p>

                <p class="text-sm text-green-600 mt-0.5">
                    Your password has been changed successfully.
                </p>

            </div>

        </div>

    @endif


    <!-- ========================================================= -->
    <!-- PROFILE CARD -->
    <!-- ========================================================= -->

    <div
        class="bg-white
               rounded-2xl
               border border-gray-200
               shadow-sm
               overflow-hidden">


        <!-- ===================================================== -->
        <!-- PROFILE HEADER -->
        <!-- ===================================================== -->

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
                        Student Account
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
                        UCU Smart+ Student Portal
                    </p>

                </div>

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- ACCOUNT INFORMATION -->
        <!-- ===================================================== -->

        <div class="p-6 sm:p-8">

            <div
                class="flex flex-col
                       sm:flex-row
                       sm:items-center
                       sm:justify-between
                       gap-4
                       mb-6">

                <div>

                    <h3
                        class="text-xl
                               font-bold
                               text-[#0E2958]">

                        Student Information

                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Your registered account details.
                    </p>

                </div>

            </div>


            <!-- ================================================= -->
            <!-- INFORMATION GRID -->
            <!-- ================================================= -->

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
                        class="bg-gray-50
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
                        class="bg-gray-50
                               border border-gray-200
                               rounded-xl
                               px-4 py-3
                               text-gray-800">

                        {{ Auth::user()->last_name }}

                    </div>

                </div>


                <!-- Student ID -->

                <div>

                    <label
                        class="block
                               text-sm
                               font-semibold
                               text-gray-600
                               mb-2">

                        Student ID

                    </label>

                    <div
                        class="bg-gray-50
                               border border-gray-200
                               rounded-xl
                               px-4 py-3
                               text-gray-800">

                        {{ Auth::user()->student_id ?? 'Not provided' }}

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
                        class="bg-gray-50
                               border border-gray-200
                               rounded-xl
                               px-4 py-3
                               text-gray-800
                               break-all">

                        {{ Auth::user()->email }}

                    </div>

                </div>


                <!-- Course -->

                <div>

                    <label
                        class="block
                               text-sm
                               font-semibold
                               text-gray-600
                               mb-2">

                        Course

                    </label>

                    <div
                        class="bg-gray-50
                               border border-gray-200
                               rounded-xl
                               px-4 py-3
                               text-gray-800">

                        {{ Auth::user()->course ?? 'Not provided' }}

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
                                Student Account
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


            <!-- ================================================= -->
            <!-- SECURITY -->
            <!-- ================================================= -->

            <div
                class="mt-8
                       pt-6
                       border-t border-gray-200">

                <div
                    class="flex flex-col
                           sm:flex-row
                           sm:items-center
                           sm:justify-between
                           gap-5">

                    <div
                        class="flex items-center
                               gap-4">

                        <div
                            class="w-11 h-11
                                   rounded-xl
                                   bg-[#0E4C6B]/10
                                   flex items-center
                                   justify-center
                                   flex-shrink-0">

                            <x-heroicon-o-lock-closed
                                class="w-5 h-5 text-[#0E4C6B]"/>

                        </div>

                        <div>

                            <h3
                                class="font-bold
                                       text-[#0E2958]">

                                Password & Security

                            </h3>

                            <p
                                class="text-sm
                                       text-gray-500
                                       mt-1">

                                Keep your account secure by updating your password.

                            </p>

                        </div>

                    </div>


                    <!-- Change Password Button -->

                    <button
                        type="button"
                        @click="passwordModal = true"
                        class="inline-flex
                               items-center
                               justify-center
                               gap-2
                               bg-[#0E2958]
                               hover:bg-[#0B2147]
                               text-white
                               px-5
                               py-3
                               rounded-xl
                               font-semibold
                               transition
                               shadow-sm
                               w-full
                               sm:w-auto">

                        <x-heroicon-o-key
                            class="w-5 h-5"/>

                        Change Password

                    </button>

                </div>

            </div>

        </div>

    </div>


    <!-- ========================================================= -->
    <!-- CHANGE PASSWORD MODAL -->
    <!-- ========================================================= -->

    <div
        x-show="passwordModal"
        x-transition.opacity
        class="fixed inset-0 z-[100]
               flex items-center
               justify-center
               p-4"
        style="display: none;">

        <!-- Backdrop -->

        <div
            class="absolute inset-0
                   bg-black/50
                   backdrop-blur-sm"
            @click="passwordModal = false">
        </div>


        <!-- Modal -->

        <div
            x-show="passwordModal"
            x-transition
            @click.stop
            class="relative
                   w-full
                   max-w-lg
                   bg-white
                   rounded-2xl
                   shadow-2xl
                   overflow-hidden">


            <!-- Modal Header -->

            <div
                class="bg-[#0E2958]
                       px-6 py-5
                       flex items-center
                       justify-between">

                <div
                    class="flex items-center
                           gap-3">

                    <div
                        class="w-10 h-10
                               rounded-xl
                               bg-white/10
                               flex items-center
                               justify-center">

                        <x-heroicon-o-lock-closed
                            class="w-5 h-5 text-white"/>

                    </div>

                    <div>

                        <h2
                            class="text-lg
                                   font-bold
                                   text-white">

                            Change Password

                        </h2>

                        <p
                            class="text-xs
                                   text-blue-200
                                   mt-0.5">

                            Update your account password

                        </p>

                    </div>

                </div>


                <!-- Close -->

                <button
                    type="button"
                    @click="passwordModal = false"
                    class="p-2
                           rounded-lg
                           text-blue-200
                           hover:text-white
                           hover:bg-white/10
                           transition">

                    <x-heroicon-o-x-mark
                        class="w-6 h-6"/>

                </button>

            </div>


            <!-- Modal Body -->

            <form
                action="{{ route('student.profile.password') }}"
                method="POST"
                class="p-6">

                @csrf


                <!-- Error -->

                @if($errors->any())

                    <div
                        class="mb-5
                               bg-red-50
                               border border-red-200
                               rounded-xl
                               p-4">

                        <div
                            class="flex items-start
                                   gap-3">

                            <x-heroicon-o-exclamation-circle
                                class="w-5 h-5
                                       text-red-600
                                       flex-shrink-0"/>

                            <div>

                                <p
                                    class="font-semibold
                                           text-red-700">

                                    Unable to change password

                                </p>

                                <ul
                                    class="mt-1
                                           text-sm
                                           text-red-600
                                           space-y-1">

                                    @foreach($errors->all() as $error)

                                        <li>
                                            {{ $error }}
                                        </li>

                                    @endforeach

                                </ul>

                            </div>

                        </div>

                    </div>

                @endif


                <div class="space-y-5">


                    <!-- Current Password -->

                    <div>

                        <label
                            for="current_password"
                            class="block
                                   text-sm
                                   font-semibold
                                   text-gray-700
                                   mb-2">

                            Current Password

                        </label>

                        <div class="relative">

                            <input
                                id="current_password"
                                name="current_password"
                                type="password"
                                required
                                autocomplete="current-password"
                                class="w-full
                                       bg-gray-50
                                       border border-gray-200
                                       rounded-xl
                                       px-4 py-3
                                       pr-12
                                       text-gray-800
                                       focus:bg-white
                                       focus:border-[#0E4C6B]
                                       focus:ring-2
                                       focus:ring-[#0E4C6B]/10
                                       outline-none
                                       transition">

                            <button
                                type="button"
                                onclick="toggleStudentPassword('current_password', this)"
                                class="absolute
                                       right-3
                                       top-1/2
                                       -translate-y-1/2
                                       p-1
                                       text-gray-400
                                       hover:text-gray-700">

                                <x-heroicon-o-eye
                                    class="w-5 h-5"/>

                            </button>

                        </div>

                    </div>


                    <!-- New Password -->

                    <div>

                        <label
                            for="password"
                            class="block
                                   text-sm
                                   font-semibold
                                   text-gray-700
                                   mb-2">

                            New Password

                        </label>

                        <div class="relative">

                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                minlength="8"
                                autocomplete="new-password"
                                class="w-full
                                       bg-gray-50
                                       border border-gray-200
                                       rounded-xl
                                       px-4 py-3
                                       pr-12
                                       text-gray-800
                                       focus:bg-white
                                       focus:border-[#0E4C6B]
                                       focus:ring-2
                                       focus:ring-[#0E4C6B]/10
                                       outline-none
                                       transition">

                            <button
                                type="button"
                                onclick="toggleStudentPassword('password', this)"
                                class="absolute
                                       right-3
                                       top-1/2
                                       -translate-y-1/2
                                       p-1
                                       text-gray-400
                                       hover:text-gray-700">

                                <x-heroicon-o-eye
                                    class="w-5 h-5"/>

                            </button>

                        </div>

                        <p
                            class="text-xs
                                   text-gray-400
                                   mt-2">

                            Use at least 8 characters.

                        </p>

                    </div>


                    <!-- Confirm Password -->

                    <div>

                        <label
                            for="password_confirmation"
                            class="block
                                   text-sm
                                   font-semibold
                                   text-gray-700
                                   mb-2">

                            Confirm New Password

                        </label>

                        <div class="relative">

                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                required
                                autocomplete="new-password"
                                class="w-full
                                       bg-gray-50
                                       border border-gray-200
                                       rounded-xl
                                       px-4 py-3
                                       pr-12
                                       text-gray-800
                                       focus:bg-white
                                       focus:border-[#0E4C6B]
                                       focus:ring-2
                                       focus:ring-[#0E4C6B]/10
                                       outline-none
                                       transition">

                            <button
                                type="button"
                                onclick="toggleStudentPassword('password_confirmation', this)"
                                class="absolute
                                       right-3
                                       top-1/2
                                       -translate-y-1/2
                                       p-1
                                       text-gray-400
                                       hover:text-gray-700">

                                <x-heroicon-o-eye
                                    class="w-5 h-5"/>

                            </button>

                        </div>

                    </div>

                </div>


                <!-- Modal Actions -->

                <div
                    class="mt-7
                           flex flex-col-reverse
                           sm:flex-row
                           sm:justify-end
                           gap-3">

                    <button
                        type="button"
                        @click="passwordModal = false"
                        class="w-full
                               sm:w-auto
                               px-5 py-3
                               rounded-xl
                               border border-gray-200
                               text-gray-700
                               font-semibold
                               hover:bg-gray-50
                               transition">

                        Cancel

                    </button>


                    <button
                        type="submit"
                        class="w-full
                               sm:w-auto
                               inline-flex
                               items-center
                               justify-center
                               gap-2
                               bg-[#0E2958]
                               hover:bg-[#0B2147]
                               text-white
                               px-5 py-3
                               rounded-xl
                               font-semibold
                               transition
                               shadow-sm">

                        <x-heroicon-o-check
                            class="w-5 h-5"/>

                        Update Password

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<!-- ============================================================= -->
<!-- PASSWORD TOGGLE -->
<!-- ============================================================= -->

<script>

function toggleStudentPassword(id, button)
{
    const input = document.getElementById(id);

    if (!input) {
        return;
    }

    if (input.type === 'password') {

        input.type = 'text';

        button.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke-width="1.5"
                 stroke="currentColor"
                 class="w-5 h-5">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M2.036 12.322a1.012 1.012 0 010-.644
                         C3.423 7.51 7.36 5 12 5
                         c4.64 0 8.577 2.51 9.964 6.678
                         .07.21.07.436 0 .644
                         C20.577 16.49 16.64 19 12 19
                         c-4.64 0-8.577-2.51-9.964-6.678z"/>

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M15 12a3 3 0 11-6 0
                         3 3 0 016 0z"/>

            </svg>
        `;

    } else {

        input.type = 'password';

        button.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke-width="1.5"
                 stroke="currentColor"
                 class="w-5 h-5">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M2.036 12.322a1.012 1.012 0 010-.644
                         C3.423 7.51 7.36 5 12 5
                         c4.64 0 8.577 2.51 9.964 6.678
                         .07.21.07.436 0 .644
                         C20.577 16.49 16.64 19 12 19
                         c-4.64 0-8.577-2.51-9.964-6.678z"/>

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M15 12a3 3 0 11-6 0
                         3 3 0 016 0z"/>

            </svg>
        `;

    }
}

</script>

@endsection