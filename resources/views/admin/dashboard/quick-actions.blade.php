<div class="bg-white rounded-2xl shadow-sm border">

    <div class="px-6 py-5 border-b">

        <h2 class="text-xl font-bold text-gray-800">

            Quick Actions

        </h2>

        <p class="text-sm text-gray-500 mt-1">

            Frequently used administrator tools.

        </p>

    </div>

    <div class="p-6 grid grid-cols-2 gap-4">

        <!-- Import Schedule -->

        <a href="{{ route('admin.schedules') }}"
           class="group border rounded-2xl p-5 hover:border-blue-600 hover:bg-blue-50 transition">

            <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center mb-4 group-hover:bg-blue-600 transition">

                <x-heroicon-o-arrow-up-tray class="w-7 h-7 text-blue-600 group-hover:text-white"/>

            </div>

            <h3 class="font-bold text-gray-800">

                Import Schedule

            </h3>

            <p class="text-sm text-gray-500 mt-2">

                Upload registrar schedules.

            </p>

        </a>

        <!-- Users -->

        <a href="{{ route('admin.users') }}"
           class="group border rounded-2xl p-5 hover:border-green-600 hover:bg-green-50 transition">

            <div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center mb-4 group-hover:bg-green-600 transition">

                <x-heroicon-o-users class="w-7 h-7 text-green-600 group-hover:text-white"/>

            </div>

            <h3 class="font-bold text-gray-800">

                User Management

            </h3>

            <p class="text-sm text-gray-500 mt-2">

                Manage accounts.

            </p>

        </a>

        <!-- Buildings -->

        <a href="{{ route('buildings.index') }}"
           class="group border rounded-2xl p-5 hover:border-yellow-500 hover:bg-yellow-50 transition">

            <div class="w-14 h-14 rounded-2xl bg-yellow-100 flex items-center justify-center mb-4 group-hover:bg-yellow-500 transition">

                <x-heroicon-o-building-office-2 class="w-7 h-7 text-yellow-600 group-hover:text-white"/>

            </div>

            <h3 class="font-bold text-gray-800">

                Buildings

            </h3>

            <p class="text-sm text-gray-500 mt-2">

                Manage campus buildings.

            </p>

        </a>

        <!-- Rooms -->

        <a href="{{ route('rooms.index') }}"
           class="group border rounded-2xl p-5 hover:border-purple-600 hover:bg-purple-50 transition">

            <div class="w-14 h-14 rounded-2xl bg-purple-100 flex items-center justify-center mb-4 group-hover:bg-purple-600 transition">

                <x-heroicon-o-home-modern class="w-7 h-7 text-purple-600 group-hover:text-white"/>

            </div>

            <h3 class="font-bold text-gray-800">

                Rooms

            </h3>

            <p class="text-sm text-gray-500 mt-2">

                Manage classrooms.

            </p>

        </a>

    </div>

</div>