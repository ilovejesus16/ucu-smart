
@if(auth()->user()->hasAdminPermission('rooms', 'full'))
<div
    id="addRoomModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm"
>
    <div class="flex max-h-[92vh] w-full max-w-3xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl">

        {{-- Header --}}
        <div class="flex shrink-0 items-center justify-between border-b border-slate-100 px-6 py-5">
            <div>
                <h2 class="text-xl font-bold text-slate-800">
                    Add Room
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Register a new classroom or laboratory.
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
            action="{{ route('admin.rooms.store') }}"
            method="POST"
            id="addRoomForm"
            class="flex min-h-0 flex-1 flex-col"
        >
            @csrf

            <div class="min-h-0 flex-1 overflow-y-auto px-6 py-6">
                @include('admin.rooms.partials.add-form')
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
                    Save Room
                </button>
            </div>
        </form>
    </div>
</div>
@endif
