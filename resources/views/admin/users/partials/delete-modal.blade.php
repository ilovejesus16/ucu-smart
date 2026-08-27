<div id="deleteModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">

        <div class="p-6">

            <div class="flex items-center justify-center w-16 h-16 mx-auto rounded-full bg-red-100">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-8 h-8 text-red-600"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>

                </svg>

            </div>

            <h2 class="mt-6 text-2xl font-bold text-center text-slate-800">

                Delete User

            </h2>

            <p class="mt-3 text-center text-slate-500">

                Are you sure you want to delete

                <span id="deleteUserName"
                      class="font-semibold text-slate-700"></span>?

            </p>

            <p class="mt-2 text-center text-sm text-red-500">

                This action cannot be undone.

            </p>

            <form
                id="deleteForm"
                method="POST"
                class="mt-8">

                @csrf
                @method('DELETE')

                <div class="flex flex-col sm:flex-row gap-3">

                    <button
                        type="button"
                        class="close-delete-modal flex-1 px-5 py-3 rounded-xl border border-slate-300 hover:bg-slate-100 transition">

                        Cancel

                    </button>

                    <button
                        type="submit"
                        class="flex-1 px-5 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white transition">

                        Delete User

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>