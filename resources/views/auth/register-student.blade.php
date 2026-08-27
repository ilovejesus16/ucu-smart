<x-layouts.auth>

    <h2 class="text-3xl sm:text-4xl font-bold text-center text-[#0E2958]">
        Student Registration
    </h2>

    <p class="text-center text-sm sm:text-base text-gray-500 mt-2 mb-8">
        Create your UCU Smart+ student account.
    </p>

    <form method="POST" action="{{ route('register.student.store') }}">

        @csrf

        <!-- Student ID -->

        <div class="mb-5">

            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Student ID
            </label>

            <input
                type="text"
                name="student_id"
                value="{{ old('student_id') }}"
                required
                maxlength="8"
                minlength="8"
                pattern="[0-9]{8}"
                inputmode="numeric"
                oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,8)"
                class="w-full rounded-xl border border-gray-300 px-4 sm:px-5 py-3 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

            @error('student_id')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror

        </div>

        <!-- First & Last Name -->

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">

            <div>

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    First Name
                </label>

                <input
                    type="text"
                    name="first_name"
                    value="{{ old('first_name') }}"
                    required
                    class="w-full rounded-xl border border-gray-300 px-4 sm:px-5 py-3 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

                @error('first_name')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror

            </div>

            <div>

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Last Name
                </label>

                <input
                    type="text"
                    name="last_name"
                    value="{{ old('last_name') }}"
                    required
                    class="w-full rounded-xl border border-gray-300 px-4 sm:px-5 py-3 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

                @error('last_name')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror

            </div>

        </div>

        <!-- Course -->

        <div class="mb-5">

            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Course
            </label>

            <select
                name="course"
                required
                class="w-full rounded-xl border border-gray-300 px-4 sm:px-5 py-3 bg-white focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

                <option value="">Select your course</option>

                <optgroup label="🎓 Graduate & Post Graduate Programs">

                    <option>Doctor of Education Major in Educational Management</option>
                    <option>Master of Arts in Education Major in Educational Management</option>
                    <option>Master of Arts in Early Childhood Education</option>
                    <option>Master of Arts in Special Education</option>
                    <option>Master in Business Administration</option>
                    <option>Master in Public Administration</option>
                    <option>Master of Arts in Nursing</option>
                    <option>Juris Doctor</option>

                </optgroup>

                <optgroup label="🎓 Business & Accountancy">

                    <option>Bachelor of Science in Accountancy</option>
                    <option>BSBA - Financial Management</option>
                    <option>BSBA - Human Resource Management</option>
                    <option>BSBA - Marketing Management</option>
                    <option>BSBA - Management Accounting</option>
                    <option>Bachelor of Science in Office Administration</option>

                </optgroup>

                <optgroup label="📚 Education">

                    <option>Bachelor of Elementary Education</option>
                    <option>Bachelor of Early Childhood Education</option>
                    <option>Bachelor of Special Needs Education</option>
                    <option>BSEd - English</option>
                    <option>BSEd - Filipino</option>
                    <option>BSEd - Mathematics</option>
                    <option>BSEd - Science</option>
                    <option>BSEd - Social Studies</option>
                    <option>Bachelor of Physical Education</option>
                    <option>Bachelor of Culture & Arts Education</option>

                </optgroup>

                <optgroup label="💙 Health Sciences">

                    <option>Bachelor of Science in Nursing</option>
                    <option>Bachelor of Science in Pharmacy</option>
                    <option>Bachelor of Science in Midwifery</option>
                    <option>Bachelor of Science in Social Work</option>
                    <option>Bachelor of Science in Psychology</option>

                </optgroup>

                <optgroup label="💻 Computing & Technology">

                    <option>Bachelor of Science in Information Technology</option>
                    <option>Bachelor of Science in Computer Engineering</option>
                    <option>Bachelor in Library & Information Science</option>

                </optgroup>

                <optgroup label="🏗 Engineering & Architecture">

                    <option>Bachelor of Science in Architecture</option>
                    <option>Bachelor of Science in Civil Engineering</option>
                    <option>Bachelor of Science in Electrical Engineering</option>
                    <option>Bachelor of Science in Electronics Engineering</option>

                </optgroup>

                <optgroup label="🏨 Hospitality & Tourism">

                    <option>Bachelor of Science in Hotel Management</option>
                    <option>Bachelor of Science in Tourism Management</option>

                </optgroup>

                <optgroup label="⚖ Public Safety & Social Sciences">

                    <option>Bachelor of Science in Criminology</option>
                    <option>Bachelor of Arts in Communication</option>

                </optgroup>

                <optgroup label="🛠 TESDA / Short Term Courses">

                    <option>Caregiving NC II</option>
                    <option>Housekeeping NC II</option>
                    <option>Bookkeeping NC III</option>
                    <option>Food and Beverage Services NC II</option>
                    <option>Bread and Pastry Production NC II</option>
                    <option>Computer Systems Servicing NC II</option>

                </optgroup>

            </select>

            @error('course')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror

        </div>

        <!-- Email -->

        <div class="mb-5">

            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Email Address
            </label>

            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                class="w-full rounded-xl border border-gray-300 px-4 sm:px-5 py-3 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

            @error('email')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror

        </div>

        <!-- Password -->

        <div class="mb-5">

            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Password
            </label>

            <input
                type="password"
                name="password"
                required
                class="w-full rounded-xl border border-gray-300 px-4 sm:px-5 py-3 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

            @error('password')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror

        </div>

        <!-- Confirm Password -->

        <div class="mb-7">

            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Confirm Password
            </label>

            <input
                type="password"
                name="password_confirmation"
                required
                class="w-full rounded-xl border border-gray-300 px-4 sm:px-5 py-3 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

        </div>

        <!-- Register Button -->

        <button
            type="submit"
            class="w-full bg-[#0E4C6B] hover:bg-[#0B3D56] text-white font-bold py-3 rounded-xl transition">

            REGISTER

        </button>

        <!-- Login Link -->

        <div class="mt-6 text-center">

            <p class="text-sm text-gray-500">

                Already have an account?

                <a
                    href="{{ route('login') }}"
                    class="font-semibold text-[#0E4C6B] hover:underline">

                    Login

                </a>

            </p>

        </div>

    </form>

</x-layouts.auth>