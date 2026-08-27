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