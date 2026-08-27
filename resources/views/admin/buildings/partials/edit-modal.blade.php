<div id="editBuildingModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4">

        <div class="p-6 border-b border-slate-200">

            <h2 class="text-2xl font-bold text-slate-800">
                Edit Building
            </h2>

            <p class="mt-1 text-slate-500">
                Update the building information.
            </p>

        </div>

        <form
            id="editBuildingForm"
            method="POST"
            enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="p-6 space-y-5">

                <!-- Building Name -->

                <div>

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Building Name
                    </label>

                    <input
                        type="text"
                        id="editBuildingName"
                        name="building_name"
                        required
                        class="w-full rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

                </div>

                <!-- Current Image -->

                <div>

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Current Image
                    </label>

                    <img
                        id="editBuildingPreview"
                        src=""
                        class="w-28 h-28 rounded-xl object-cover border border-slate-200 hidden">

                    <div
                        id="editBuildingPlaceholder"
                        class="w-28 h-28 rounded-xl bg-slate-100 flex items-center justify-center">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke-width="1.8"
                             stroke="currentColor"
                             class="w-10 h-10 text-slate-400">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M3.75 21h16.5M5.25 21V7.5A2.25 2.25 0 0 1 7.5 5.25h9A2.25 2.25 0 0 1 18.75 7.5V21"/>

                        </svg>

                    </div>

                </div>

                <!-- Upload -->

                <div>

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Replace Image
                    </label>

                    <input
                        type="file"
                        name="image"
                        accept="image/*"
                        class="block w-full rounded-xl border border-slate-300 file:mr-4 file:rounded-lg file:border-0 file:bg-[#0E4C6B] file:px-4 file:py-2 file:text-white hover:file:bg-[#0B3D56]">

                </div>

            </div>

            <div class="border-t border-slate-200 p-6 flex justify-end gap-3">

                <button
                    type="button"
                    class="close-edit-building px-5 py-2.5 rounded-xl border border-slate-300 hover:bg-slate-100">

                    Cancel

                </button>

                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-xl bg-[#0E4C6B] text-white hover:bg-[#0B3D56]">

                    Save Changes

                </button>

            </div>

        </form>

    </div>

</div>