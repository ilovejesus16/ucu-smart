@php
    $totalFacilities = $facilities->count();
    $activeFacilities = $facilities->where('is_active', true)->count();
    $inactiveFacilities = $facilities->where('is_active', false)->count();
    $totalReservations = $facilities->sum('reservations_count');
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

    {{-- Total Facilities --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Facilities</p>
                <h2 class="text-3xl font-bold text-slate-800 mt-2">
                    {{ $totalFacilities }}
                </h2>
            </div>

            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                <svg class="w-6 h-6 text-[#0E4C6B]" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Active Facilities --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Active Facilities</p>
                <h2 class="text-3xl font-bold text-slate-800 mt-2">
                    {{ $activeFacilities }}
                </h2>
            </div>

            <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Inactive Facilities --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Inactive Facilities</p>
                <h2 class="text-3xl font-bold text-slate-800 mt-2">
                    {{ $inactiveFacilities }}
                </h2>
            </div>

            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v4m0 4h.01M10.3 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.7 3.86a2 2 0 00-3.4 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Recorded Reservations --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Recorded Reservations</p>
                <h2 class="text-3xl font-bold text-slate-800 mt-2">
                    {{ $totalReservations }}
                </h2>
            </div>

            <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14H4V6a1 1 0 011-1z"/>
                </svg>
            </div>
        </div>
    </div>

</div>
