<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <link
        rel="icon"
        type="image/png"
        href="{{ asset('images/favicon.png') }}">

    <title>@yield('title') | UCU Smart+</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>


<body class="bg-gray-100">

    <div class="min-h-screen">

        <!-- Visitor Header -->

        <header
            class="bg-[#1E3A8A]
                   text-white
                   shadow-lg">

            <div
                class="max-w-7xl
                       mx-auto
                       px-4 sm:px-6
                       py-4
                       flex items-center
                       justify-between">

                <div class="flex items-center gap-3">

                    <img
                        src="{{ asset('images/logo.png') }}"
                        alt="UCU Smart+"
                        class="w-10 h-10 object-contain">

                    <div>

                        <h1 class="font-bold text-lg">
                            UCU SMART+
                        </h1>

                        <p class="text-xs text-blue-200">
                            Visitor Portal
                        </p>

                    </div>

                </div>


                <a
                    href="{{ route('login') }}"
                    class="inline-flex
                           items-center
                           gap-2
                           bg-white/10
                           hover:bg-white/20
                           px-4 py-2
                           rounded-lg
                           text-sm
                           font-semibold
                           transition">

                    <x-heroicon-o-arrow-left-start-on-rectangle
                        class="w-4 h-4"/>

                    Login

                </a>

            </div>

        </header>


        <!-- Content -->

        <main class="p-4 sm:p-6 lg:p-8">

            @yield('content')

        </main>

    </div>

</body>

</html>