<tr>

    <td colspan="7" class="px-6 py-20">

        <div class="flex flex-col items-center justify-center text-center">

            <div class="w-24 h-24 rounded-full bg-slate-100 flex items-center justify-center mb-6">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-12 h-12 text-slate-400"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.5"
                          d="M17 20h5V4H2v16h5m10 0v-6H7v6m10 0H7"/>

                </svg>

            </div>

            <h3 class="text-2xl font-semibold text-slate-700">

                No Users Found

            </h3>

            <p class="mt-3 text-slate-500 max-w-md">

                There are no users matching your current search or filter.
                Try changing your filters or import new users.

            </p>

            <div class="mt-8 flex flex-wrap justify-center gap-3">

                <button
                    type="button"
                    onclick="document.getElementById('studentImportModal').classList.remove('hidden')"
                    class="px-5 py-2.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">

                    Import Students

                </button>

                <button
                    type="button"
                    onclick="document.getElementById('instructorImportModal').classList.remove('hidden')"
                    class="px-5 py-2.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition">

                    Import Instructors

                </button>

            </div>

        </div>

    </td>

</tr>