@if(auth()->user()->hasAdminPermission('user_management', 'full'))
<div
    id="editModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">

        {{-- Modal Header --}}
        <div class="flex items-center justify-between p-6 border-b">
            <h2 class="text-xl font-bold text-slate-800">
                Edit User
            </h2>

            <button
                type="button"
                class="close-edit-modal text-2xl text-slate-500 hover:text-slate-700"
                aria-label="Close">
                &times;
            </button>
        </div>

        {{-- Edit User Form --}}
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 p-6">

                {{-- First Name --}}
                <div>
                    <label for="editFirstName" class="block text-sm font-medium mb-2">
                        First Name
                    </label>
                    <input
                        type="text"
                        id="editFirstName"
                        name="first_name"
                        class="w-full rounded-xl border-slate-300 focus:ring-[#0E4C6B] focus:border-[#0E4C6B]">
                </div>

                {{-- Last Name --}}
                <div>
                    <label for="editLastName" class="block text-sm font-medium mb-2">
                        Last Name
                    </label>
                    <input
                        type="text"
                        id="editLastName"
                        name="last_name"
                        class="w-full rounded-xl border-slate-300 focus:ring-[#0E4C6B] focus:border-[#0E4C6B]">
                </div>

                {{-- Email --}}
                <div>
                    <label for="editEmail" class="block text-sm font-medium mb-2">
                        Email
                    </label>
                    <input
                        type="email"
                        id="editEmail"
                        name="email"
                        class="w-full rounded-xl border-slate-300 focus:ring-[#0E4C6B] focus:border-[#0E4C6B]">
                </div>

                {{-- Status --}}
                <div>
                    <label for="editStatus" class="block text-sm font-medium mb-2">
                        Status
                    </label>
                    <select
                        id="editStatus"
                        name="status"
                        class="w-full rounded-xl border-slate-300">
                        <option value="active">Active</option>
                        <option value="pending">Pending</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>

                {{-- Department / Course --}}
                <div class="md:col-span-2">
                    <label for="editDepartment" class="block text-sm font-medium mb-2">
                        Course / Department
                    </label>
                    <input
                        type="text"
                        id="editDepartment"
                        name="department"
                        class="w-full rounded-xl border-slate-300">
                </div>

            </div>

            {{-- Administrator Permissions --}}
            <div
                id="editAdminPermissionsSection"
                class="hidden border-t border-slate-200 px-6 py-5">

                <h3 class="text-base font-bold text-slate-800">
                    Administrator Permissions
                </h3>

                <p class="mt-1 mb-4 text-sm text-slate-500">
                    Choose what this administrator can access.
                </p>

                <div class="space-y-3">

                    @foreach (\App\Support\AdminPermissions::FEATURES as $key => $label)

                        <div class="grid grid-cols-2 items-center gap-4">

                            <label
                                for="edit_permission_{{ $key }}"
                                class="text-sm font-medium text-slate-700">

                                {{ $label }}

                            </label>

                            <select
                                id="edit_permission_{{ $key }}"
                                name="permissions[{{ $key }}]"
                                class="w-full rounded-xl border-slate-300 text-sm">

                                @foreach (\App\Support\AdminPermissions::LEVELS as $value => $description)

                                    <option value="{{ $value }}">
                                        {{ $description }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                    @endforeach

                </div>
            </div>

            {{-- Footer --}}
            <div class="flex justify-end gap-3 px-6 py-4 border-t">

                <button
                    type="button"
                    class="close-edit-modal px-5 py-2 rounded-xl border">
                    Cancel
                </button>

                <button
                    type="submit"
                    class="px-5 py-2 rounded-xl bg-[#0E4C6B] text-white hover:bg-[#08344a]">
                    Save Changes
                </button>

            </div>

        </form>
    </div>
</div>
@endif
