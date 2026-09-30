<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

    <div>

        <h1 class="text-3xl font-bold text-slate-800">
            Building Management
        </h1>

        <p class="mt-1 text-slate-500">
            Manage campus buildings and organize rooms efficiently.
        </p>

    </div>

    <div class="flex flex-wrap gap-3">

        <!-- Download Template -->

        <a
            href="{{ route('buildings.template') }}"
            class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-medium text-slate-700 hover:bg-slate-100 transition">

            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke-width="1.8"
                 stroke="currentColor"
                 class="w-5 h-5">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M12 16.5V3m0 13.5 4.5-4.5M12 16.5 7.5 12M4.5 21h15"/>

            </svg>

            Download Template

        </a>

        <!-- Import -->

        <button
            type="button"
            class="open-import-modal inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-medium text-slate-700 hover:bg-slate-100 transition">

            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke-width="1.8"
                 stroke="currentColor"
                 class="w-5 h-5">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M12 16.5V4.5m0 12 3.75-3.75M12 16.5l-3.75-3.75M3 19.5h18"/>

            </svg>

            Import Excel

        </button>

        <!-- Export -->

        <a
            href="{{ route('buildings.export') }}"
            class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-medium text-slate-700 hover:bg-slate-100 transition">

            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke-width="1.8"
                 stroke="currentColor"
                 class="w-5 h-5">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>

            </svg>

            Export Excel

        </a>

        <!-- Delete All Buildings -->

        <button
            type="button"
            id="openDeleteAllBuildingsModal"
            class="inline-flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-5 py-3 text-sm font-medium text-red-600 hover:bg-red-100 hover:border-red-300 transition">

            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke-width="1.8"
                 stroke="currentColor"
                 class="w-5 h-5">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M14.74 9 9.26 14.48m0-5.48 5.48 5.48M19.5 6.75h-15m12.75 0v12.375A2.625 2.625 0 0 1 14.625 21.75h-5.25A2.625 2.625 0 0 1 6.75 19.125V6.75m3.75 0V4.875A1.875 1.875 0 0 1 12.375 3h-.75A1.875 1.875 0 0 1 13.5 4.875V6.75"/>

            </svg>

            Delete All Buildings

        </button>

        <!-- Add -->

        <button
            type="button"
            class="open-add-modal inline-flex items-center gap-2 rounded-xl bg-[#0E4C6B] px-5 py-3 text-sm font-medium text-white hover:bg-[#0B3D56] transition">

            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke-width="1.8"
                 stroke="currentColor"
                 class="w-5 h-5">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M12 4.5v15m7.5-7.5h-15"/>

            </svg>

            Add Building

        </button>

    </div>

</div>


{{-- Delete All Buildings Confirmation Modal --}}

<div
    id="deleteAllBuildingsModal"
    class="hidden fixed inset-0 z-[100] items-center justify-center bg-black/50 px-4">

    <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl overflow-hidden">

        <div class="p-6">

            <div class="flex items-center gap-3">

                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-red-100">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke-width="1.8"
                         stroke="currentColor"
                         class="w-6 h-6 text-red-600">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 9v3.75m0 3.75h.007M10.29 3.86 2.82 17.25a1.875 1.875 0 0 0 1.63 2.812h15.1a1.875 1.875 0 0 0 1.63-2.812L13.71 3.86a1.875 1.875 0 0 0-3.42 0Z"/>

                    </svg>

                </div>

                <div>

                    <h2 class="text-xl font-bold text-slate-800">
                        Delete All Buildings
                    </h2>

                    <p class="text-sm text-slate-500">
                        This action cannot be undone.
                    </p>

                </div>

            </div>

            <div class="mt-5 rounded-xl bg-red-50 border border-red-100 p-4">

                <p class="text-sm leading-6 text-red-700">

                    Are you sure you want to permanently delete
                    <strong>all buildings</strong>?

                    This will remove all building records from the system.

                </p>

            </div>

        </div>

        <div class="flex justify-end gap-3 border-t border-slate-200 p-5">

            <button
                type="button"
                id="cancelDeleteAllBuildings"
                class="px-5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-700 hover:bg-slate-100 transition">

                Cancel

            </button>

            <form
                method="POST"
                action="{{ route('buildings.deleteAll') }}">

                @csrf

                @method('DELETE')

                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-xl bg-red-600 text-white hover:bg-red-700 transition">

                    Delete All Buildings

                </button>

            </form>

        </div>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const openButton =
        document.getElementById('openDeleteAllBuildingsModal');

    const modal =
        document.getElementById('deleteAllBuildingsModal');

    const cancelButton =
        document.getElementById('cancelDeleteAllBuildings');

    if (!openButton || !modal || !cancelButton) {
        return;
    }

    openButton.addEventListener('click', function () {

        modal.classList.remove('hidden');

        modal.classList.add('flex');

    });

    cancelButton.addEventListener('click', function () {

        modal.classList.add('hidden');

        modal.classList.remove('flex');

    });

    modal.addEventListener('click', function (event) {

        if (event.target === modal) {

            modal.classList.add('hidden');

            modal.classList.remove('flex');

        }

    });

    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {

            modal.classList.add('hidden');

            modal.classList.remove('flex');

        }

    });

});

</script>