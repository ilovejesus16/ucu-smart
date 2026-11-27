
<div
    id="addReservationModal"
    class="fixed inset-0 z-[70] hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4 py-6">

    <div class="w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-2xl bg-white shadow-2xl">

        <div class="flex items-center justify-between border-b border-slate-200 p-6">
            <div>
                <h2 class="text-xl font-bold text-slate-800">
                    Add Facility Reservation
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Enter the event and reservation details.
                </p>
            </div>

            <button type="button" class="close-reservation-modal text-slate-400 hover:text-slate-700 text-2xl">
                &times;
            </button>
        </div>

        <form method="POST" action="{{ route('admin.facility-reservations.store') }}" class="p-6 space-y-5">

            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">
                    Facility
                </label>

                <select name="facility_id" required
                    class="w-full rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

                    <option value="">Select Facility</option>

                    @foreach($facilities as $facility)
                        <option value="{{ $facility->id }}">
                            {{ $facility->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">
                    Event Name
                </label>

                <input type="text" name="event_name" required maxlength="255"
                    placeholder="Enter event name"
                    class="w-full rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">
                    Description
                </label>

                <textarea name="description" rows="3"
                    placeholder="Additional event details..."
                    class="w-full rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">
                        Reservation Date
                    </label>

                    <input type="date" name="reservation_date" required
                        class="w-full rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">
                        Requesting Office
                    </label>

                    <input type="text" name="requesting_office" required maxlength="255"
                        placeholder="e.g. Office of Student Affairs"
                        class="w-full rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">
                        Start Time
                    </label>

                    <input type="time" name="start_time" required
                        class="w-full rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">
                        End Time
                    </label>

                    <input type="time" name="end_time" required
                        class="w-full rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">
                </div>

            </div>

            <div class="flex justify-end gap-3 border-t border-slate-200 pt-5">

                <button type="button"
                    class="close-reservation-modal rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100">
                    Cancel
                </button>

                <button type="submit"
                    class="rounded-xl bg-[#0E4C6B] px-5 py-2.5 text-sm font-medium text-white hover:bg-[#0B3D56]">
                    Save Reservation
                </button>

            </div>

        </form>

    </div>
</div>
