<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

    <!-- Total Rooms -->

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

        <p class="text-sm text-slate-500">
            Total Rooms
        </p>

        <h2 class="mt-2 text-3xl font-bold text-slate-800">

            {{ $roomCount }}

        </h2>

    </div>

    <!-- Buildings -->

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

        <p class="text-sm text-slate-500">
            Buildings
        </p>

        <h2 class="mt-2 text-3xl font-bold text-slate-800">

            {{ $buildingCount }}

        </h2>

    </div>

    <!-- Total Capacity -->

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

        <p class="text-sm text-slate-500">
            Total Capacity
        </p>

        <h2 class="mt-2 text-3xl font-bold text-slate-800">

            {{ $capacityCount }}

        </h2>

    </div>

    <!-- Highest Floor -->

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

        <p class="text-sm text-slate-500">
            Highest Floor
        </p>

        <h2 class="mt-2 text-3xl font-bold text-slate-800">

            {{ $highestFloor }}

        </h2>

    </div>

</div>