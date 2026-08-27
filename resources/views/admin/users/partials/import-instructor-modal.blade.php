<div id="instructorImportModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4">

        <div class="flex items-center justify-between p-6 border-b">

            <div>

                <h2 class="text-2xl font-bold text-slate-800">
                    Import Instructors
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Upload an Excel (.xlsx, .xls) or CSV file.
                </p>

            </div>

            <button
                type="button"
                class="close-instructor-modal text-2xl text-slate-400 hover:text-slate-700">

                &times;

            </button>

        </div>

        <form
            action="{{ route('admin.users.import.instructors.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="p-6 space-y-6">

            @csrf

            <div>

                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Select File
                </label>

                <input
                    type="file"
                    name="file"
                    accept=".xlsx,.xls,.csv"
                    required
                    class="block w-full rounded-lg border border-slate-300
                           file:mr-4
                           file:px-4
                           file:py-2
                           file:border-0
                           file:bg-indigo-600
                           file:text-white
                           file:rounded-lg
                           hover:file:bg-indigo-700">

            </div>

            <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4">

                <h4 class="font-semibold text-indigo-700 mb-2">
                    Instructor Template Columns
                </h4>

                <ul class="text-sm text-slate-600 space-y-1">

                    <li>• Employee ID</li>
                    <li>• First Name</li>
                    <li>• Last Name</li>
                    <li>• Department</li>
                    <li>• Email</li>

                </ul>

            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-3">

                <button
                    type="button"
                    class="close-instructor-modal px-5 py-2 rounded-lg border border-slate-300 hover:bg-slate-100">

                    Cancel

                </button>

                <button
                    type="submit"
                    class="px-5 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white">

                    Import Instructors

                </button>

            </div>

        </form>

    </div>

</div>