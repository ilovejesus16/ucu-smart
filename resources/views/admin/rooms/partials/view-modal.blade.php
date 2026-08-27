<div
    id="viewRoomModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">

    <div class="w-full max-w-2xl rounded-2xl bg-white shadow-2xl overflow-hidden">

        <!-- Header -->

        <div class="bg-[#0E4C6B] px-6 py-5">

            <h2
                id="viewRoomName"
                class="text-2xl font-bold text-white">

                Room Name

            </h2>

            <p
                id="viewRoomNumber"
                class="mt-1 text-blue-100">

                Room Number

            </p>

        </div>

        <!-- Content -->

        <div class="p-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>

                    <label class="text-sm text-slate-500">
                        Building
                    </label>

                    <p
                        id="viewRoomBuilding"
                        class="mt-1 text-lg font-semibold text-slate-800">
                    </p>

                </div>

                <div>

                    <label class="text-sm text-slate-500">
                        Capacity
                    </label>

                    <p
                        id="viewRoomCapacity"
                        class="mt-1 text-lg font-semibold text-slate-800">
                    </p>

                </div>

                <div>

                    <label class="text-sm text-slate-500">
                        Floor
                    </label>

                    <p
                        id="viewRoomFloor"
                        class="mt-1 text-lg font-semibold text-slate-800">
                    </p>

                </div>

                <div>

                    <label class="text-sm text-slate-500">
                        Created
                    </label>

                    <p
                        id="viewRoomCreated"
                        class="mt-1 text-lg font-semibold text-slate-800">
                    </p>

                </div>

                <div class="md:col-span-2">

                    <label class="text-sm text-slate-500">
                        Last Updated
                    </label>

                    <p
                        id="viewRoomUpdated"
                        class="mt-1 text-lg font-semibold text-slate-800">
                    </p>

                </div>

            </div>

        </div>

        <!-- Footer -->

        <div class="border-t border-slate-200 p-6 flex justify-end">

            <button
                type="button"
                class="close-view-modal rounded-xl border border-slate-300 px-5 py-2.5 hover:bg-slate-100">

                Close

            </button>

        </div>

    </div>

</div>