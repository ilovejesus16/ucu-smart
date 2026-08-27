<div class="bg-white rounded-2xl border border-slate-200 shadow-sm">

    <div class="overflow-x-auto lg:overflow-visible">

        <table class="min-w-full divide-y divide-slate-200">

            <thead class="bg-slate-50">

                <tr>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                        Building
                    </th>

                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-600">
                        Rooms
                    </th>

                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-600">
                        Created
                    </th>

                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-600">
                        Actions
                    </th>

                </tr>

            </thead>

            <tbody
                id="buildingTable"
                class="divide-y divide-slate-100">

            @forelse($buildings as $building)

                <tr class="hover:bg-slate-50 transition">

                    <!-- Building -->

                    <td class="px-6 py-5">

                        <div class="flex items-center gap-4">

                            @if($building->image)

                                <img
                                    src="{{ asset('storage/'.$building->image) }}"
                                    class="w-16 h-16 rounded-xl object-cover border border-slate-200">

                            @else

                                <div class="w-16 h-16 rounded-xl bg-[#0E4C6B]/10 flex items-center justify-center">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke-width="1.8"
                                         stroke="currentColor"
                                         class="w-8 h-8 text-[#0E4C6B]">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M3.75 21h16.5M5.25 21V7.5A2.25 2.25 0 0 1 7.5 5.25h9A2.25 2.25 0 0 1 18.75 7.5V21M9 9h.008v.008H9V9Zm0 3h.008v.008H9V12Zm0 3h.008v.008H9V15Zm6-6h.008v.008H15V9Zm0 3h.008v.008H15V12Zm0 3h.008v.008H15V15Z"/>

                                    </svg>

                                </div>

                            @endif

                            <div>

                                <h3 class="font-semibold text-slate-800">

                                    {{ $building->building_name }}

                                </h3>

                                <p class="text-sm text-slate-500">

                                    Campus Building

                                </p>

                            </div>

                        </div>

                    </td>

                    <!-- Rooms -->

                    <td class="px-6 py-5 text-center">

                        <span class="inline-flex items-center rounded-full bg-blue-100 text-blue-700 px-3 py-1 text-xs font-semibold">

                            {{ $building->rooms_count }} Rooms

                        </span>

                    </td>

                    <!-- Created -->

                    <td class="px-6 py-5 text-center text-slate-500">

                        {{ $building->created_at->format('M d, Y') }}

                    </td>

                    <!-- Actions -->

                    <td class="px-6 py-5 text-center">

                        @include('admin.buildings.partials.actions')

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="4" class="py-16 text-center">

                        <div class="flex flex-col items-center">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke-width="1.8"
                                 stroke="currentColor"
                                 class="w-12 h-12 text-slate-300">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M3.75 21h16.5M5.25 21V7.5A2.25 2.25 0 0 1 7.5 5.25h9A2.25 2.25 0 0 1 18.75 7.5V21"/>

                            </svg>

                            <h3 class="mt-4 text-lg font-semibold text-slate-700">

                                No Buildings Found

                            </h3>

                            <p class="mt-1 text-slate-500">

                                Add your first campus building to get started.

                            </p>

                        </div>

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    @if($buildings->hasPages())

        <div class="border-t border-slate-200 p-5">

            {{ $buildings->links() }}

        </div>

    @endif

</div>