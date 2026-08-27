<div id="importBuildingModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4">

        <div class="p-6 border-b border-slate-200">

            <h2 class="text-2xl font-bold text-slate-800">
                Import Buildings
            </h2>

            <p class="mt-1 text-slate-500">
                Upload an Excel or CSV file containing your building list.
            </p>

        </div>

        <form
            action="{{ route('buildings.import.store') }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf

            <div class="p-6 space-y-5">

                <div>

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Excel File
                    </label>

                    <input
                        type="file"
                        name="file"
                        accept=".xlsx,.xls,.csv"
                        required
                        class="block w-full rounded-xl border border-slate-300
                               file:mr-4
                               file:rounded-lg
                               file:border-0
                               file:bg-[#0E4C6B]
                               file:px-4
                               file:py-2
                               file:text-white
                               hover:file:bg-[#0B3D56]">

                </div>

                <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">

                    <h3 class="font-semibold text-slate-700">

                        Template Format

                    </h3>

                    <div class="mt-3 overflow-hidden rounded-lg border">

                        <table class="w-full text-sm">

                            <thead class="bg-slate-100">

                                <tr>

                                    <th class="px-4 py-2 text-left">

                                        Building Name

                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                <tr>

                                    <td class="px-4 py-2">
                                        Main Building
                                    </td>

                                </tr>

                                <tr>

                                    <td class="px-4 py-2">
                                        TEC Building
                                    </td>

                                </tr>

                                <tr>

                                    <td class="px-4 py-2">
                                        CITE Building
                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

                <a
                    href="{{ route('buildings.template') }}"
                    class="inline-flex items-center gap-2 text-sm text-[#0E4C6B] hover:underline">

                    Download Template

                </a>

            </div>

            <div class="border-t border-slate-200 p-6 flex justify-end gap-3">

                <button
                    type="button"
                    class="close-import-modal px-5 py-2.5 rounded-xl border border-slate-300 hover:bg-slate-100">

                    Cancel

                </button>

                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-xl bg-[#0E4C6B] text-white hover:bg-[#0B3D56]">

                    Import Buildings

                </button>

            </div>

        </form>

    </div>

</div>