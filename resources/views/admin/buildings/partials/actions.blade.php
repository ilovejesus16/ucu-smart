<div class="relative inline-block text-left" x-data="{ open: false }">

    <button
        @click="open = !open"
        class="inline-flex items-center justify-center w-10 h-10 rounded-lg border border-slate-300 bg-white hover:bg-slate-100 transition">

        <svg xmlns="http://www.w3.org/2000/svg"
             class="w-5 h-5"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor">

            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 5v.01M12 12v.01M12 19v.01"/>

        </svg>

    </button>

    <div
        x-show="open"
        @click.away="open = false"
        x-transition
        class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-lg border border-slate-200 z-50">

        <div class="py-2">

            <!-- View -->

            <button
                type="button"
                class="view-building w-full flex items-center gap-3 px-4 py-2 hover:bg-slate-100 text-left"
                data-id="{{ $building->id }}">

                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="1.8"
                     stroke="currentColor"
                     class="w-5 h-5">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M2.036 12.322a1.012 1.012 0 0 1 0-.644C3.423 7.51 7.36 4.5 12 4.5s8.577 3.01 9.964 7.178a1.012 1.012 0 0 1 0 .644C20.577 16.49 16.64 19.5 12 19.5S3.423 16.49 2.036 12.322Z"/>

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>

                </svg>

                <span>View</span>

            </button>

            <!-- Edit -->

            <button
                type="button"
                class="edit-building w-full flex items-center gap-3 px-4 py-2 hover:bg-slate-100 text-left"
                data-id="{{ $building->id }}">

                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="1.8"
                     stroke="currentColor"
                     class="w-5 h-5">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.862 4.487Z"/>

                </svg>

                <span>Edit</span>

            </button>

            <!-- View Rooms -->

            <a
                href="{{ route('rooms.index', ['building' => $building->id]) }}"
                class="flex items-center gap-3 px-4 py-2 hover:bg-slate-100">

                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="1.8"
                     stroke="currentColor"
                     class="w-5 h-5">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M3.75 6.75A2.25 2.25 0 0 1 6 4.5h12a2.25 2.25 0 0 1 2.25 2.25v10.5A2.25 2.25 0 0 1 18 19.5H6a2.25 2.25 0 0 1-2.25-2.25V6.75Z"/>

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M9 10.5h6m-6 3h3"/>

                </svg>

                <span>View Rooms</span>

            </a>

            <hr>

            <!-- Delete -->

            <button
                type="button"
                class="delete-building w-full flex items-center gap-3 px-4 py-2 text-red-600 hover:bg-red-50 text-left"
                data-id="{{ $building->id }}"
                data-name="{{ $building->building_name }}">

                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="1.8"
                     stroke="currentColor"
                     class="w-5 h-5">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21H8.084a2.25 2.25 0 0 1-2.244-2.327L4.772 5.79"/>

                </svg>

                <span>Delete</span>

            </button>

        </div>

    </div>

</div>