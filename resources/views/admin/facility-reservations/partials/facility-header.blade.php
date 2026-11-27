<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

    <div>
        <div class="flex items-center gap-2 mb-2">
            <a href="{{ route('admin.facility-reservations.index') }}"
               class="text-sm text-slate-500 hover:text-[#0E4C6B] transition">
                Facility Reservations
            </a>

            <span class="text-slate-300">/</span>

            <span class="text-sm font-medium text-slate-700">
                Manage Facilities
            </span>
        </div>

        <h1 class="text-3xl font-bold text-slate-800">
            Facility Management
        </h1>

        <p class="text-sm text-slate-500 mt-1">
            Manage campus facilities available for reservations.
        </p>
    </div>

    <div class="flex flex-wrap items-center gap-3">

        <a href="{{ route('admin.facility-reservations.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200
                  bg-white text-slate-700 text-sm font-semibold hover:bg-slate-50 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Reservations
        </a>

        @if(auth()->user()->hasAdminPermission('facility_reservations', 'full'))
            <button type="button"
                    onclick="openAddFacilityModal()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl
                           bg-[#0E4C6B] text-white text-sm font-semibold
                           hover:bg-[#0B3D56] transition shadow-sm">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4v16m8-8H4"/>
                </svg>

                Add Facility
            </button>
        @endif

    </div>
</div>
