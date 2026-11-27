
<div
    id="viewReservationModal"
    class="fixed inset-0 z-[70] hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4 py-6">

    <div class="w-full max-w-lg rounded-2xl bg-white shadow-2xl">

        <div class="flex items-center justify-between border-b border-slate-200 p-6">
            <h2 class="text-xl font-bold text-slate-800">
                Reservation Details
            </h2>

            <button type="button" class="close-reservation-modal text-slate-400 hover:text-slate-700 text-2xl">
                &times;
            </button>
        </div>

        <div class="p-6 space-y-4">

            <div>
                <p class="text-sm text-slate-500">Event</p>
                <p id="view_event" class="font-semibold text-slate-800"></p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Facility</p>
                <p id="view_facility" class="font-semibold text-slate-800"></p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-slate-500">Date</p>
                    <p id="view_date" class="font-medium text-slate-800"></p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Status</p>
                    <p id="view_status" class="font-medium text-slate-800"></p>
                </div>
            </div>

            <div>
                <p class="text-sm text-slate-500">Schedule</p>
                <p id="view_time" class="font-medium text-slate-800"></p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Requesting Office</p>
                <p id="view_office" class="font-medium text-slate-800"></p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Description</p>
                <p id="view_description" class="text-slate-700 whitespace-pre-line"></p>
            </div>

        </div>

        <div class="border-t border-slate-200 bg-slate-50 p-5 flex justify-end rounded-b-2xl">
            <button type="button"
                class="close-reservation-modal rounded-xl bg-[#0E4C6B] px-5 py-2.5 text-sm font-medium text-white hover:bg-[#0B3D56]">
                Close
            </button>
        </div>

    </div>
</div>
