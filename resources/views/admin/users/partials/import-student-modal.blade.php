<div id="studentImportModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4">

        <div class="flex items-center justify-between p-6 border-b">

            <div>

                <h2 class="text-2xl font-bold text-slate-800">
                    Import Students
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Upload an Excel (.xlsx, .xls) or CSV file.
                </p>

            </div>

            <button
                type="button"
                class="close-student-modal text-2xl text-slate-400 hover:text-slate-700">

                &times;

            </button>

        </div>

        <form
            action="{{ route('admin.users.import.students.store') }}"
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
                           file:bg-blue-600
                           file:text-white
                           file:rounded-lg
                           hover:file:bg-blue-700">

            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">

                <h4 class="font-semibold text-blue-700 mb-2">

                    Student Template Columns

                </h4>

                <ul class="text-sm text-slate-600 space-y-1">

                    <li>• Student ID</li>
                    <li>• First Name</li>
                    <li>• Last Name</li>
                    <li>• Course</li>
                    <li>• Email</li>

                </ul>

            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-3">

                <button
                    type="button"
                    class="close-student-modal px-5 py-2 rounded-lg border border-slate-300 hover:bg-slate-100">

                    Cancel

                </button>

                <button
                    type="submit"
                    class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white">

                    Import Students

                </button>

            </div>

        </form>

    </div>

</div>