<div id="viewBuildingModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 overflow-hidden">

        <div class="p-6 border-b border-slate-200">

            <h2 class="text-2xl font-bold text-slate-800">
                Building Details
            </h2>

            <p class="mt-1 text-slate-500">
                View building information.
            </p>

        </div>

        <div class="p-6">

            <!-- Image -->

            <div class="flex justify-center mb-6">

                <img
                    id="viewBuildingImage"
                    src=""
                    class="w-32 h-32 rounded-2xl object-cover border border-slate-200 hidden">

                <div
                    id="viewBuildingPlaceholder"
                    class="w-32 h-32 rounded-2xl bg-[#0E4C6B]/10 flex items-center justify-center">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke-width="1.8"
                         stroke="currentColor"
                         class="w-14 h-14 text-[#0E4C6B]">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M3.75 21h16.5M5.25 21V7.5A2.25 2.25 0 0 1 7.5 5.25h9A2.25 2.25 0 0 1 18.75 7.5V21"/>

                    </svg>

                </div>

            </div>

            <div class="space-y-4">

                <div>

                    <label class="text-sm text-slate-500">
                        Building Name
                    </label>

                    <p
                        id="viewBuildingName"
                        class="font-semibold text-lg text-slate-800">
                    </p>

                </div>

                <div>

                    <label class="text-sm text-slate-500">
                        Total Rooms
                    </label>

                    <p
                        id="viewBuildingRooms"
                        class="font-semibold text-slate-800">
                    </p>

                </div>

                <div>

                    <label class="text-sm text-slate-500">
                        Created
                    </label>

                    <p
                        id="viewBuildingCreated"
                        class="font-semibold text-slate-800">
                    </p>

                </div>

                <div>

                    <label class="text-sm text-slate-500">
                        Last Updated
                    </label>

                    <p
                        id="viewBuildingUpdated"
                        class="font-semibold text-slate-800">
                    </p>

                </div>

            </div>

        </div>

        <div class="border-t border-slate-200 p-6 flex justify-end">

            <button
                type="button"
                class="close-view-building px-5 py-2.5 rounded-xl border border-slate-300 hover:bg-slate-100">

                Close

            </button>

        </div>

    </div>

</div>