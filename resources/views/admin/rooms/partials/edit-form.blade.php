<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- Building -->

    <div>

        <label class="block text-sm font-semibold text-slate-700 mb-2">
            Building <span class="text-red-500">*</span>
        </label>

        <select
            id="edit_building_id"
            name="building_id"
            required
            class="w-full rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

            <option value="">
                Select Building
            </option>

            @foreach($buildings as $building)

                <option value="{{ $building->id }}">

                    {{ $building->building_name }}

                </option>

            @endforeach

        </select>

    </div>

    <!-- Room Number -->

    <div>

        <label class="block text-sm font-semibold text-slate-700 mb-2">
            Room Number <span class="text-red-500">*</span>
        </label>

        <input
            id="edit_room_number"
            type="text"
            name="room_number"
            required
            placeholder="e.g. 301"
            class="w-full rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

    </div>

</div>

<!-- Room Name -->

<div class="mt-6">

    <label class="block text-sm font-semibold text-slate-700 mb-2">
        Room Name <span class="text-red-500">*</span>
    </label>

    <input
        id="edit_room_name"
        type="text"
        name="room_name"
        required
        placeholder="e.g. IT Laboratory 1"
        class="w-full rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

</div>

<!-- Capacity + Floor -->

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

    <!-- Capacity -->

    <div>

        <label class="block text-sm font-semibold text-slate-700 mb-2">
            Capacity
        </label>

        <input
            id="edit_capacity"
            type="number"
            min="1"
            name="capacity"
            class="w-full rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

    </div>

    <!-- Floor -->

    <div>

        <label class="block text-sm font-semibold text-slate-700 mb-2">
            Floor
        </label>

        <input
            id="edit_floor"
            type="number"
            min="1"
            name="floor"
            class="w-full rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

    </div>

</div>