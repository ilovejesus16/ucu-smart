<div class="bg-white rounded-2xl shadow-sm border h-full">

    <div class="flex items-center justify-between px-6 py-5 border-b">

        <div>

            <h2 class="text-lg font-bold text-gray-800">

                Recent Users

            </h2>

            <p class="text-sm text-gray-500">

                Latest registered accounts.

            </p>

        </div>

        <x-heroicon-o-clock class="w-6 h-6 text-gray-400"/>

    </div>

    <div class="p-5">

        @forelse($recentUsers as $user)

            <div class="flex items-center justify-between py-4 border-b last:border-0">

                <div class="flex items-center gap-4">

                    <div class="w-11 h-11 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold">

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

                <div class="text-right">

                    <p class="text-sm text-gray-600">

                        {{ $user->created_at->diffForHumans() }}

                    </p>

                    <p class="text-xs text-gray-400">

                        {{ $user->created_at->format('M d') }}

                    </p>

                </div>

            </div>

        @empty

            <div class="py-10 text-center">

                <div class="w-16 h-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">

                    <x-heroicon-o-user-group class="w-8 h-8 text-gray-500"/>

                </div>

                <h3 class="font-semibold text-gray-700">

                    No Recent Users

                </h3>

                <p class="text-sm text-gray-500 mt-2">

                    No new registrations have been made yet.

                </p>

            </div>

        @endforelse

    </div>

    <div class="border-t p-5">

        <a href="{{ route('admin.users') }}"
           class="w-full flex items-center justify-center gap-2 rounded-xl border border-gray-300 hover:bg-gray-50 py-3 transition">

            <x-heroicon-o-users class="w-5 h-5"/>

            View All Users

        </a>

    </div>

</div>