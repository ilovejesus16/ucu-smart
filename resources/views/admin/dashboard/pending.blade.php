<div class="bg-white rounded-2xl shadow-sm border h-full">

    <div class="flex items-center justify-between px-6 py-5 border-b">

        <div>

            <h2 class="text-lg font-bold text-gray-800">

                Pending Approvals

            </h2>

            <p class="text-sm text-gray-500">

                Awaiting administrator approval.

            </p>

        </div>

        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">

            {{ $pendingUsers }}

        </span>

    </div>

    <div class="p-5">

        @forelse($pendingList as $user)

            <div class="flex items-center justify-between py-4 border-b last:border-0">

                <div class="flex items-center gap-4">

                    <div class="w-12 h-12 rounded-full bg-[#0E2958] text-white flex items-center justify-center font-bold text-lg">

                        {{ strtoupper(substr($user->first_name,0,1)) }}

                    </div>

                    <div>

                        <h3 class="font-semibold text-gray-800">

                            {{ $user->first_name }}
                            {{ $user->last_name }}

                        </h3>

                        <p class="text-sm text-gray-500 capitalize">

                            {{ $user->role }}

                        </p>

                    </div>

                </div>

                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">

                    Pending

                </span>

            </div>

        @empty

            <div class="py-10 text-center">

                <div class="w-16 h-16 mx-auto rounded-full bg-green-100 flex items-center justify-center mb-4">

                    <x-heroicon-o-check-circle class="w-8 h-8 text-green-600"/>

                </div>

                <h3 class="font-semibold text-gray-700">

                    All Caught Up

                </h3>

                <p class="text-sm text-gray-500 mt-2">

                    There are no pending registrations.

                </p>

            </div>

        @endforelse

    </div>

    <div class="border-t p-5">

        <a href="{{ route('admin.users') }}"
           class="w-full flex items-center justify-center gap-2 rounded-xl bg-[#0E2958] hover:bg-[#1E3A8A] text-white py-3 transition">

            <x-heroicon-o-users class="w-5 h-5"/>

            Review All Users

        </a>

    </div>

</div>