<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6">

    <form id="filtersForm"
          method="GET"
          action="{{ route('admin.users') }}">

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-5">

            {{-- Search --}}
            <div class="xl:col-span-2">

                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Search
                </label>

                <div class="relative">

                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke-width="1.8"
                             stroke="currentColor"
                             class="w-5 h-5 text-slate-400">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="m21 21-5.2-5.2m0 0A7.5 7.5 0 1 0 5.2 5.2a7.5 7.5 0 0 0 10.6 10.6Z"/>

                        </svg>

                    </div>

                    <input
                        id="searchInput"
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search by ID, name, or email..."
                        class="w-full pl-10 rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

                </div>

                <p class="mt-2 text-xs text-slate-500">
                    Press <kbd class="px-1 py-0.5 border rounded">Enter</kbd> to search.
                </p>

            </div>

            {{-- Role --}}
            <div>

                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Role
                </label>

                <select
                    name="role"
                    class="filter-select w-full rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

                    <option value="">All Roles</option>
                    <option value="student" @selected(request('role')=='student')>Student</option>
                    <option value="instructor" @selected(request('role')=='instructor')>Instructor</option>
                    <option value="admin" @selected(request('role')=='admin')>Administrator</option>

                </select>

            </div>

            {{-- Status --}}
            <div>

                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Status
                </label>

                <select
                    name="status"
                    class="filter-select w-full rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

                    <option value="">All Status</option>
                    <option value="pending" @selected(request('status')=='pending')>Pending</option>
                    <option value="active" @selected(request('status')=='active')>Active</option>
                    <option value="rejected" @selected(request('status')=='rejected')>Rejected</option>

                </select>

            </div>

            {{-- Course --}}
            <div>

                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Course
                </label>

                <select
                    name="course"
                    class="filter-select w-full rounded-xl border-slate-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

                    <option value="">All Courses</option>

                    @foreach($courses as $course)
                        <option value="{{ $course }}"
                            @selected(request('course')==$course)>
                            {{ $course }}
                        </option>
                    @endforeach

                </select>

            </div>

        </div>

        <div class="flex justify-end mt-6">

            <a href="{{ route('admin.users') }}"
               class="px-5 py-2.5 rounded-xl border border-slate-300 hover:bg-slate-100 transition">

                Reset Filters

            </a>

        </div>

    </form>

</div>

<script>

document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('filtersForm');
    const search = document.getElementById('searchInput');

    // Search only when Enter is pressed
    search.addEventListener('keydown', function(e){

        if(e.key === 'Enter'){
            e.preventDefault();
            form.submit();
        }

    });

    // Also search when user leaves the textbox
    search.addEventListener('blur', function(){

        form.submit();

    });

    // Dropdowns submit instantly
    document.querySelectorAll('.filter-select').forEach(select => {

        select.addEventListener('change', () => {

            form.submit();

        });

    });

});

</script>