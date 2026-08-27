<div
    id="importRoomModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">

    <div class="w-full max-w-lg mx-4 rounded-2xl bg-white shadow-2xl">

        <!-- Header -->

        <div class="border-b border-slate-200 p-6">

            <h2 class="text-2xl font-bold text-slate-800">
                Import Rooms
            </h2>

            <p class="mt-1 text-slate-500">
                Upload an Excel (.xlsx, .xls) or CSV file to import rooms.
            </p>

        </div>

        <!-- Form -->

        <form
            action="{{ route('rooms.import.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="p-6">

            @csrf

            @if ($errors->any())

                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">

                    <p class="font-semibold text-red-700 mb-2">
                        Import failed
                    </p>

                    <ul class="list-disc list-inside text-sm text-red-600 space-y-1">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <div>

                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Excel File
                </label>

                <input
                    type="file"
                    name="file"
                    accept=".xlsx,.xls,.csv"
                    required
                    class="block w-full rounded-xl border border-slate-300 p-3
                           focus:border-[#0E4C6B]
                           focus:ring-[#0E4C6B]">

            </div>

            <div class="mt-6 rounded-xl bg-blue-50 p-4 text-sm text-slate-600">

                <p class="font-semibold mb-2">
                    Accepted Format
                </p>

                <p>• Building Name</p>
                <p>• Room Number</p>
                <p>• Room Name</p>
                <p>• Capacity</p>
                <p>• Floor</p>

            </div>

            <div class="mt-8 flex justify-end gap-3">

                <button
                    type="button"
                    class="close-import-modal rounded-xl border border-slate-300 px-5 py-2.5 hover:bg-slate-100">

                    Cancel

                </button>

                <button
                    type="submit"
                    class="rounded-xl bg-[#0E4C6B] px-6 py-2.5 text-white hover:bg-[#0B3D56]">

                    Import

                </button>

            </div>

        </form>

    </div>

</div>