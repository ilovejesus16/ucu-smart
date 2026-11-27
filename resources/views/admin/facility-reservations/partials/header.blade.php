<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

    <div>
        <h1 class="text-3xl font-bold text-slate-800">
            Facility Reservations
        </h1>

        <p class="mt-1 text-slate-500">
            Manage and monitor university facility reservations.
        </p>
    </div>

    <div class="flex flex-wrap items-center gap-3">

        <a
            href="{{ route('admin.facility-reservations.facilities') }}"
            class="inline-flex items-center gap-2 whitespace-nowrap rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-medium text-slate-700 hover:bg-slate-100 transition">

            <x-heroicon-o-building-office-2 class="w-5 h-5"/>

            Manage Facilities
        </a>

        @if(auth()->user()->hasAdminPermission('facility_reservations', 'full'))

            <button
                type="button"
                id="openAddReservationModal"
                class="inline-flex items-center gap-2 whitespace-nowrap rounded-xl bg-[#0E4C6B] px-5 py-3 text-sm font-medium text-white hover:bg-[#0B3D56] transition">

                <x-heroicon-o-plus class="w-5 h-5"/>

                Add Reservation
            </button>

        @endif

    </div>

</div>
