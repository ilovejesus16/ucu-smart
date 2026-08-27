<x-layouts.auth>

    <h2 class="text-3xl sm:text-4xl font-bold text-center text-[#0E2958]">
        Instructor Registration
    </h2>

    <p class="text-center text-sm sm:text-base text-gray-500 mt-2 mb-8">
        Create your UCU Smart+ instructor account.
    </p>

    <form method="POST" action="{{ route('register.instructor.store') }}">

        @csrf

        <!-- Employee ID -->

        <div class="mb-5">

            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Employee ID
            </label>

            <input
                type="text"
                name="employee_id"
                value="{{ old('employee_id') }}"
                required
                class="w-full rounded-xl border border-gray-300 px-4 sm:px-5 py-3 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

            @error('employee_id')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror

        </div>

        <!-- First & Last Name -->

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">

            <div>

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    First Name
                </label>

                <input
                    type="text"
                    name="first_name"
                    value="{{ old('first_name') }}"
                    required
                    class="w-full rounded-xl border border-gray-300 px-4 sm:px-5 py-3 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

                @error('first_name')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror

            </div>

            <div>

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Last Name
                </label>

                <input
                    type="text"
                    name="last_name"
                    value="{{ old('last_name') }}"
                    required
                    class="w-full rounded-xl border border-gray-300 px-4 sm:px-5 py-3 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

                @error('last_name')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror

            </div>

        </div>

        <!-- Department -->

        <div class="mb-5">

            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Department
            </label>

            <input
                type="text"
                name="department"
                value="{{ old('department') }}"
                required
                class="w-full rounded-xl border border-gray-300 px-4 sm:px-5 py-3 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

            @error('department')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror

        </div>

        <!-- Email -->

        <div class="mb-5">

            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Email Address
            </label>

            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                class="w-full rounded-xl border border-gray-300 px-4 sm:px-5 py-3 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

            @error('email')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror

        </div>

        <!-- Password -->

        <div class="mb-5">

            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Password
            </label>

            <input
                type="password"
                name="password"
                required
                class="w-full rounded-xl border border-gray-300 px-4 sm:px-5 py-3 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

            @error('password')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror

        </div>

        <!-- Confirm Password -->

        <div class="mb-7">

            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Confirm Password
            </label>

            <input
                type="password"
                name="password_confirmation"
                required
                class="w-full rounded-xl border border-gray-300 px-4 sm:px-5 py-3 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

        </div>

        <!-- Register Button -->

        <button
            type="submit"
            class="w-full bg-[#0E4C6B] hover:bg-[#0B3D56] text-white font-bold py-3 rounded-xl transition">

            REGISTER

        </button>

        <!-- Login Link -->

        <div class="mt-6 text-center">

            <p class="text-sm text-gray-500">

                Already have an account?

                <a
                    href="{{ route('login') }}"
                    class="font-semibold text-[#0E4C6B] hover:underline">

                    Login

                </a>

            </p>

        </div>

    </form>

</x-layouts.auth>