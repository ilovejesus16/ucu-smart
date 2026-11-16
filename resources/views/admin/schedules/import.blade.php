@extends('layouts.admin')

@section('title', 'Import Schedule')

@section('content')

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
                Import Class Schedule
            </h1>

            <p class="text-gray-500 mt-1">
                Upload the official Registrar class schedule.
            </p>
        </div>

        <a href="{{ route('admin.schedules') }}"
           class="w-full sm:w-auto text-center bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-3 rounded-xl">
            ← Back
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <!-- Card Header -->
        <div class="bg-[#0E4C6B] px-4 sm:px-6 lg:px-8 py-6">
            <h2 class="text-xl sm:text-2xl font-bold text-white">
                Excel Schedule Import
            </h2>

            <p class="text-blue-100 mt-1 text-sm sm:text-base">
                Supported formats: XLSX, XLS and CSV.
            </p>
        </div>

        <div class="p-4 sm:p-6 lg:p-8">

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 rounded-xl bg-green-100 border border-green-200 text-green-700 p-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Validation Errors -->
            @if($errors->any())
                <div class="mb-6 rounded-xl bg-red-100 border border-red-200 text-red-700 p-4">
                    <ul class="list-disc ml-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                action="{{ route('admin.schedules.import') }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf

                <!-- Semester -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Semester <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="semester"
                        required
                        class="w-full rounded-xl border-gray-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">

                        <option value="">Select Semester</option>

                        <option value="1st Semester"
                            {{ old('semester') == '1st Semester' ? 'selected' : '' }}>
                            1st Semester
                        </option>

                        <option value="2nd Semester"
                            {{ old('semester') == '2nd Semester' ? 'selected' : '' }}>
                            2nd Semester
                        </option>

                    </select>
                </div>

                <!-- School Year -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        School Year <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="school_year"
                        value="{{ old('school_year') }}"
                        placeholder="Example: 2026-2027"
                        required
                        maxlength="20"
                        class="w-full rounded-xl border-gray-300 focus:border-[#0E4C6B] focus:ring-[#0E4C6B]">
                </div>

                <!-- File Upload -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Registrar Schedule File <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="file"
                        name="file"
                        accept=".xlsx,.xls,.csv"
                        required
                        class="block w-full rounded-xl border border-gray-300
                               file:mr-4 file:rounded-lg file:border-0
                               file:bg-[#0E4C6B] file:px-4 file:py-2
                               file:text-white hover:file:bg-[#0B3D56]">

                    <p class="text-xs text-gray-500 mt-2">
                        Upload the official Registrar Excel or CSV file.
                    </p>
                </div>

                <!-- Expected Columns -->
                <div class="mb-8 bg-blue-50 border border-blue-100 rounded-xl p-4">
                    <h3 class="font-semibold text-[#0E4C6B] mb-2">
                        Required Registrar File Columns
                    </h3>

                    <p class="text-sm text-gray-600 leading-relaxed">
                        Code, Name, Subject Title, Units, Start, End, Days,
                        Classroom, Instructor, Enrolled
                    </p>

                    <p class="text-xs text-gray-500 mt-2">
                        Make sure the column headings match the Registrar's original file.
                    </p>
                </div>

                <!-- Buttons -->
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3">

                    <a
                        href="{{ route('admin.schedules') }}"
                        class="w-full sm:w-auto text-center px-6 py-3 rounded-xl bg-gray-200 hover:bg-gray-300 text-gray-700">
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="w-full sm:w-auto bg-[#0E4C6B] hover:bg-[#0B3D56]
                               text-white px-8 py-3 rounded-xl font-semibold">
                        Continue to Preview →
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>

@endsection
