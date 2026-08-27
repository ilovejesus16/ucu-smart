<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

    <form
        method="GET"
        action="{{ route('rooms.index') }}"
        class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <!-- Search -->

        <div>

            <label class="block text-sm font-medium text-slate-600 mb-2">
                Search
            </label>

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search room name or room number..."
                class="w-full rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

        </div>

        <!-- Building -->

        <div>

            <label class="block text-sm font-medium text-slate-600 mb-2">
                Building
            </label>

            <select
                name="building"
                class="w-full rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

                <option value="">
                    All Buildings
                </option>

                @foreach($buildings as $building)

                    <option
                        value="{{ $building->id }}"
                        @selected(request('building') == $building->id)>

                        {{ $building->building_name }}

                    </option>

                @endforeach

            </select>

        </div>

        <!-- Floor -->

        <div>

            <label class="block text-sm font-medium text-slate-600 mb-2">
                Floor
            </label>

            <select
                name="floor"
                class="w-full rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

                <option value="">
                    All Floors
                </option>

                @for($i = 1; $i <= 10; $i++)

                    <option
                        value="{{ $i }}"
                        @selected(request('floor') == $i)>

                        Floor {{ $i }}

                    </option>

                @endfor

            </select>

        </div>

        <!-- Buttons -->

        <div class="md:col-span-3 flex justify-end gap-3">

            <a
                href="{{ route('rooms.index') }}"
                class="px-5 py-2.5 rounded-xl border border-slate-300 hover:bg-slate-100">

                Reset

            </a>

            <button
                type="submit"
                class="px-5 py-2.5 rounded-xl bg-[#0E4C6B] text-white hover:bg-[#0B3D56]">

                Apply Filters

            </button>

        </div>

    </form>

</div>