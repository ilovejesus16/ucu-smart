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
            method="POST">

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

                        <option value="student">Student</option>

                        <option value="instructor">Instructor</option>

                        <option value="admin">Administrator</option>

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

                        <option value="active">Active</option>

                        <option value="pending">Pending</option>

                    </select>

                </div>

                <!-- Admin ID -->
                 <div id="usernameField" class="hidden">

    <label class="block text-sm font-semibold text-slate-700 mb-2">
        Username
    </label>

    <input
        type="text"
        name="username"
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
                        class="w-full rounded-xl border-slate-300">

                </div>

                <!-- First Name -->

                <div>

                    <label class="block text-sm font-semibold text-slate-700 mb-2">

                        First Name

                    </label>

                    <input
                        type="text"
                        name="first_name"
                        required
                        class="w-full rounded-xl border-slate-300">

                </div>

                <!-- Last Name -->

                <div>

                    <label class="block text-sm font-semibold text-slate-700 mb-2">

                        Last Name

                    </label>

                    <input
                        type="text"
                        name="last_name"
                        required
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

            <div class="border-t border-slate-200 p-6 flex justify-end gap-3">

                <button
                    type="button"
                    class="close-add-user-modal px-5 py-3 rounded-xl border border-slate-300 hover:bg-slate-100">

                    Cancel

                </button>

                <button
                    type="submit"
                    class="px-5 py-3 rounded-xl bg-[#0E4C6B] text-white hover:bg-[#0B3D56]">

                    Add User

                </button>

            </div>

        </form>

    </div>

</div>