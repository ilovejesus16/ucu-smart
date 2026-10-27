<div id="addUserModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl mx-4">

        <div class="p-6 border-b border-slate-200">

            <h2 class="text-2xl font-bold text-slate-800">
                Add User
            </h2>

            <p class="mt-1 text-slate-500">
                Create a new student, instructor, or administrator.
            </p>

        </div>

        <form
            action="{{ route('admin.users.store') }}"
            method="POST"
            id="addUserForm">

            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 p-6">

                <!-- Role -->

                <div>

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Role
                    </label>

                    <select
                        id="roleSelect"
                        name="role"
                        class="w-full rounded-xl border-slate-300">

                        <option value="student">
                            Student
                        </option>

                        <option value="instructor">
                            Instructor
                        </option>

                        <option value="admin">
                            Administrator
                        </option>

                    </select>

                </div>


                <!-- Status -->

                <div>

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Status
                    </label>

                    <select
                        name="status"
                        class="w-full rounded-xl border-slate-300">

                        <option value="active">
                            Active
                        </option>

                        <option value="pending">
                            Pending
                        </option>

                    </select>

                </div>


                <!-- Username -->

                <div id="usernameField" class="hidden">

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Username
                    </label>

                    <input
                        type="text"
                        name="username"
                        id="usernameInput"
                        class="w-full rounded-xl border-slate-300">

                </div>


                <!-- Student ID -->

                <div id="studentField">

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Student ID
                    </label>

                    <input
                        type="text"
                        name="student_id"
                        id="studentIdInput"
                        class="w-full rounded-xl border-slate-300">

                </div>


                <!-- Employee ID -->

                <div id="employeeField" class="hidden">

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Employee ID
                    </label>

                    <input
                        type="text"
                        name="employee_id"
                        id="employeeIdInput"
                        class="w-full rounded-xl border-slate-300">

                </div>


                <!-- Administrator Name -->

                <div
                    id="adminNameField"
                    class="hidden md:col-span-2">

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Name
                    </label>

                    <input
                        type="text"
                        id="adminNameInput"
                        placeholder="Enter administrator name"
                        class="w-full rounded-xl border-slate-300">

                </div>


                <!-- First Name -->

                <div id="firstNameField">

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        First Name
                    </label>

                    <input
                        type="text"
                        name="first_name"
                        id="firstNameInput"
                        class="w-full rounded-xl border-slate-300">

                </div>


                <!-- Last Name -->

                <div id="lastNameField">

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Last Name
                    </label>

                    <input
                        type="text"
                        name="last_name"
                        id="lastNameInput"
                        class="w-full rounded-xl border-slate-300">

                </div>


                <!-- Course -->

                <div id="courseField">

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Course
                    </label>

                    <input
                        type="text"
                        name="course"
                        id="courseInput"
                        class="w-full rounded-xl border-slate-300">

                </div>


                <!-- Department -->

                <div id="departmentField" class="hidden">

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Department
                    </label>

                    <input
                        type="text"
                        name="department"
                        id="departmentInput"
                        class="w-full rounded-xl border-slate-300">

                </div>


                <!-- Email -->

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


                <!-- Password -->

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


            <!-- Footer -->

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


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const roleSelect =
        document.getElementById('roleSelect');

    const usernameField =
        document.getElementById('usernameField');

    const studentField =
        document.getElementById('studentField');

    const employeeField =
        document.getElementById('employeeField');

    const adminNameField =
        document.getElementById('adminNameField');

    const firstNameField =
        document.getElementById('firstNameField');

    const lastNameField =
        document.getElementById('lastNameField');

    const courseField =
        document.getElementById('courseField');

    const departmentField =
        document.getElementById('departmentField');

    const usernameInput =
        document.getElementById('usernameInput');

    const studentIdInput =
        document.getElementById('studentIdInput');

    const employeeIdInput =
        document.getElementById('employeeIdInput');

    const adminNameInput =
        document.getElementById('adminNameInput');

    const firstNameInput =
        document.getElementById('firstNameInput');

    const lastNameInput =
        document.getElementById('lastNameInput');


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

        const role = roleSelect.value;


        /*
        |--------------------------------------------------------------------------
        | STUDENT
        |--------------------------------------------------------------------------
        */

        if (role === 'student') {

            hideElement(usernameField);
            showElement(studentField);
            hideElement(employeeField);

            hideElement(adminNameField);

            showElement(firstNameField);
            showElement(lastNameField);

            showElement(courseField);
            hideElement(departmentField);

            usernameInput.required = false;

            studentIdInput.required = true;
            employeeIdInput.required = false;

            adminNameInput.required = false;

            firstNameInput.required = true;
            lastNameInput.required = true;

        }


        /*
        |--------------------------------------------------------------------------
        | INSTRUCTOR
        |--------------------------------------------------------------------------
        */

        else if (role === 'instructor') {

            hideElement(usernameField);
            hideElement(studentField);
            showElement(employeeField);

            hideElement(adminNameField);

            showElement(firstNameField);
            showElement(lastNameField);

            hideElement(courseField);
            showElement(departmentField);

            usernameInput.required = false;

            studentIdInput.required = false;
            employeeIdInput.required = true;

            adminNameInput.required = false;

            firstNameInput.required = true;
            lastNameInput.required = true;

        }


        /*
        |--------------------------------------------------------------------------
        | ADMINISTRATOR
        |--------------------------------------------------------------------------
        */

        else if (role === 'admin') {

            showElement(usernameField);

            hideElement(studentField);
            hideElement(employeeField);

            showElement(adminNameField);

            hideElement(firstNameField);
            hideElement(lastNameField);

            hideElement(courseField);
            hideElement(departmentField);

            usernameInput.required = true;

            studentIdInput.required = false;
            employeeIdInput.required = false;

            adminNameInput.required = true;

            firstNameInput.required = false;
            lastNameInput.required = false;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Role Change
    |--------------------------------------------------------------------------
    */

    if (roleSelect) {

        roleSelect.addEventListener(
            'change',
            updateRoleFields
        );

        updateRoleFields();

    }


    /*
    |--------------------------------------------------------------------------
    | Administrator Name
    |
    | The existing database still uses first_name and last_name.
    | We split the single Name field before submitting.
    |--------------------------------------------------------------------------
    */

    const addUserForm =
        document.getElementById('addUserForm');


    if (addUserForm) {

        addUserForm.addEventListener(
            'submit',
            function () {

                if (roleSelect.value !== 'admin') {
                    return;
                }


                const fullName =
                    adminNameInput.value.trim();


                const nameParts =
                    fullName.split(/\s+/);


                if (nameParts.length === 1) {

                    firstNameInput.value =
                        nameParts[0];

                    lastNameInput.value =
                        nameParts[0];

                } else {

                    firstNameInput.value =
                        nameParts.shift();

                    lastNameInput.value =
                        nameParts.join(' ');

                }

            }
        );

    }

});

</script>

@endpush