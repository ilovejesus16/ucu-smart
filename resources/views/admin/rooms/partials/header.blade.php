<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

    <div>

        <h1 class="text-3xl font-bold text-slate-800">
            Room Management
        </h1>

        <p class="mt-1 text-slate-500">
            Manage classrooms, laboratories, and campus facilities.
        </p>

    </div>

    <div class="flex items-center gap-3 shrink-0">

        <!-- Download Template -->

        <a
            href="{{ route('rooms.template') }}"
            class="inline-flex items-center gap-2 whitespace-nowrap shrink-0 rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-medium text-slate-700 hover:bg-slate-100 transition">

            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke-width="1.8"
                 stroke="currentColor"
                 class="w-5 h-5 shrink-0">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M12 16.5V3m0 13.5 4.5-4.5M12 16.5 7.5 12M4.5 21h15"/>

            </svg>

            Download Template

        </a>

        <!-- Import -->

        <button
            type="button"
            class="open-import-modal inline-flex items-center gap-2 whitespace-nowrap shrink-0 rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-medium text-slate-700 hover:bg-slate-100 transition">

            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke-width="1.8"
                 stroke="currentColor"
                 class="w-5 h-5 shrink-0">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M12 16.5V4.5m0 12 3.75-3.75M12 16.5l-3.75-3.75M3 19.5h18"/>

            </svg>

            Import Excel

        </button>

        <!-- Export -->

        <a
            href="{{ route('rooms.export') }}"
            class="inline-flex items-center gap-2 whitespace-nowrap shrink-0 rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-medium text-slate-700 hover:bg-slate-100 transition">

            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke-width="1.8"
                 stroke="currentColor"
                 class="w-5 h-5 shrink-0">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>

            </svg>

            Export Excel

        </a>

        <!-- Delete All Rooms -->

        <button
            type="button"
            id="openDeleteAllRoomsModal"
            class="inline-flex items-center gap-2 whitespace-nowrap shrink-0 rounded-xl border border-red-200 bg-red-50 px-5 py-3 text-sm font-medium text-red-700 hover:bg-red-100 transition">

            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke-width="1.8"
                 stroke="currentColor"
                 class="w-5 h-5 shrink-0">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M6 7.5h12m-10.5 0v10.125A1.875 1.875 0 0 0 9.375 19.5h5.25a1.875 1.875 0 0 0 1.875-1.875V7.5m-7.5 0V5.625A1.125 1.125 0 0 1 10.125 4.5h3.75A1.125 1.125 0 0 1 15 5.625V7.5m-6 3v6m6-6v6"/>

            </svg>

            Delete All Rooms

        </button>

        <!-- Add Room -->

        <button
            type="button"
            class="open-add-modal inline-flex items-center gap-2 whitespace-nowrap shrink-0 rounded-xl bg-[#0E4C6B] px-5 py-3 text-sm font-medium text-white hover:bg-[#0B3D56] transition">

            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke-width="1.8"
                 stroke="currentColor"
                 class="w-5 h-5 shrink-0">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M12 4.5v15m7.5-7.5h-15"/>

            </svg>

            Add Room

        </button>

    </div>

</div>


{{-- Delete All Rooms Confirmation Modal --}}

<div
    id="deleteAllRoomsModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4">

    <div
        class="w-full max-w-md rounded-2xl bg-white shadow-2xl">

        <div class="p-6">

            <div class="flex items-start gap-4">

                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-100">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke-width="1.8"
                         stroke="currentColor"
                         class="h-6 w-6 text-red-600">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M6 7.5h12m-10.5 0v10.125A1.875 1.875 0 0 0 9.375 19.5h5.25a1.875 1.875 0 0 0 1.875-1.875V7.5m-7.5 0V5.625A1.125 1.125 0 0 1 10.125 4.5h3.75A1.125 1.125 0 0 1 15 5.625V7.5m-6 3v6m6-6v6"/>

                    </svg>

                </div>

                <div>

                    <h2 class="text-lg font-semibold text-slate-800">
                        Delete All Rooms?
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        This will permanently delete all room records from the system.
                        This action cannot be undone.
                    </p>

                </div>

            </div>

        </div>

        <div class="flex justify-end gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4 rounded-b-2xl">

            <button
                type="button"
                id="closeDeleteAllRoomsModal"
                class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100 transition">

                Cancel

            </button>

            <form
                method="POST"
                action="{{ route('rooms.deleteAll') }}">

                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="rounded-xl bg-red-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-red-700 transition">

                    Delete All Rooms

                </button>

            </form>

        </div>

    </div>

</div>


<script>

    document.addEventListener('DOMContentLoaded', function () {

        const modal = document.getElementById('deleteAllRoomsModal');
        const openButton = document.getElementById('openDeleteAllRoomsModal');
        const closeButton = document.getElementById('closeDeleteAllRoomsModal');

        if (!modal || !openButton || !closeButton) {
            return;
        }

        openButton.addEventListener('click', function () {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });

        closeButton.addEventListener('click', function () {
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