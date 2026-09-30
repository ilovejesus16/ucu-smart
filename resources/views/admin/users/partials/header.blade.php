<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

    <!-- Left -->

    <div>

        <h1 class="text-3xl font-bold text-slate-800">
            User Management
        </h1>

        <p class="mt-1 text-slate-500">
            Manage student, instructor, and administrator accounts.
        </p>

    </div>

    <!-- Right -->

    <div class="flex flex-wrap gap-3">

        <!-- Import Students -->

        <button
            type="button"
            class="open-student-modal inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-medium text-slate-700 hover:bg-slate-100 transition">

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

            Import Students

        </button>

        <!-- Import Instructors -->

        <button
            type="button"
            class="open-instructor-modal inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-medium text-slate-700 hover:bg-slate-100 transition">

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

            Import Instructors

        </button>

        <!-- Templates -->

        <div class="relative">

            <button
                id="templateDropdownBtn"
                type="button"
                class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-medium text-slate-700 hover:bg-slate-100 transition">

                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="1.8"
                     stroke="currentColor"
                     class="w-5 h-5">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375H14.25V6.375A2.625 2.625 0 0 0 11.625 3.75h-4.5A2.625 2.625 0 0 0 4.5 6.375v11.25a2.625 2.625 0 0 0 2.625 2.625h9.75A2.625 2.625 0 0 0 19.5 17.625V14.25Z"/>

                </svg>

                Templates

                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="1.8"
                     stroke="currentColor"
                     class="w-4 h-4">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="m19.5 8.25-7.5 7.5-7.5-7.5"/>

                </svg>

            </button>

            <div
                id="templateDropdown"
                class="hidden absolute right-0 mt-2 w-64 bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden z-50">

                <a
                    href="{{ route('admin.users.template.students') }}"
                    class="block px-4 py-3 hover:bg-slate-100">

                    Student Import Template

                </a>

                <a
                    href="{{ route('admin.users.template.instructors') }}"
                    class="block px-4 py-3 hover:bg-slate-100">

                    Instructor Import Template

                </a>

            </div>

        </div>

        <!-- Delete All Users -->

        <button
            type="button"
            id="openDeleteAllUsersModal"
            class="inline-flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-5 py-3 text-sm font-medium text-red-600 hover:bg-red-100 hover:border-red-300 transition">

            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke-width="1.8"
                 stroke="currentColor"
                 class="w-5 h-5">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M6 7.5h12m-9 0v10.125m6-10.125v10.125M9.75 4.5h4.5l.75 3H9l.75-3ZM6.75 7.5l.75 12h9l.75-12"/>

            </svg>

            Delete All Users

        </button>

        <!-- Add User -->

        <button
            type="button"
            class="open-add-user-modal inline-flex items-center gap-2 rounded-xl bg-[#0E4C6B] px-5 py-3 text-sm font-medium text-white hover:bg-[#0B3D56] transition">

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

            Add User

        </button>

    </div>

</div>


<!-- ========================================================= -->
<!-- Delete All Users Confirmation Modal -->
<!-- ========================================================= -->

<div
    id="deleteAllUsersModal"
    class="hidden fixed inset-0 z-[100] items-center justify-center bg-black/50 px-4">

    <div
        class="w-full max-w-md rounded-2xl bg-white shadow-2xl overflow-hidden">

        <!-- Modal Header -->

        <div class="flex items-center gap-4 px-6 py-5 border-b border-slate-200">

            <div class="flex items-center justify-center w-11 h-11 rounded-full bg-red-100">

                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="1.8"
                     stroke="currentColor"
                     class="w-6 h-6 text-red-600">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 9v3.75m0 3.75h.007M10.29 3.86 2.82 17.25A1.875 1.875 0 0 0 4.45 20h15.1a1.875 1.875 0 0 0 1.63-2.75L13.71 3.86a1.875 1.875 0 0 0-3.42 0Z"/>

                </svg>

            </div>

            <div>

                <h2 class="text-lg font-semibold text-slate-800">
                    Delete All Users
                </h2>

                <p class="text-sm text-slate-500">
                    Permanent account deletion
                </p>

            </div>

        </div>


        <!-- Modal Body -->

        <div class="px-6 py-5">

            <p class="text-sm leading-6 text-slate-600">

                This action will permanently delete all user accounts
                from the system.

            </p>

            <div class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3">

                <div class="flex gap-3">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke-width="1.8"
                         stroke="currentColor"
                         class="w-5 h-5 flex-shrink-0 text-red-600 mt-0.5">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 9v3.75m0 3.75h.007M10.29 3.86 2.82 17.25A1.875 1.875 0 0 0 4.45 20h15.1a1.875 1.875 0 0 0 1.63-2.75L13.71 3.86a1.875 1.875 0 0 0-3.42 0Z"/>

                    </svg>

                    <div>

                        <p class="text-sm font-semibold text-red-700">
                            This action cannot be undone.
                        </p>

                        <p class="mt-1 text-sm text-red-600">
                            Your currently logged-in administrator account
                            will be protected from deletion.
                        </p>

                    </div>

                </div>

            </div>

        </div>


        <!-- Modal Footer -->

        <div class="flex justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-200">

            <button
                type="button"
                id="cancelDeleteAllUsers"
                class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100 transition">

                Cancel

            </button>

            <form
                method="POST"
                action="{{ route('admin.users.deleteAll') }}">

                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-red-700 transition">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke-width="1.8"
                         stroke="currentColor"
                         class="w-5 h-5">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M6 7.5h12m-9 0v10.125m6-10.125v10.125M9.75 4.5h4.5l.75 3H9l.75 3h-9"/>

                    </svg>

                    Delete All Users

                </button>

            </form>

        </div>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const openButton = document.getElementById('openDeleteAllUsersModal');

    const modal = document.getElementById('deleteAllUsersModal');

    const cancelButton = document.getElementById('cancelDeleteAllUsers');


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