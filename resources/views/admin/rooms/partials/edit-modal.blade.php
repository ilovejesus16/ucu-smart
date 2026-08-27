<div
    id="editRoomModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">

    <div class="w-full max-w-3xl rounded-2xl bg-white shadow-2xl">

        <div class="border-b border-slate-200 p-6">

            <h2 class="text-2xl font-bold text-slate-800">
                Edit Room
            </h2>

            <p class="mt-1 text-slate-500">
                Update room information.
            </p>

        </div>

        <form
            id="editRoomForm"
            method="POST"
            class="p-6">

            @csrf
            @method('PUT')

            @include('admin.rooms.partials.edit-form')

            <div class="mt-8 flex justify-end gap-3">

                <button
                    type="button"
                    class="close-edit-modal rounded-xl border border-slate-300 px-5 py-2.5 hover:bg-slate-100">

                    Cancel

                </button>

                <button
                    type="submit"
                    class="rounded-xl bg-[#0E4C6B] px-6 py-2.5 text-white hover:bg-[#0B3D56]">

                    Update Room

                </button>

            </div>

        </form>

    </div>

</div>