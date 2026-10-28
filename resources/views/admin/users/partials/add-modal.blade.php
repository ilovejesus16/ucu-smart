
@if(auth()->user()->hasAdminPermission('user_management', 'full'))

<div
    id="addUserModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl mx-4 max-h-[90vh] overflow-y-auto">

        {{-- Modal Header --}}
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-2xl font-bold text-slate-800">
                Add User
            </h2>

            <p class="mt-1 text-slate-500">
                Create a new student, instructor, or administrator.
            </p>
        </div>

        {{-- Add User Form --}}
        <form
            action="{{ route('admin.users.store') }}"
            method="POST"
            id="addUserForm">

            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 p-6">

                {{-- Role --}}
                <div>
                    <label for="roleSelect" class="block text-sm font-semibold text-slate-700 mb-2">
                        Role
                    </label>

                    <select
                        id="roleSelect"
                        name="role"
                        class="w-full rounded-xl border-slate-300">

                        <option value="student">Student</option>
                        <option value="instructor">Instructor</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Status
                    </label>

                    <select
                        name="status"
                        class="w-full rounded-xl border-slate-300">

                        <option value="active">Active</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>

                {{-- Username --}}
                <div id="usernameField" class="hidden">
                    <label for="usernameInput" class="block text-sm font-semibold text-slate-700 mb-2">
                        Username
                    </label>

                    <input
                        type="text"
                        name="username"
                        id="usernameInput"
                        class="w-full rounded-xl border-slate-300">
                </div>

                {{-- Student ID --}}
                <div id="studentField">
                    <label for="studentIdInput" class="block text-sm font-semibold text-slate-700 mb-2">
                        Student ID
                    </label>

                    <input
                        type="text"
                        name="student_id"
                        id="studentIdInput"
                        class="w-full rounded-xl border-slate-300">
                </div>

                {{-- Employee ID --}}
                <div id="employeeField" class="hidden">
                    <label for="employeeIdInput" class="block text-sm font-semibold text-slate-700 mb-2">
                        Employee ID
                    </label>

                    <input
                        type="text"
                        name="employee_id"
                        id="employeeIdInput"
                        class="w-full rounded-xl border-slate-300">
                </div>

                {{-- Administrator Name --}}
                <div id="adminNameField" class="hidden md:col-span-2">
                    <label for="adminNameInput" class="block text-sm font-semibold text-slate-700 mb-2">
                        Name
                    </label>

                    <input
                        type="text"
                        id="adminNameInput"
                        placeholder="Enter administrator name"
                        class="w-full rounded-xl border-slate-300">
                </div>

                {{-- First Name --}}
                <div id="firstNameField">
                    <label for="firstNameInput" class="block text-sm font-semibold text-slate-700 mb-2">
                        First Name
                    </label>

                    <input
                        type="text"
                        name="first_name"
                        id="firstNameInput"
                        class="w-full rounded-xl border-slate-300">
                </div>

                {{-- Last Name --}}
                <div id="lastNameField">
                    <label for="lastNameInput" class="block text-sm font-semibold text-slate-700 mb-2">
                        Last Name
                    </label>

                    <input
                        type="text"
                        name="last_name"
                        id="lastNameInput"
                        class="w-full rounded-xl border-slate-300">
                </div>

                {{-- Course --}}
                <div id="courseField">
                    <label for="courseInput" class="block text-sm font-semibold text-slate-700 mb-2">
                        Course
                    </label>

                    <input
                        type="text"
                        name="course"
                        id="courseInput"
                        class="w-full rounded-xl border-slate-300">
                </div>

                {{-- Department --}}
                <div id="departmentField" class="hidden">
                    <label for="departmentInput" class="block text-sm font-semibold text-slate-700 mb-2">
                        Department
                    </label>

                    <input
                        type="text"
                        name="department"
                        id="departmentInput"
                        class="w-full rounded-xl border-slate-300">
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        required
                        class="w-full rounded-xl border-slate-300">
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        required
                        class="w-full rounded-xl border-slate-300">
                </div>

            </div>

            {{-- Administrator Permissions --}}
            <div
                id="adminPermissionsSection"
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
                                for="permission_{{ $key }}"
                                class="text-sm font-medium text-slate-700">

                                {{ $label }}
                            </label>

                            <select
                                id="permission_{{ $key }}"
                                name="permissions[{{ $key }}]"
                                class="admin-permission-select w-full rounded-xl border-slate-300 text-sm">

                                @foreach (\App\Support\AdminPermissions::LEVELS as $value => $description)

                                    <option
                                        value="{{ $value }}"
                                        @selected(
                                            ($key === 'dashboard' && $value === 'full') ||
                                            ($key === 'user_management' && $value === 'full') ||
                                            ($key === 'schedule_management' && $value === 'full') ||
                                            (in_array($key, ['buildings', 'rooms', 'reports', 'activity_logs'], true) && $value === 'view') ||
                                            ($key === 'campus_map' && $value === 'none')
                                        )>

                                        {{ $description }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                    @endforeach

                </div>
            </div>

            {{-- Footer --}}
            <div class="border-t border-slate-200 p-6 flex justify-end gap-3">

                <button
                    type="button"
                    class="close-add-user-modal px-5 py-3 rounded-xl border border-slate-300 hover:bg-slate-100 transition">

                    Cancel
                </button>

                <button
                    type="submit"
                    class="px-5 py-3 rounded-xl bg-[#0E4C6B] text-white hover:bg-[#0B3D56] transition">

                    Add User
                </button>

            </div>

        </form>

    </div>
</div>

@endif


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const roleSelect = document.getElementById('roleSelect');
    const adminPermissionsSection = document.getElementById('adminPermissionsSection');

    const usernameField = document.getElementById('usernameField');
    const studentField = document.getElementById('studentField');
    const employeeField = document.getElementById('employeeField');
    const adminNameField = document.getElementById('adminNameField');
    const firstNameField = document.getElementById('firstNameField');
    const lastNameField = document.getElementById('lastNameField');
    const courseField = document.getElementById('courseField');
    const departmentField = document.getElementById('departmentField');

    const usernameInput = document.getElementById('usernameInput');
    const studentIdInput = document.getElementById('studentIdInput');
    const employeeIdInput = document.getElementById('employeeIdInput');
    const adminNameInput = document.getElementById('adminNameInput');
    const firstNameInput = document.getElementById('firstNameInput');
    const lastNameInput = document.getElementById('lastNameInput');

    function showElement(element) {
        if (element) {
            element.classList.remove('hidden');
        }
    }

    function hideElement(element) {
        if (element) {
            element.classList.add('hidden');
        }
    }

    function updateRoleFields() {
        if (!roleSelect) {
            return;
        }

        const role = roleSelect.value;

        showElement(adminPermissionsSection);

        // STUDENT
        if (role === 'student') {
            hideElement(usernameField);
            showElement(studentField);
            hideElement(employeeField);
            hideElement(adminPermissionsSection);
            hideElement(adminNameField);

            showElement(firstNameField);
            showElement(lastNameField);
            showElement(courseField);
            hideElement(departmentField);

            if (usernameInput) usernameInput.required = false;
            if (studentIdInput) studentIdInput.required = true;
            if (employeeIdInput) employeeIdInput.required = false;
            if (adminNameInput) adminNameInput.required = false;
            if (firstNameInput) firstNameInput.required = true;
            if (lastNameInput) lastNameInput.required = true;
        }

        // INSTRUCTOR
        else if (role === 'instructor') {
            hideElement(usernameField);
            hideElement(studentField);
            showElement(employeeField);
            hideElement(adminNameField);

            showElement(firstNameField);
            showElement(lastNameField);
            hideElement(courseField);
            showElement(departmentField);
            hideElement(adminPermissionsSection);

            if (usernameInput) usernameInput.required = false;
            if (studentIdInput) studentIdInput.required = false;
            if (employeeIdInput) employeeIdInput.required = true;
            if (adminNameInput) adminNameInput.required = false;
            if (firstNameInput) firstNameInput.required = true;
            if (lastNameInput) lastNameInput.required = true;
        }

        // ADMINISTRATOR
        else if (role === 'admin') {
            showElement(usernameField);
            hideElement(studentField);
            hideElement(employeeField);
            showElement(adminNameField);

            hideElement(firstNameField);
            hideElement(lastNameField);
            hideElement(courseField);
            hideElement(departmentField);
            showElement(adminPermissionsSection);

            if (usernameInput) usernameInput.required = true;
            if (studentIdInput) studentIdInput.required = false;
            if (employeeIdInput) employeeIdInput.required = false;
            if (adminNameInput) adminNameInput.required = true;
            if (firstNameInput) firstNameInput.required = false;
            if (lastNameInput) lastNameInput.required = false;
        }
    }

    if (roleSelect) {
        roleSelect.addEventListener('change', updateRoleFields);
        updateRoleFields();
    }

    // Split administrator's full name into first_name and last_name.
    const addUserForm = document.getElementById('addUserForm');

    if (addUserForm) {
        addUserForm.addEventListener('submit', function () {

            if (!roleSelect || roleSelect.value !== 'admin' || !adminNameInput) {
                return;
            }

            const fullName = adminNameInput.value.trim();
            const nameParts = fullName.split(/\s+/).filter(Boolean);

            if (nameParts.length === 1) {
                if (firstNameInput) firstNameInput.value = nameParts[0];
                if (lastNameInput) lastNameInput.value = nameParts[0];
            } else if (nameParts.length > 1) {
                if (firstNameInput) firstNameInput.value = nameParts.shift();
                if (lastNameInput) lastNameInput.value = nameParts.join(' ');
            }
        });
    }
});
</script>
@endpush
