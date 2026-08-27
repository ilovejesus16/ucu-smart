<nav class="bg-white shadow px-8 py-5 flex justify-between items-center">

    <div>

        <h2 class="text-2xl font-bold text-gray-800">

            @yield('title')

        </h2>

    </div>

    <div class="text-gray-600">

        Welcome,

        <strong>

            {{ auth()->user()->first_name }}

        </strong>

    </div>

</nav>