
@if(auth()->user()->hasAdminPermission('buildings', 'full'))
<div
    id="editBuildingModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm"
>
    <div class="flex max-h-[92vh] w-full max-w-2xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl">

        {{-- Header --}}
        <div class="flex shrink-0 items-center justify-between border-b border-slate-100 px-6 py-5">
            <div>
                <h2 class="text-xl font-bold text-slate-800">
                    Edit Building
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Update the building information below.
                </p>
            </div>

            <button
                type="button"
                class="close-edit-building rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                aria-label="Close modal"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Form --}}
        <form
            id="editBuildingForm"
            method="POST"
            enctype="multipart/form-data"
            class="flex min-h-0 flex-1 flex-col"
        >
            @csrf
            @method('PUT')

            <div class="min-h-0 flex-1 space-y-6 overflow-y-auto px-6 py-6">

                {{-- Building Name --}}
                <div>
                    <label for="editBuildingName" class="mb-2 block text-sm font-semibold text-slate-700">
                        Building Name <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        id="editBuildingName"
                        name="building_name"
                        required
                        maxlength="255"
                        placeholder="Enter building name"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-[#0E4C6B] focus:ring-2 focus:ring-[#0E4C6B]/20"
                    >
                </div>

                {{-- Description --}}
                <div>
                    <label for="editBuildingDescription" class="mb-2 block text-sm font-semibold text-slate-700">
                        Description
                    </label>

                    <textarea
                        id="editBuildingDescription"
                        name="description"
                        rows="4"
                        maxlength="2000"
                        placeholder="Describe the building and its purpose..."
                        class="w-full resize-y rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-[#0E4C6B] focus:ring-2 focus:ring-[#0E4C6B]/20"
                    ></textarea>
                </div>

                {{-- Current Image --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Current Building Image
                    </label>

                    <div class="flex items-center gap-4 rounded-xl border border-slate-200 bg-slate-50 p-4">

                        <img
                            id="editBuildingPreview"
                            src=""
                            alt="Current building image"
                            class="hidden h-24 w-24 rounded-xl border border-slate-200 object-cover"
                        >

                        <div
                            id="editBuildingPlaceholder"
                            class="flex h-24 w-24 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="h-10 w-10 text-slate-400"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3.75 21h16.5M5.25 21V7.5A2.25 2.25 0 0 1 7.5 5.25h9A2.25 2.25 0 0 1 18.75 7.5V21"
                                />
                            </svg>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-slate-700">
                                Building preview
                            </p>
                            <p class="mt-1 text-xs text-slate-500">
                                The current image is displayed here.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Replace Image --}}
                <div>
                    <label for="editBuildingImage" class="mb-2 block text-sm font-semibold text-slate-700">
                        Replace Image
                    </label>

                    <input
                        type="file"
                        id="editBuildingImage"
                        name="image"
                        accept="image/*"
                        class="block w-full cursor-pointer rounded-xl border border-slate-300 text-sm text-slate-600 file:mr-4 file:cursor-pointer file:rounded-l-xl file:border-0 file:bg-[#0E4C6B] file:px-4 file:py-3 file:font-semibold file:text-white hover:file:bg-[#0B3D56]"
                    >

                    <p class="mt-1.5 text-xs text-slate-500">
                        Leave this empty to keep the existing building image.
                    </p>
                </div>

            </div>

            {{-- Footer --}}
            <div class="flex shrink-0 items-center justify-end gap-3 border-t border-slate-200 bg-white px-6 py-4">

                <button
                    type="button"
                    class="close-edit-building rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="rounded-xl bg-[#0E4C6B] px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#0B3D56] focus:outline-none focus:ring-2 focus:ring-[#0E4C6B]/30"
                >
                    Save Changes
                </button>

            </div>
        </form>
    </div>
</div>
@endif
