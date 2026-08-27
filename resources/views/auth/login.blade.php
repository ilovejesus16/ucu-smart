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

            <input
                type="password"
                name="password"
                required
                class="w-full rounded-xl border border-gray-300 px-4 sm:px-5 py-4 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

            @error('password')
                <p class="text-red-500 text-sm mt-2 break-words">
                    {{ $message }}
                </p>
            @enderror

        </div>

        <!-- Remember -->

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

        <!-- Divider -->

        <div class="flex items-center my-7">

            <div class="flex-1 h-px bg-gray-300"></div>

            <span class="px-4 text-gray-500">

                OR

            </span>

            <div class="flex-1 h-px bg-gray-300"></div>

        </div>

        <!-- Visitor -->

        <a
                href="{{ route('visitor.dashboard') }}"
            class="block w-full text-center border-2 border-[#0E4C6B] text-[#0E4C6B] font-semibold py-3 rounded-xl hover:bg-[#0E4C6B] hover:text-white transition">

            Continue as Visitor

        </a>

        <!-- Register -->

        <div class="mt-5 text-center">

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