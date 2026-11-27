<div id="addFacilityModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">

    <div class="bg-white w-full max-w-xl rounded-2xl shadow-xl overflow-hidden">

        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-200">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Add Facility</h2>
                <p class="text-sm text-slate-500 mt-1">Register a new campus facility.</p>
            </div>

            <button type="button" onclick="closeAddFacilityModal()"
                    class="text-slate-400 hover:text-slate-700 text-2xl leading-none">
                &times;
            </button>
        </div>

        <form method="POST" action="{{ route('admin.facility-reservations.facilities.store') }}">
            @csrf

            <div class="p-6 space-y-4">

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Facility Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" required maxlength="255"
                           value="{{ old('name') }}"
                           placeholder="e.g. Gymnasium"
                           class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm
                                  focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Location
                    </label>
                    <input type="text" name="location" maxlength="255"
                           value="{{ old('location') }}"
                           placeholder="e.g. Main Campus"
                           class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm
                                  focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Capacity
                    </label>
                    <input type="number" name="capacity" min="1"
                           value="{{ old('capacity') }}"
                           placeholder="Maximum number of people"
                           class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm
                                  focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Description
                    </label>
                    <textarea name="description" rows="3" maxlength="2000"
                              placeholder="Describe this facility..."
                              class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm
                                     focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">{{ old('description') }}</textarea>
                </div>

            </div>

            <div class="flex justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-200">
                <button type="button" onclick="closeAddFacilityModal()"
                        class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700
                               text-sm font-semibold hover:bg-slate-100 transition">
                    Cancel
                </button>

                <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-[#0E4C6B] text-white
                               text-sm font-semibold hover:bg-[#0B3D56] transition">
                    Save Facility
                </button>
            </div>

        </form>
    </div>
</div>
