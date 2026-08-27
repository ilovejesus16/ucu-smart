<div id="addBuildingModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4">

        <div class="p-6 border-b border-slate-200">

            <h2 class="text-2xl font-bold text-slate-800">
                Add Building
            </h2>

            <p class="mt-1 text-slate-500">
                Enter the building information below.
            </p>

        </div>

        <form
            action="{{ route('buildings.store') }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf

            <div class="p-6 space-y-5">

                <!-- Building Name -->

                <div>

                    <label class="block text-sm font-semibold text-slate-700 mb-2">

                        Building Name
                        <span class="text-red-500">*</span>

                    </label>

                    <input
                        type="text"
                        name="building_name"
                        required
                        class="w-full rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]"
                        placeholder="e.g. TEC Building">

                </div>

                <!-- Image -->

                <div>

                    <label class="block text-sm font-semibold text-slate-700 mb-2">

                        Building Image

                    </label>

                    <input
                        type="file"
                        name="image"
                        accept="image/*"
                        class="block w-full rounded-xl border border-slate-300 file:mr-4 file:rounded-lg file:border-0 file:bg-[#0E4C6B] file:px-4 file:py-2 file:text-white hover:file:bg-[#0B3D56]">

                </div>

            </div>

            <div class="flex justify-end gap-3 border-t border-slate-200 p-6">

                <button
                    type="button"
                    class="close-add-modal px-5 py-2.5 rounded-xl border border-slate-300 hover:bg-slate-100">

                    Cancel

                </button>

                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-xl bg-[#0E4C6B] text-white hover:bg-[#0B3D56]">

                    Save Building

                </button>

            </div>

        </form>

    </div>

</div>