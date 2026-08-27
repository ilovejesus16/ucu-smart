<!DOCTYPE html>
<html lang="en">

<head>
<link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>@yield('title') | UCU Smart+</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

    <x-instructor-sidebar />

    <main class="flex-1 min-h-screen p-4 sm:p-6 lg:p-8 lg:ml-72">
        @yield('content')
    </main>

</div>

@stack('scripts')

</body>

</html>