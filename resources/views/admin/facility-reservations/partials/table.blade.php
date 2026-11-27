<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    <div class="overflow-x-auto">
        <table class="min-w-full">

            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">
                        Event
                    </th>

                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">
                        Facility
                    </th>

                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">
                        Date & Time
                    </th>

                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">
                        Requesting Office
                    </th>

                    <th class="px-6 py-4 text-center text-sm font-semibold text-slate-700">
                        Status
                    </th>

                    <th class="px-6 py-4 text-center text-sm font-semibold text-slate-700">
                        Actions
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-200">

                @forelse($reservations as $reservation)

                    @php
                        $statusColor = match($reservation->display_status) {
                            'Upcoming' => 'bg-blue-100 text-blue-700',
                            'Ongoing' => 'bg-emerald-100 text-emerald-700',
                            'Completed' => 'bg-slate-100 text-slate-600',
                            'Cancelled' => 'bg-red-100 text-red-700',
                            default => 'bg-slate-100 text-slate-600',
                        };
                    @endphp

                    <tr class="hover:bg-slate-50 transition">

                        {{-- Event --}}
                        <td class="px-6 py-5">
                            <p class="font-semibold text-slate-800">
                                {{ $reservation->event_name }}
                            </p>

                            <p class="text-sm text-slate-500">
                                {{ \Illuminate\Support\Str::limit($reservation->description, 55) }}
                            </p>
                        </td>

                        {{-- Facility --}}
                        <td class="px-6 py-5">
                            <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                {{ $reservation->facility->name }}
                            </span>
                        </td>

                        {{-- Date and Time --}}
                        <td class="px-6 py-5">
                            <p class="font-medium text-slate-800">
                                {{ $reservation->reservation_date->format('M d, Y') }}
                            </p>

                            <p class="text-sm text-slate-500">
                                {{ \Carbon\Carbon::parse($reservation->start_time)->format('h:i A') }}
                                –
                                {{ \Carbon\Carbon::parse($reservation->end_time)->format('h:i A') }}
                            </p>
                        </td>

                        {{-- Requesting Office --}}
                        <td class="px-6 py-5 text-sm text-slate-700">
                            {{ $reservation->requesting_office }}
                        </td>

                        {{-- Dynamic Status --}}
                        <td class="px-6 py-5 text-center">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusColor }}">
                                {{ $reservation->display_status }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-5">
                            <div class="flex justify-center flex-wrap gap-2">

                                {{-- View --}}
                                <button
                                    type="button"
                                    class="view-reservation-btn rounded-lg bg-blue-600 px-3 py-2 text-sm text-white hover:bg-blue-700"
                                    data-event="{{ $reservation->event_name }}"
                                    data-facility="{{ $reservation->facility->name }}"
                                    data-date="{{ $reservation->reservation_date->format('F d, Y') }}"
                                    data-start="{{ \Carbon\Carbon::parse($reservation->start_time)->format('h:i A') }}"
                                    data-end="{{ \Carbon\Carbon::parse($reservation->end_time)->format('h:i A') }}"
                                    data-office="{{ $reservation->requesting_office }}"
                                    data-description="{{ $reservation->description }}"
                                    data-status="{{ $reservation->display_status }}">
                                    View
                                </button>

                                {{-- Edit and Cancel --}}
                                @if(auth()->user()->hasAdminPermission('facility_reservations', 'full')
                                    && $reservation->status === 'approved'
                                    && in_array($reservation->display_status, ['Upcoming', 'Ongoing']))

                                    <button
                                        type="button"
                                        class="edit-reservation-btn rounded-lg bg-green-600 px-3 py-2 text-sm text-white hover:bg-green-700"
                                        data-id="{{ $reservation->id }}"
                                        data-facility="{{ $reservation->facility_id }}"
                                        data-event="{{ $reservation->event_name }}"
                                        data-description="{{ $reservation->description }}"
                                        data-date="{{ $reservation->reservation_date->format('Y-m-d') }}"
                                        data-start="{{ substr($reservation->start_time, 0, 5) }}"
                                        data-end="{{ substr($reservation->end_time, 0, 5) }}"
                                        data-office="{{ $reservation->requesting_office }}">
                                        Edit
                                    </button>

                                    <form
                                        method="POST"
                                        action="{{ route('admin.facility-reservations.cancel', $reservation) }}"
                                        onsubmit="return confirm('Are you sure you want to cancel this reservation?')">

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="rounded-lg bg-red-600 px-3 py-2 text-sm text-white hover:bg-red-700">
                                            Cancel
                                        </button>

                                    </form>

                                @endif

                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-slate-500">
                            No facility reservations found.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>
    </div>

    {{-- Pagination --}}
    <div class="border-t border-slate-200 p-6">
        {{ $reservations->links() }}
    </div>

</div>
