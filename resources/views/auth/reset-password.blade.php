
<x-guest-layout>
    <div class="mb-7">
        <div class="inline-flex items-center gap-2 rounded-full bg-[#E8F1F5] px-3 py-1.5 mb-4">
            <span class="h-2 w-2 rounded-full bg-[#0E4C6B]"></span>
            <span class="text-xs font-semibold tracking-wider text-[#0E4C6B] uppercase">
                Account Activation
            </span>
        </div>

        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">
            Welcome to UCU Smart+!
        </h1>

        <p class="mt-2 text-sm leading-6 text-gray-500">
            Your account has been created successfully.
            Set up your password below to activate your account and get started.
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email -->
        <div>
            <x-input-label for="email" value="Username / Email Address"
                class="!text-sm !font-semibold !text-gray-700" />

            <x-text-input
                id="email"
                class="block mt-2 w-full !rounded-xl !border-gray-200 !bg-gray-50 !py-3 focus:!border-[#0E4C6B] focus:!ring-[#0E4C6B]"
                type="email"
                name="email"
                :value="old('email', $request->email)"
                required
                readonly
                autocomplete="username"
            />

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Create Password"
                class="!text-sm !font-semibold !text-gray-700" />

            <x-text-input
                id="password"
                class="block mt-2 w-full !rounded-xl !border-gray-200 !py-3 focus:!border-[#0E4C6B] focus:!ring-[#0E4C6B]"
                type="password"
                name="password"
                required
                autofocus
                autocomplete="new-password"
                placeholder="Enter your password"
            />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" value="Confirm Password"
                class="!text-sm !font-semibold !text-gray-700" />

            <x-text-input
                id="password_confirmation"
                class="block mt-2 w-full !rounded-xl !border-gray-200 !py-3 focus:!border-[#0E4C6B] focus:!ring-[#0E4C6B]"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Re-enter your password"
            />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Password Information -->
        <div class="rounded-xl bg-[#F0F6F8] border border-[#DCE9EE] p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-[#0E4C6B] mt-0.5 shrink-0"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 11c0-1.1.9-2 2-2s2 .9 2 2v2m-4-2v2m-6 7h12a2 2 0 002-2v-7a2 2 0 00-2-2H6a2 2 0 00-2 2v7a2 2 0 002 2z" />
                </svg>

                <div>
                    <p class="text-sm font-semibold text-[#0E4C6B]">
                        Keep your account secure
                    </p>
                    <p class="mt-1 text-xs leading-5 text-gray-600">
                        Choose a strong password that you haven't used elsewhere.
                        Keep your login credentials private.
                    </p>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <button
            type="submit"
            class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-[#0E4C6B] px-5 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#0A3B54] focus:outline-none focus:ring-2 focus:ring-[#0E4C6B] focus:ring-offset-2">
            Activate My Account

            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2" d="M5 12h14m-7-7l7 7-7 7" />
            </svg>
        </button>
    </form>

    <div class="mt-7 pt-5 border-t border-gray-100 text-center">
        <p class="text-xs text-gray-400">
            By activating your account, you can access UCU Smart+ services.
        </p>
    </div>
</x-guest-layout>
