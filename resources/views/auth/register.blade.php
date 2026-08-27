<x-guest-layout>

    <div class="text-center">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
            Create an Account
        </h1>

        <p class="mt-2 text-sm sm:text-base text-gray-500">
            Select your account type.
        </p>
    </div>

    <div class="mt-8 space-y-4">

        <a href="{{ route('register.student') }}"
           class="block w-full rounded-xl border p-5 sm:p-6 hover:bg-gray-100 transition">

            <h2 class="text-lg sm:text-xl font-semibold break-words">
                🎓 Student
            </h2>

            <p class="mt-1 text-sm sm:text-base text-gray-500">
                Register using your Student ID.
            </p>

        </a>

        <a href="{{ route('register.instructor') }}"
           class="block w-full rounded-xl border p-5 sm:p-6 hover:bg-gray-100 transition">

            <h2 class="text-lg sm:text-xl font-semibold break-words">
                👨‍🏫 Instructor
            </h2>

            <p class="mt-1 text-sm sm:text-base text-gray-500">
                Register using your Employee ID.
            </p>

        </a>

    </div>

    <div class="mt-6 text-center">

        <a href="{{ route('login') }}"
           class="text-blue-600 hover:underline break-words">

            Already have an account? Login

        </a>

    </div>

</x-guest-layout>