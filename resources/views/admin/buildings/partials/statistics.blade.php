<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- Total Buildings -->

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm font-medium text-slate-500">
                    Total Buildings
                </p>

                <h2 class="mt-2 text-3xl font-bold text-slate-800">

                    {{ $buildingCount }}

                </h2>

            </div>

            <div class="w-14 h-14 rounded-2xl bg-[#0E4C6B]/10 flex items-center justify-center">

                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="1.8"
                     stroke="currentColor"
                     class="w-7 h-7 text-[#0E4C6B]">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M3.75 21h16.5M5.25 21V7.5A2.25 2.25 0 0 1 7.5 5.25h9A2.25 2.25 0 0 1 18.75 7.5V21M9 9h.008v.008H9V9Zm0 3h.008v.008H9V12Zm0 3h.008v.008H9V15Zm6-6h.008v.008H15V9Zm0 3h.008v.008H15V12Zm0 3h.008v.008H15V15Z"/>

                </svg>

            </div>

        </div>

    </div>

    <!-- Total Rooms -->

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm font-medium text-slate-500">
                    Total Rooms
                </p>

                <h2 class="mt-2 text-3xl font-bold text-slate-800">

                    {{ $roomCount }}

                </h2>

            </div>

            <div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center">

                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="1.8"
                     stroke="currentColor"
                     class="w-7 h-7 text-green-600">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M3.75 6.75A2.25 2.25 0 0 1 6 4.5h12a2.25 2.25 0 0 1 2.25 2.25v10.5A2.25 2.25 0 0 1 18 19.5H6a2.25 2.25 0 0 1-2.25-2.25V6.75ZM9 10.5h6m-6 3h3"/>

                </svg>

            </div>

        </div>

    </div>

    <!-- Buildings with Images -->

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm font-medium text-slate-500">
                    Buildings with Images
                </p>

                <h2 class="mt-2 text-3xl font-bold text-slate-800">

                    {{ $imageCount }}

                </h2>

            </div>

            <div class="w-14 h-14 rounded-2xl bg-yellow-100 flex items-center justify-center">

                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="1.8"
                     stroke="currentColor"
                     class="w-7 h-7 text-yellow-600">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0L15 15m-1.5-1.5 1.909-1.909a2.25 2.25 0 0 1 3.182 0L21.75 15.75M3.75 19.5h16.5A1.5 1.5 0 0 0 21.75 18V6A1.5 1.5 0 0 0 20.25 4.5H3.75A1.5 1.5 0 0 0 2.25 6v12A1.5 1.5 0 0 0 3.75 19.5Z"/>

                </svg>

            </div>

        </div>

    </div>

</div>