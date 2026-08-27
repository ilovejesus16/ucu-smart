<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

    <div class="overflow-x-auto">

        <table class="min-w-full">

            <thead class="bg-slate-50 border-b border-slate-200">

                <tr>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-600">
                        User
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-600">
                        ID
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-600">
                        Role
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-600">
                        Course / Department
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-600">
                        Status
                    </th>

                    <th class="px-6 py-4 text-right text-xs font-semibold uppercase text-slate-600">
                        Actions
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-slate-200 bg-white">

                @forelse($users as $user)

                <tr class="hover:bg-slate-50 transition">

                    {{-- USER --}}
                    <td class="px-6 py-4">

                        <div class="flex items-center gap-4">

                            <div class="w-11 h-11 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">

                                {{ strtoupper(substr($user->first_name,0,1).substr($user->last_name,0,1)) }}

                            </div>

                            <div>

                                <div class="font-semibold text-slate-800">

                                    {{ $user->first_name }} {{ $user->last_name }}

                                </div>

                                <div class="text-sm text-slate-500">

                                    {{ $user->email }}

                                </div>

                            </div>

                        </div>

                    </td>

                    {{-- ID --}}
                    <td class="px-6 py-4 text-sm text-slate-700">

                        {{ $user->student_id ?? $user->employee_id ?? '-' }}

                    </td>

                    {{-- ROLE --}}
                    <td class="px-6 py-4">

                        @if($user->role=='student')

                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                Student
                            </span>

                        @elseif($user->role=='instructor')

                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                Instructor
                            </span>

                        @else

                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">
                                Administrator
                            </span>

                        @endif

                    </td>

                    {{-- COURSE / DEPARTMENT --}}
                    <td class="px-6 py-4 text-sm text-slate-700">

                        {{ $user->course ?: ($user->department ?: '-') }}

                    </td>

                    {{-- STATUS --}}
                    <td class="px-6 py-4">

                        @if($user->status=='active')

                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                Active
                            </span>

                        @elseif($user->status=='pending')

                            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                                Pending
                            </span>

                        @else

                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                Rejected
                            </span>

                        @endif

                    </td>

                    {{-- ACTIONS --}}
                    <td class="px-6 py-4 text-right">

                        @include('admin.users.partials.actions')

                    </td>

                </tr>

                @empty

                    @include('admin.users.partials.empty-state')

                @endforelse

            </tbody>

        </table>

    </div>

    @if($users->hasPages())

        <div class="border-t border-slate-200 px-6 py-4">

            {{ $users->links() }}

        </div>

    @endif

</div>