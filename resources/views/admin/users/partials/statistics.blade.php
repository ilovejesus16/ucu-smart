<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">

    <!-- Students -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm text-slate-500">
                    Students
                </p>

                <h2 class="mt-2 text-3xl font-bold text-slate-800">
                    {{ number_format($studentCount) }}
                </h2>

            </div>

          <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">

    <svg xmlns="http://www.w3.org/2000/svg"
         fill="none"
         viewBox="0 0 24 24"
         stroke-width="1.8"
         stroke="currentColor"
         class="w-7 h-7 text-blue-600">

        <path stroke-linecap="round"
              stroke-linejoin="round"
              d="M12 14 3 9l9-5 9 5-9 5Zm0 0v6m6-9v5"/>

    </svg>

</div>

        </div>

    </div>

    <!-- Instructors -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm text-slate-500">
                    Instructors
                </p>

                <h2 class="mt-2 text-3xl font-bold text-slate-800">
                    {{ number_format($instructorCount) }}
                </h2>

            </div>

            <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center">

    <svg xmlns="http://www.w3.org/2000/svg"
         fill="none"
         viewBox="0 0 24 24"
         stroke-width="1.8"
         stroke="currentColor"
         class="w-7 h-7 text-indigo-600">

        <path stroke-linecap="round"
              stroke-linejoin="round"
              d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.118a7.5 7.5 0 0 1 15 0"/>

    </svg>

</div>

        </div>

    </div>

    <!-- Administrators -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm text-slate-500">
                    Administrators
                </p>

                <h2 class="mt-2 text-3xl font-bold text-slate-800">
                    {{ number_format($adminCount) }}
                </h2>

            </div>

           <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center">

    <svg xmlns="http://www.w3.org/2000/svg"
         fill="none"
         viewBox="0 0 24 24"
         stroke-width="1.8"
         stroke="currentColor"
         class="w-7 h-7 text-emerald-600">

        <path stroke-linecap="round"
              stroke-linejoin="round"
              d="M9 12.75 11.25 15 15 9.75m6-3.75L12 3 2.25 9c0 5.25 3.438 10.125 9.75 12 6.312-1.875 9.75-6.75 9.75-12Z"/>

    </svg>

</div>

        </div>

    </div>

    <!-- Pending -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm text-slate-500">
                    Pending Approval
                </p>

                <h2 class="mt-2 text-3xl font-bold text-amber-600">
                    {{ number_format($pendingCount) }}
                </h2>

            </div>

            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">

    <svg xmlns="http://www.w3.org/2000/svg"
         fill="none"
         viewBox="0 0 24 24"
         stroke-width="1.8"
         stroke="currentColor"
         class="w-7 h-7 text-amber-600">

        <path stroke-linecap="round"
              stroke-linejoin="round"
              d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z"/>

    </svg>

</div>

        </div>

    </div>

</div>