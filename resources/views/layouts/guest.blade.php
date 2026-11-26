
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <title>Account Activation | UCU Smart+</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900">

    <div class="relative min-h-screen flex flex-col items-center justify-center overflow-hidden bg-[#F4F7F9] px-4 py-10 sm:px-6">

        <!-- Background Decoration -->
        <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-[#0E4C6B]/5 blur-3xl"></div>
        <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full bg-[#0E4C6B]/5 blur-3xl"></div>

        <!-- Main Content -->
        <main class="relative z-10 w-full max-w-md">

            <!-- Branding -->
            <div class="text-center mb-8">
                <a href="/" class="inline-flex flex-col items-center group">

                    <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-[#0E4C6B] shadow-lg shadow-[#0E4C6B]/20 transition-transform group-hover:scale-105">
                        <span class="text-2xl font-extrabold tracking-tight text-white">
                            U+
                        </span>
                    </div>

                    <h1 class="mt-4 text-2xl font-extrabold tracking-tight text-[#0E4C6B]">
                        UCU Smart<span class="text-[#3A91B5]">+</span>
                    </h1>

                    <p class="mt-1 text-[10px] font-semibold tracking-[0.2em] text-gray-400 uppercase">
                        Urdaneta City University
                    </p>

                </a>
            </div>

            <!-- Card -->
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-xl shadow-gray-200/60 sm:p-8">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="mt-7 text-center">
                <p class="text-xs text-gray-400">
                    &copy; {{ date('Y') }} UCU Smart+. All rights reserved.
                </p>
                <p class="mt-1 text-[11px] text-gray-400">
                    Urdaneta City University
                </p>
            </div>

        </main>
    </div>

</body>
</html>
