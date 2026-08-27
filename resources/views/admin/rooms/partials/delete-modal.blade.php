<div
    id="deleteRoomModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">

    <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl">

        <!-- Header -->

        <div class="border-b border-slate-200 p-6">

            <h2 class="text-xl font-bold text-slate-800">
                Delete Room
            </h2>

            <p class="mt-1 text-slate-500">
                This action cannot be undone.
            </p>

        </div>

        <!-- Body -->

        <div class="p-6">

            <p class="text-slate-700">

                Are you sure you want to delete

                <span
                    id="deleteRoomName"
                    class="font-bold text-red-600">
                </span>?

            </p>

        </div>

        <!-- Footer -->

        <div class="flex justify-end gap-3 border-t border-slate-200 p-6">

            <button
                type="button"
                class="close-delete-modal rounded-xl border border-slate-300 px-5 py-2.5 hover:bg-slate-100">

                Cancel

            </button>

            <form
                id="deleteRoomForm"
                method="POST">

                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="rounded-xl bg-red-600 px-6 py-2.5 text-white hover:bg-red-700">

                    Delete

                </button>

            </form>

        </div>

    </div>

</div>