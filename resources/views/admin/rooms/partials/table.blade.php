<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    <div class="overflow-x-auto">

        <table class="min-w-full">

            <thead class="bg-slate-50 border-b border-slate-200">

                <tr>

                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">
                        Room
                    </th>

                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">
                        Building
                    </th>

                    <th class="px-6 py-4 text-center text-sm font-semibold text-slate-700">
                        Capacity
                    </th>

                    <th class="px-6 py-4 text-center text-sm font-semibold text-slate-700">
                        Floor
                    </th>

                    <th class="px-6 py-4 text-center text-sm font-semibold text-slate-700">
                        Actions
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-slate-200">

            @forelse($rooms as $room)

                <tr class="hover:bg-slate-50 transition">

                    <!-- Room -->

                    <td class="px-6 py-5">

                        <p class="font-semibold text-slate-800">

                            {{ $room->room_name }}

                        </p>

                        <p class="text-sm text-slate-500">

                            Room {{ $room->room_number }}

                        </p>

                    </td>

                    <!-- Building -->

                    <td class="px-6 py-5">

                        <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">

                            {{ $room->building->building_name }}

                        </span>

                    </td>

                    <!-- Capacity -->

                    <td class="px-6 py-5 text-center">

                        {{ $room->capacity }}

                    </td>

                    <!-- Floor -->

                    <td class="px-6 py-5 text-center">

                        Floor {{ $room->floor }}

                    </td>

                    <!-- Actions -->

                    <td class="px-6 py-5">

                        <div class="flex justify-center gap-2">

                            <button
                                class="view-room-btn rounded-lg bg-blue-600 px-3 py-2 text-sm text-white hover:bg-blue-700"
                                data-id="{{ $room->id }}">

                                View

                            </button>

                            <button
                                class="edit-room-btn rounded-lg bg-green-600 px-3 py-2 text-sm text-white hover:bg-green-700"
                                data-id="{{ $room->id }}">

                                Edit

                            </button>

                            <button
                                class="delete-room-btn rounded-lg bg-red-600 px-3 py-2 text-sm text-white hover:bg-red-700"
                                data-id="{{ $room->id }}"
                                data-name="{{ $room->room_name }}">

                                Delete

                            </button>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="5"
                        class="px-6 py-16 text-center text-slate-500">

                        No rooms found.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    <div class="border-t border-slate-200 p-6">

        {{ $rooms->links() }}

    </div>

</div>