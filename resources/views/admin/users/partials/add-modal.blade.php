
@if(auth()->user()->hasAdminPermission('user_management', 'full'))

<div
    id="addUserModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm"
>
    <div class="flex max-h-[92vh] w-full max-w-3xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl">

        {{-- Header --}}
        <div class="flex shrink-0 items-center justify-between border-b border-slate-100 px-6 py-5">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Add User</h2>
                <p class="mt-1 text-sm text-slate-500">
                    Create a new student, instructor, or administrator.
                </p>
            </div>

            <button
                type="button"
                class="close-add-user-modal rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                aria-label="Close modal"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Form --}}
        <form
            action="{{ route('admin.users.store') }}"
            method="POST"
            id="addUserForm"
            class="flex min-h-0 flex-1 flex-col"
        >
            @csrf

            <input type="hidden" name="_form" value="add_user">
            <input type="hidden" name="status" value="active">

            {{-- Validation Errors --}}
            @if ($errors->any() && old('_form') === 'add_user')
                <div
                    data-add-user-errors
                    class="mx-6 mt-5 rounded-xl border border-red-200 bg-red-50 p-4"
                >
                    <div class="flex items-start gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                <path d="M12 8v4m0 4h.01" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-red-800">
                                Unable to add user
                            </p>
                            <p class="mt-1 text-xs text-red-600">
                                Please review the following issues.
                            </p>

                            <ul class="mt-3 space-y-2">
                                @foreach ($errors->all() as $error)
                                    <li class="flex items-start gap-2 text-sm text-red-700">
                                        <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-red-500"></span>
                                        <span>{{ $error }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Scrollable Form Content --}}
            <div class="min-h-0 flex-1 space-y-6 overflow-y-auto px-6 py-6">

                {{-- Role --}}
                <div>
                    <label for="roleSelect" class="mb-2 block text-sm font-semibold text-slate-700">
                        Account Role
                    </label>

                    <select
                        id="roleSelect"
                        name="role"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-[#0E4C6B] focus:ring-2 focus:ring-[#0E4C6B]/20"
                    >
                        <option value="student" @selected(old('role', 'student') === 'student')>Student</option>
                        <option value="instructor" @selected(old('role') === 'instructor')>Instructor</option>
                        <option value="admin" @selected(old('role') === 'admin')>Administrator</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                    {{-- Username --}}
                    <div id="usernameField" class="hidden">
                        <label for="usernameInput" class="mb-2 block text-sm font-semibold text-slate-700">
                            Username
                        </label>
                        <input
                            type="text"
                            name="username"
                            id="usernameInput"
                            value="{{ old('username') }}"
                            autocomplete="off"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-[#0E4C6B] focus:ring-2 focus:ring-[#0E4C6B]/20"
                        >
                    </div>

                    {{-- Student ID --}}
                    <div id="studentField">
                        <label for="studentIdInput" class="mb-2 block text-sm font-semibold text-slate-700">
                            Student ID
                        </label>
                        <input
                            type="text"
                            name="student_id"
                            id="studentIdInput"
                            value="{{ old('student_id') }}"
                            maxlength="8"
                            minlength="8"
                            pattern="[0-9]{8}"
                            inputmode="numeric"
                            oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,8)"
                            placeholder="e.g. 20230531"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-[#0E4C6B] focus:ring-2 focus:ring-[#0E4C6B]/20"
                        >
                    </div>

                    {{-- Employee ID --}}
                    <div id="employeeField" class="hidden">
                        <label for="employeeIdInput" class="mb-2 block text-sm font-semibold text-slate-700">
                            Employee ID
                        </label>
                        <input
                            type="text"
                            name="employee_id"
                            id="employeeIdInput"
                            value="{{ old('employee_id') }}"
                            placeholder="e.g. JOTT0035"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-[#0E4C6B] focus:ring-2 focus:ring-[#0E4C6B]/20"
                        >
                    </div>

                    {{-- Administrator Full Name --}}
                    <div id="adminNameField" class="hidden md:col-span-2">
                        <label for="adminNameInput" class="mb-2 block text-sm font-semibold text-slate-700">
                            Full Name
                        </label>
                        <input
                            type="text"
                            id="adminNameInput"
                            value="{{ trim(old('first_name', '') . ' ' . old('last_name', '')) }}"
                            placeholder="Enter administrator's full name"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-[#0E4C6B] focus:ring-2 focus:ring-[#0E4C6B]/20"
                        >
                        <p class="mt-1.5 text-xs text-slate-500">
                            First and last names will be saved automatically.
                        </p>
                    </div>

                    {{-- First Name --}}
                    <div id="firstNameField">
                        <label for="firstNameInput" class="mb-2 block text-sm font-semibold text-slate-700">
                            First Name
                        </label>
                        <input
                            type="text"
                            name="first_name"
                            id="firstNameInput"
                            value="{{ old('first_name') }}"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-[#0E4C6B] focus:ring-2 focus:ring-[#0E4C6B]/20"
                        >
                    </div>

                    {{-- Last Name --}}
                    <div id="lastNameField">
                        <label for="lastNameInput" class="mb-2 block text-sm font-semibold text-slate-700">
                            Last Name
                        </label>
                        <input
                            type="text"
                            name="last_name"
                            id="lastNameInput"
                            value="{{ old('last_name') }}"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-[#0E4C6B] focus:ring-2 focus:ring-[#0E4C6B]/20"
                        >
                    </div>

                    {{-- Student Course --}}
                    <div id="courseField" class="md:col-span-2">
                        <label for="courseInput" class="mb-2 block text-sm font-semibold text-slate-700">
                            Course / Program
                        </label>

                        <div class="relative">
                            <select
                                name="course"
                                id="courseInput"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-[#0E4C6B] focus:ring-2 focus:ring-[#0E4C6B]/20"
                            >
                                <option value="">Select student's course</option>

                                <optgroup label="Graduate & Post Graduate Programs">
                                    @foreach ([
                                        'Doctor of Education Major in Educational Management',
                                        'Master of Arts in Education Major in Educational Management',
                                        'Master of Arts in Early Childhood Education',
                                        'Master of Arts in Special Education',
                                        'Master in Business Administration',
                                        'Master in Public Administration',
                                        'Master of Arts in Nursing',
                                        'Juris Doctor'
                                    ] as $course)
                                        <option value="{{ $course }}" @selected(old('course') === $course)>{{ $course }}</option>
                                    @endforeach
                                </optgroup>

                                <optgroup label="Business & Accountancy">
                                    @foreach ([
                                        'Bachelor of Science in Accountancy',
                                        'BSBA - Financial Management',
                                        'BSBA - Human Resource Management',
                                        'BSBA - Marketing Management',
                                        'BSBA - Management Accounting',
                                        'Bachelor of Science in Office Administration'
                                    ] as $course)
                                        <option value="{{ $course }}" @selected(old('course') === $course)>{{ $course }}</option>
                                    @endforeach
                                </optgroup>

                                <optgroup label="Education">
                                    @foreach ([
                                        'Bachelor of Elementary Education',
                                        'Bachelor of Early Childhood Education',
                                        'Bachelor of Special Needs Education',
                                        'BSEd - English',
                                        'BSEd - Filipino',
                                        'BSEd - Mathematics',
                                        'BSEd - Science',
                                        'BSEd - Social Studies',
                                        'Bachelor of Physical Education',
                                        'Bachelor of Culture & Arts Education'
                                    ] as $course)
                                        <option value="{{ $course }}" @selected(old('course') === $course)>{{ $course }}</option>
                                    @endforeach
                                </optgroup>

                                <optgroup label="Health Sciences">
                                    @foreach ([
                                        'Bachelor of Science in Nursing',
                                        'Bachelor of Science in Pharmacy',
                                        'Bachelor of Science in Midwifery',
                                        'Bachelor of Science in Social Work',
                                        'Bachelor of Science in Psychology'
                                    ] as $course)
                                        <option value="{{ $course }}" @selected(old('course') === $course)>{{ $course }}</option>
                                    @endforeach
                                </optgroup>

                                <optgroup label="Computing & Technology">
                                    @foreach ([
                                        'Bachelor of Science in Information Technology',
                                        'Bachelor of Science in Computer Engineering',
                                        'Bachelor in Library & Information Science'
                                    ] as $course)
                                        <option value="{{ $course }}" @selected(old('course') === $course)>{{ $course }}</option>
                                    @endforeach
                                </optgroup>

                                <optgroup label="Engineering & Architecture">
                                    @foreach ([
                                        'Bachelor of Science in Architecture',
                                        'Bachelor of Science in Civil Engineering',
                                        'Bachelor of Science in Electrical Engineering',
                                        'Bachelor of Science in Electronics Engineering'
                                    ] as $course)
                                        <option value="{{ $course }}" @selected(old('course') === $course)>{{ $course }}</option>
                                    @endforeach
                                </optgroup>

                                <optgroup label="Hospitality & Tourism">
                                    @foreach ([
                                        'Bachelor of Science in Hotel Management',
                                        'Bachelor of Science in Tourism Management'
                                    ] as $course)
                                        <option value="{{ $course }}" @selected(old('course') === $course)>{{ $course }}</option>
                                    @endforeach
                                </optgroup>

                                <optgroup label="Public Safety & Social Sciences">
                                    @foreach ([
                                        'Bachelor of Science in Criminology',
                                        'Bachelor of Arts in Communication'
                                    ] as $course)
                                        <option value="{{ $course }}" @selected(old('course') === $course)>{{ $course }}</option>
                                    @endforeach
                                </optgroup>

                                <optgroup label="TESDA / Short Term Courses">
                                    @foreach ([
                                        'Caregiving NC II',
                                        'Housekeeping NC II',
                                        'Bookkeeping NC III',
                                        'Food and Beverage Services NC II',
                                        'Bread and Pastry Production NC II',
                                        'Computer Systems Servicing NC II'
                                    ] as $course)
                                        <option value="{{ $course }}" @selected(old('course') === $course)>{{ $course }}</option>
                                    @endforeach
                                </optgroup>
                            </select>

                           
                        </div>

                        <p class="mt-1.5 text-xs text-slate-500">
                            Choose the student's official academic program.
                        </p>
                    </div>

                    {{-- Instructor Department --}}
                    <div id="departmentField" class="hidden md:col-span-2">
                        <label for="departmentInput" class="mb-2 block text-sm font-semibold text-slate-700">
                            Department
                        </label>
                        <input
                            type="text"
                            name="department"
                            id="departmentInput"
                            value="{{ old('department') }}"
                            placeholder="Enter instructor's department"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-[#0E4C6B] focus:ring-2 focus:ring-[#0E4C6B]/20"
                        >
                    </div>

                    {{-- Email --}}
                    <div class="md:col-span-2">
                        <label for="emailInput" class="mb-2 block text-sm font-semibold text-slate-700">
                            Email Address
                        </label>
                        <input
                            type="email"
                            name="email"
                            id="emailInput"
                            value="{{ old('email') }}"
                            required
                            placeholder="example@email.com"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-[#0E4C6B] focus:ring-2 focus:ring-[#0E4C6B]/20"
                        >
                        <p class="mt-1.5 text-xs text-slate-500">
                            The user will receive an email with a secure link to set their password.
                        </p>
                    </div>
                </div>

                {{-- Administrator Permissions --}}
                <section
                    id="adminPermissionsSection"
                    class="hidden rounded-xl border border-slate-200 bg-slate-50 p-5"
                >
                    <div class="mb-4">
                        <h3 class="text-base font-bold text-slate-800">
                            Administrator Permissions
                        </h3>
                        <p class="mt-1 text-sm text-slate-500">
                            Choose which sections this administrator can access.
                        </p>
                    </div>

                    <div class="space-y-3">
                        @foreach (\App\Support\AdminPermissions::FEATURES as $key => $label)
                            <div class="grid grid-cols-1 items-center gap-2 border-b border-slate-200 pb-3 last:border-0 last:pb-0 sm:grid-cols-[minmax(0,1fr)_180px] sm:gap-4">
                                <label
                                    for="permission_{{ $key }}"
                                    class="text-sm font-medium text-slate-700"
                                >
                                    {{ $label }}
                                </label>

                                <select
                                    id="permission_{{ $key }}"
                                    name="permissions[{{ $key }}]"
                                    class="admin-permission-select w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-[#0E4C6B] focus:ring-[#0E4C6B]"
                                >
                                    @foreach (\App\Support\AdminPermissions::LEVELS as $value => $description)
                                        <option
                                            value="{{ $value }}"
                                            @selected(
                                                old('permissions.' . $key) !== null
                                                    ? old('permissions.' . $key) === $value
                                                    : (
                                                        ($key === 'dashboard' && $value === 'full') ||
                                                        ($key === 'user_management' && $value === 'full') ||
                                                        ($key === 'schedule_management' && $value === 'full') ||
                                                        (in_array($key, ['buildings', 'rooms', 'reports', 'activity_logs'], true) && $value === 'view') ||
                                                        ($key === 'campus_map' && $value === 'none')
                                                    )
                                            )
                                        >
                                            {{ $description }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>

            {{-- Footer --}}
            <div class="flex shrink-0 items-center justify-end gap-3 border-t border-slate-200 bg-white px-6 py-4">
                <button
                    type="button"
                    class="close-add-user-modal rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="rounded-xl bg-[#0E4C6B] px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#0B3D56] focus:outline-none focus:ring-2 focus:ring-[#0E4C6B]/30"
                >
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

    const modal = document.getElementById('addUserModal');
    const form = document.getElementById('addUserForm');

    if (!modal || !form) return;

    const roleSelect = document.getElementById('roleSelect');

    const usernameField = document.getElementById('usernameField');
    const studentField = document.getElementById('studentField');
    const employeeField = document.getElementById('employeeField');
    const adminNameField = document.getElementById('adminNameField');
    const firstNameField = document.getElementById('firstNameField');
    const lastNameField = document.getElementById('lastNameField');
    const courseField = document.getElementById('courseField');
    const departmentField = document.getElementById('departmentField');
    const permissionsSection = document.getElementById('adminPermissionsSection');

    const usernameInput = document.getElementById('usernameInput');
    const studentIdInput = document.getElementById('studentIdInput');
    const employeeIdInput = document.getElementById('employeeIdInput');
    const adminNameInput = document.getElementById('adminNameInput');
    const firstNameInput = document.getElementById('firstNameInput');
    const lastNameInput = document.getElementById('lastNameInput');
    const courseInput = document.getElementById('courseInput');
    const departmentInput = document.getElementById('departmentInput');

    const permissionSelects = form.querySelectorAll('.admin-permission-select');

    function setField(field, visible, input, required = false) {
        if (!field) return;

        field.classList.toggle('hidden', !visible);

        if (input) {
            input.disabled = !visible;
            input.required = visible && required;

            if (!visible) {
                input.value = '';
            }
        }
    }

    function updateRoleFields() {
        const role = roleSelect.value;

        const isStudent = role === 'student';
        const isInstructor = role === 'instructor';
        const isAdmin = role === 'admin';

        setField(usernameField, isAdmin, usernameInput, true);
        setField(studentField, isStudent, studentIdInput, true);
        setField(employeeField, isInstructor, employeeIdInput, true);

        setField(adminNameField, isAdmin, adminNameInput, true);

        if (firstNameField) firstNameField.classList.toggle('hidden', isAdmin);
        if (lastNameField) lastNameField.classList.toggle('hidden', isAdmin);

        // First and last name remain enabled because the administrator's
        // full name is split into these fields before form submission.
        firstNameInput.required = !isAdmin;
        lastNameInput.required = !isAdmin;

        setField(courseField, isStudent, courseInput, true);
        setField(departmentField, isInstructor, departmentInput, true);

        if (permissionsSection) {
            permissionsSection.classList.toggle('hidden', !isAdmin);
        }

        permissionSelects.forEach(select => {
            select.disabled = !isAdmin;
        });
    }

    roleSelect.addEventListener('change', updateRoleFields);
    updateRoleFields();

    // Split administrator full name into first and last name.
    form.addEventListener('submit', function () {
        if (roleSelect.value !== 'admin') return;

        const parts = adminNameInput.value.trim().split(/\s+/).filter(Boolean);

        firstNameInput.value = parts.shift() || '';
        lastNameInput.value = parts.join(' ');
    });

    // Remove validation message when the modal is closed.
    function clearAddUserErrors() {
        const errorBox = form.querySelector('[data-add-user-errors]');

        if (errorBox) {
            errorBox.remove();
        }
    }

    function closeModal() {
        clearAddUserErrors();
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    modal.querySelectorAll('.close-add-user-modal').forEach(button => {
        button.addEventListener('click', closeModal);
    });

    // Close when clicking the backdrop.
    modal.addEventListener('click', function (event) {
        if (event.target === modal) {
            closeModal();
        }
    });

    // Reopen modal after Laravel validation redirects back.
    @if ($errors->any() && old('_form') === 'add_user')
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    @endif

});
</script>
@endpush
