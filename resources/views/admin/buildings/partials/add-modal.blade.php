
@if(auth()->user()->hasAdminPermission('buildings', 'full'))
<div
    id="addBuildingModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm"
>
    <div class="flex max-h-[92vh] w-full max-w-2xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl">

        {{-- Header --}}
        <div class="flex shrink-0 items-center justify-between border-b border-slate-100 px-6 py-5">
            <div>
                <h2 class="text-xl font-bold text-slate-800">
                    Add Building
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Enter the building information below.
                </p>
            </div>

            <button
                type="button"
                class="close-add-modal rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                aria-label="Close modal"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Form --}}
        <form
            action="{{ route('buildings.store') }}"
            method="POST"
            enctype="multipart/form-data"
            id="addBuildingForm"
            class="flex min-h-0 flex-1 flex-col"
        >
            @csrf

            <div class="min-h-0 flex-1 space-y-6 overflow-y-auto px-6 py-6">

                {{-- Building Name --}}
                <div>
                    <label for="buildingName" class="mb-2 block text-sm font-semibold text-slate-700">
                        Building Name <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="building_name"
                        id="buildingName"
                        value="{{ old('building_name') }}"
                        required
                        maxlength="255"
                        placeholder="e.g. TEC Building"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-[#0E4C6B] focus:ring-2 focus:ring-[#0E4C6B]/20"
                    >
                </div>

                {{-- Description --}}
                <div>
                    <label for="buildingDescription" class="mb-2 block text-sm font-semibold text-slate-700">
                        Description
                    </label>

                    <textarea
                        name="description"
                        id="buildingDescription"
                        rows="4"
                        maxlength="2000"
                        placeholder="Describe the building and its purpose..."
                        class="w-full resize-y rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-[#0E4C6B] focus:ring-2 focus:ring-[#0E4C6B]/20"
                    >{{ old('description') }}</textarea>

                    <p class="mt-1.5 text-xs text-slate-500">
                        Provide a short description of this campus building.
                    </p>
                </div>

                {{-- Building Image --}}
                <div>
                    <label for="buildingImage" class="mb-2 block text-sm font-semibold text-slate-700">
                        Building Image
                    </label>

                    <input
                        type="file"
                        name="image"
                        id="buildingImage"
                        accept="image/*"
                        class="block w-full cursor-pointer rounded-xl border border-slate-300 text-sm text-slate-600 file:mr-4 file:cursor-pointer file:rounded-l-xl file:border-0 file:bg-[#0E4C6B] file:px-4 file:py-3 file:font-semibold file:text-white hover:file:bg-[#0B3D56]"
                    >

                    <p class="mt-1.5 text-xs text-slate-500">
                        Upload an image of the building. Supported formats depend on your server validation.
                    </p>
                </div>

            </div>

            {{-- Footer --}}
            <div class="flex shrink-0 items-center justify-end gap-3 border-t border-slate-200 bg-white px-6 py-4">

                <button
                    type="button"
                    class="close-add-modal rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="rounded-xl bg-[#0E4C6B] px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#0B3D56] focus:outline-none focus:ring-2 focus:ring-[#0E4C6B]/30"
                >
                    Save Building
                </button>

            </div>
        </form>
    </div>
</div>
@endif
