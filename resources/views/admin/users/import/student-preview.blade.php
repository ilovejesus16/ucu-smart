@extends('layouts.admin')

@section('title', 'Student Import Preview')

@section('content')

<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-slate-800">
            Student Import Preview
        </h1>

        <p class="text-slate-500 mt-1">
            Review the records before importing.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
            <p class="text-sm text-slate-500">Total Records</p>
            <h2 class="text-3xl font-bold mt-2">{{ $total }}</h2>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-green-200 p-5">
            <p class="text-sm text-green-600">Ready to Import</p>
            <h2 class="text-3xl font-bold text-green-600 mt-2">{{ $ready }}</h2>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-yellow-200 p-5">
            <p class="text-sm text-yellow-600">Duplicates</p>
            <h2 class="text-3xl font-bold text-yellow-600 mt-2">{{ $duplicates }}</h2>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-red-200 p-5">
            <p class="text-sm text-red-600">Invalid Rows</p>
            <h2 class="text-3xl font-bold text-red-600 mt-2">{{ $invalid }}</h2>
        </div>

    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">

                <tr>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Student ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">First Name</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Last Name</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Course</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Email</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold uppercase">Status</th>

                </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                @foreach($students as $student)

              <tr class="{{ $student['status'] == 'duplicate' ? 'bg-yellow-50' : ($student['status'] == 'invalid' ? 'bg-red-50' : '') }}">

    <td class="px-6 py-4">{{ $student['student_id'] }}</td>

    <td class="px-6 py-4">{{ $student['first_name'] }}</td>

    <td class="px-6 py-4">{{ $student['last_name'] }}</td>

    <td class="px-6 py-4">{{ $student['course'] }}</td>

    <td class="px-6 py-4">{{ $student['email'] }}</td>

    <td class="px-6 py-4 text-center">

        @switch($student['status'])

            @case('new')
                <span class="inline-flex items-center rounded-full bg-green-100 text-green-700 px-3 py-1 text-xs font-semibold">
                    ✓ New
                </span>
                @break

            @case('duplicate')
                <span class="inline-flex items-center rounded-full bg-yellow-100 text-yellow-700 px-3 py-1 text-xs font-semibold">
                    ⚠ Duplicate
                </span>
                @break

            @default
                <span class="inline-flex items-center rounded-full bg-red-100 text-red-700 px-3 py-1 text-xs font-semibold">
                    ✕ Invalid
                </span>

        @endswitch

    </td>

</tr>

                @endforeach

                </tbody>

            </table>

        </div>

    </div>

    <div class="flex justify-end gap-3">

        <a href="{{ route('admin.users') }}"
           class="px-5 py-2.5 rounded-xl border border-slate-300 hover:bg-slate-100">
            Cancel
        </a>

        <form action="{{ route('admin.users.student.store') }}" method="POST">
            @csrf

            <button
                class="px-5 py-2.5 rounded-xl bg-[#0E4C6B] text-white hover:bg-[#0b3d57]">

                Import {{ $ready }} Students

            </button>

        </form>

    </div>

</div>

@endsection