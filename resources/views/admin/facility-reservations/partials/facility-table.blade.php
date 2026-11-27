<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

    <div class="p-5 border-b border-slate-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Campus Facilities</h2>
                <p class="text-sm text-slate-500 mt-1">
                    All facilities registered in the system.
                </p>
            </div>

            <span class="text-sm text-slate-500">
                {{ $facilities->count() }} {{ Str::plural('facility', $facilities->count()) }}
            </span>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[850px] text-sm text-left">

            <thead class="bg-slate-50 text-slate-600 uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-6 py-4 font-semibold">Facility</th>
                    <th class="px-6 py-4 font-semibold">Location</th>
                    <th class="px-6 py-4 font-semibold">Capacity</th>
                    <th class="px-6 py-4 font-semibold">Reservations</th>
                    <th class="px-6 py-4 font-semibold">Status</th>

                    @if(auth()->user()->hasAdminPermission('facility_reservations', 'full'))
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    @endif
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">

                @forelse($facilities as $facility)
                    <tr class="hover:bg-slate-50 transition">

                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-800">
                                {{ $facility->name }}
                            </div>

                            @if($facility->description)
                                <div class="text-xs text-slate-500 mt-1 max-w-xs truncate">
                                    {{ $facility->description }}
                                </div>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-slate-600">
                            {{ $facility->location ?: 'Not specified' }}
                        </td>

                        <td class="px-6 py-4 text-slate-600">
                            {{ $facility->capacity ? number_format($facility->capacity) : 'Not specified' }}
                        </td>

                        <td class="px-6 py-4 text-slate-600">
                            {{ $facility->reservations_count ?? 0 }}
                        </td>

                        <td class="px-6 py-4">
                            @if($facility->is_active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-2"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-2"></span>
                                    Inactive
                                </span>
                            @endif
                        </td>

                        @if(auth()->user()->hasAdminPermission('facility_reservations', 'full'))
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">

                                    <button type="button"
                                            data-facility="{{ $facility->toJson() }}"
                                            onclick="openEditFacilityModal(this)"
                                            class="px-3 py-2 rounded-lg text-xs font-semibold
                                                   text-[#0E4C6B] bg-blue-50 hover:bg-blue-100 transition">
                                        Edit
                                    </button>

                                    <form method="POST"
                                          action="{{ route('admin.facility-reservations.facilities.toggle-status', $facility) }}"
                                          onsubmit="return confirm('Are you sure you want to {{ $facility->is_active ? 'deactivate' : 'activate' }} this facility?')">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                                class="px-3 py-2 rounded-lg text-xs font-semibold transition
                                                {{ $facility->is_active
                                                    ? 'bg-red-50 text-red-600 hover:bg-red-100'
                                                    : 'bg-green-50 text-green-700 hover:bg-green-100' }}">
                                            {{ $facility->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>

                                </div>
                            </td>
                        @endif

                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->hasAdminPermission('facility_reservations', 'full') ? 6 : 5 }}"
                            class="px-6 py-12 text-center">

                            <div class="flex flex-col items-center">
                                <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                                    <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/>
                                    </svg>
                                </div>

                                <p class="font-semibold text-slate-700">No facilities found</p>
                                <p class="text-sm text-slate-500 mt-1">
                                    Add a facility to make it available for reservations.
                                </p>
                            </div>

                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>

</div>
