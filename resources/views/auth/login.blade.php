
<x-layouts.auth>

    <h2 class="text-4xl sm:text-5xl font-bold text-center text-[#0E2958]">
        Welcome!
    </h2>

    <p class="text-center text-sm sm:text-base text-gray-500 mt-3 mb-8">
        Please log in to your access account.
    </p>

    @if(session('success'))
    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4">
        <p class="text-sm font-medium text-green-700">
            {{ session('success') }}
        </p>
    </div>
    @endif

    @if ($errors->any())
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">
        @foreach ($errors->all() as $error)
            <p class="text-sm text-red-700 break-words">{{ $error }}</p>
        @endforeach
    </div>
    @endif

    <x-auth-session-status
        class="mb-4"
        :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Username -->
        <div class="mb-5">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Username
            </label>

            <input
                type="text"
                name="username"
                value="{{ old('username') }}"
                required
                autofocus
                class="w-full rounded-xl border border-gray-300 px-4 sm:px-5 py-4 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

            @error('username')
                <p class="text-red-500 text-sm mt-2 break-words">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Password -->
<div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">
        Password
    </label>

    <div class="relative">
        <input
            id="password"
            type="password"
            name="password"
            required
            class="w-full rounded-xl border border-gray-300 px-4 sm:px-5 py-4 pr-12 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

        <button
            type="button"
            onclick="togglePasswordVisibility('password', 'password-toggle-icon')"
            class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-400 hover:text-[#0E4C6B]"
            aria-label="Show password">

            <svg id="password-toggle-icon"
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="1.8">

                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 12s3.5-7 9.75-7 9.75 7 9.75 7-3.5 7-9.75 7-9.75-7-9.75-7z"/>

                <circle cx="12" cy="12" r="3"/>
            </svg>
        </button>
    </div>

    @error('password')
        <p class="text-red-500 text-sm mt-2 break-words">
            {{ $message }}
        </p>
    @enderror
</div>

        <!-- Remember Me / Forgot Password -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mt-5">
            <label class="flex items-center gap-2">
                <input
                    type="checkbox"
                    name="remember"
                    class="rounded">

                <span class="text-sm text-gray-600">
                    Remember Me
                </span>
            </label>

            @if(Route::has('password.request'))
                <a
                    href="{{ route('password.request') }}"
                    class="text-sm text-[#0E4C6B] font-medium hover:underline">
                    Forgot Password?
                </a>
            @endif
        </div>

        <!-- Login -->
        <button
            type="submit"
            class="w-full mt-8 bg-[#0E4C6B] hover:bg-[#0B3D56] text-white font-bold py-4 rounded-xl transition">
            LOGIN
        </button>

        <!-- Registration -->
        <div class="mt-7 text-center">
            <p class="text-sm text-gray-500">
                Don't have an account?
            </p>

            <div class="mt-2 flex flex-col sm:flex-row justify-center items-center gap-2 sm:gap-3 text-sm">
                <a
                    href="{{ route('register.student') }}"
                    class="font-semibold text-[#0E4C6B] hover:underline">
                    Student
                </a>

                <span class="hidden sm:inline text-gray-400">
                    |
                </span>

                <a
                    href="{{ route('register.instructor') }}"
                    class="font-semibold text-[#0E4C6B] hover:underline">
                    Instructor
                </a>
            </div>
        </div>

    </form>

</x-layouts.auth>
<script>
    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (input.type === 'password') {
            input.type = 'text';

            icon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 3l18 18M10.58 10.59a2 2 0 002.83 2.83M9.88 5.09A10.94 10.94 0 0112 4.88c6.25 0 9.75 7.12 9.75 7.12a16.5 16.5 0 01-3.1 3.9M6.23 6.23C3.7 7.85 2.25 12 2.25 12s3.5 7.12 9.75 7.12c1.3 0 2.47-.3 3.5-.78"/>
            `;

        } else {
            input.type = 'password';

            icon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 12s3.5-7 9.75-7 9.75 7 9.75 7-3.5 7-9.75 7-9.75-7-9.75-7z"/>
                <circle cx="12" cy="12" r="3"/>
            `;
        }
    }
</script>