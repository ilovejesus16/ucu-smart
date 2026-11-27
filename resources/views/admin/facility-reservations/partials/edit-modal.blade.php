
<div
    id="editReservationModal"
    class="fixed inset-0 z-[70] hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4 py-6">

    <div class="w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-2xl bg-white shadow-2xl">

        <div class="flex items-center justify-between border-b border-slate-200 p-6">
            <h2 class="text-xl font-bold text-slate-800">
                Edit Reservation
            </h2>

            <button type="button" class="close-reservation-modal text-slate-400 hover:text-slate-700 text-2xl">
                &times;
            </button>
        </div>

        <form id="editReservationForm" method="POST" class="p-6 space-y-5">

            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">
                    Facility
                </label>

                <select id="edit_facility_id" name="facility_id" required
                    class="w-full rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

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

                <input id="edit_event_name" type="text" name="event_name" required maxlength="255"
                    class="w-full rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">
                    Description
                </label>

                <textarea id="edit_description" name="description" rows="3"
                    class="w-full rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">
                        Reservation Date
                    </label>

                    <input id="edit_reservation_date" type="date" name="reservation_date" required
                        class="w-full rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">
                        Requesting Office
                    </label>

                    <input id="edit_requesting_office" type="text" name="requesting_office" required maxlength="255"
                        class="w-full rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">
                        Start Time
                    </label>

                    <input id="edit_start_time" type="time" name="start_time" required
                        class="w-full rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">
                        End Time
                    </label>

                    <input id="edit_end_time" type="time" name="end_time" required
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
                    Update Reservation
                </button>

            </div>

        </form>

    </div>
</div>
