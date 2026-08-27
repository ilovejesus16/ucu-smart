<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-6 gap-6">

    <!-- Students -->

    <div class="bg-white rounded-2xl shadow-sm border hover:shadow-lg transition">

        <div class="p-6">

            <div class="flex justify-between items-start">

                <div>

                    <p class="text-gray-500 text-sm">
                        Students
                    </p>

                    <h2 class="text-4xl font-bold mt-3 text-gray-800">
                        {{ number_format($students) }}
                    </h2>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center">

                    <x-heroicon-o-academic-cap class="w-8 h-8 text-blue-600"/>

                </div>

            </div>

            <div class="mt-5 pt-4 border-t">

                <span class="text-sm text-gray-500">

                    Registered Students

                </span>

            </div>

        </div>

    </div>

    <!-- Instructors -->

    <div class="bg-white rounded-2xl shadow-sm border hover:shadow-lg transition">

        <div class="p-6">

            <div class="flex justify-between items-start">

                <div>

                    <p class="text-gray-500 text-sm">

                        Instructors

                    </p>

                    <h2 class="text-4xl font-bold mt-3 text-gray-800">

                        {{ number_format($instructors) }}

                    </h2>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center">

                    <x-heroicon-o-user-group class="w-8 h-8 text-green-600"/>

                </div>

            </div>

            <div class="mt-5 pt-4 border-t">

                <span class="text-sm text-gray-500">

                    Active Faculty

                </span>

            </div>

        </div>

    </div>

    <!-- Buildings -->

    <div class="bg-white rounded-2xl shadow-sm border hover:shadow-lg transition">

        <div class="p-6">

            <div class="flex justify-between items-start">

                <div>

                    <p class="text-gray-500 text-sm">

                        Buildings

                    </p>

                    <h2 class="text-4xl font-bold mt-3 text-gray-800">

                        {{ number_format($buildings) }}

                    </h2>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-yellow-100 flex items-center justify-center">

                    <x-heroicon-o-building-office-2 class="w-8 h-8 text-yellow-600"/>

                </div>

            </div>

            <div class="mt-5 pt-4 border-t">

                <span class="text-sm text-gray-500">

                    Campus Buildings

                </span>

            </div>

        </div>

    </div>

    <!-- Rooms -->

    <div class="bg-white rounded-2xl shadow-sm border hover:shadow-lg transition">

        <div class="p-6">

            <div class="flex justify-between items-start">

                <div>

                    <p class="text-gray-500 text-sm">

                        Rooms

                    </p>

                    <h2 class="text-4xl font-bold mt-3 text-gray-800">

                        {{ number_format($rooms) }}

                    </h2>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-purple-100 flex items-center justify-center">

                    <x-heroicon-o-home-modern class="w-8 h-8 text-purple-600"/>

                </div>

            </div>

            <div class="mt-5 pt-4 border-t">

                <span class="text-sm text-gray-500">

                    Total Classrooms

                </span>

            </div>

        </div>

    </div>

    <!-- Today's Classes -->

    <div class="bg-white rounded-2xl shadow-sm border hover:shadow-lg transition">

        <div class="p-6">

            <div class="flex justify-between items-start">

                <div>

                    <p class="text-gray-500 text-sm">

                        Today's Classes

                    </p>

                    <h2 class="text-4xl font-bold mt-3 text-gray-800">

                        {{ number_format($todayClasses) }}

                    </h2>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-red-100 flex items-center justify-center">

                    <x-heroicon-o-calendar-days class="w-8 h-8 text-red-600"/>

                </div>

            </div>

            <div class="mt-5 pt-4 border-t">

                <span class="text-sm text-gray-500">

                    Scheduled Today

                </span>

            </div>

        </div>

    </div>

    <!-- Available Rooms -->

    <div class="bg-white rounded-2xl shadow-sm border hover:shadow-lg transition">

        <div class="p-6">

            <div class="flex justify-between items-start">

                <div>

                    <p class="text-gray-500 text-sm">

                        Available Rooms

                    </p>

                    <h2 class="text-4xl font-bold mt-3 text-green-600">

                        {{ number_format($availableRooms) }}

                    </h2>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-emerald-100 flex items-center justify-center">

                    <x-heroicon-o-check-badge class="w-8 h-8 text-emerald-600"/>

                </div>

            </div>

            <div class="mt-5 pt-4 border-t">

                <span class="text-sm text-gray-500">

                    Currently Available

                </span>

            </div>

        </div>

    </div>

</div>