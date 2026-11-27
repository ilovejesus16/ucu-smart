
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

    <form
        method="GET"
        action="{{ route('admin.facility-reservations.index') }}"
        class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">

        {{-- Search --}}
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-2">
                Search
            </label>

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search event or requesting office..."
                class="w-full rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">
        </div>

        {{-- Facility --}}
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-2">
                Facility
            </label>

            <select
                name="facility_id"
                class="w-full rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

                <option value="">All Facilities</option>

                @foreach($facilities as $facility)
                    <option
                        value="{{ $facility->id }}"
                        @selected(request('facility_id') == $facility->id)>
                        {{ $facility->name }}
                    </option>
                @endforeach

            </select>
        </div>

        {{-- Reservation Date --}}
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-2">
                Reservation Date
            </label>

            <input
                type="date"
                name="date"
                value="{{ request('date') }}"
                class="w-full rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">
        </div>

        {{-- Status --}}
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-2">
                Status
            </label>

            <select
                name="status"
                class="w-full rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

                <option value="">All Statuses</option>

                <option value="upcoming" @selected(request('status') === 'upcoming')>
                    Upcoming
                </option>

                <option value="ongoing" @selected(request('status') === 'ongoing')>
                    Ongoing
                </option>

                <option value="completed" @selected(request('status') === 'completed')>
                    Completed
                </option>

                <option value="cancelled" @selected(request('status') === 'cancelled')>
                    Cancelled
                </option>

            </select>
        </div>

        {{-- Actions --}}
        <div class="md:col-span-2 xl:col-span-4 flex justify-end gap-3">

            <a
                href="{{ route('admin.facility-reservations.index') }}"
                class="px-5 py-2.5 rounded-xl border border-slate-300 hover:bg-slate-100 transition">
                Reset
            </a>

            <button
                type="submit"
                class="px-5 py-2.5 rounded-xl bg-[#0E4C6B] text-white hover:bg-[#0B3D56] transition">
                Apply Filters
            </button>

        </div>

    </form>

</div>
