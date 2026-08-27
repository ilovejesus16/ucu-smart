@extends('layouts.admin')

@section('title','Import Schedule')

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

        <a
            href="{{ route('admin.schedules') }}"
            class="w-full sm:w-auto text-center bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-3 rounded-xl">

            ← Back

        </a>

    </div>

    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <!-- Header -->

        <div class="bg-[#0E4C6B] px-4 sm:px-6 lg:px-8 py-6">

            <h2 class="text-xl sm:text-2xl font-bold text-white">

                Excel Schedule Import

            </h2>

            <p class="text-blue-100 mt-1 text-sm sm:text-base">

                Supported formats: XLSX, XLS and CSV.

            </p>

        </div>

        <div class="p-4 sm:p-6 lg:p-8">

            @if(session('success'))

                <div class="mb-6 rounded-xl bg-green-100 border border-green-200 text-green-700 p-4 break-words">

                    {{ session('success') }}

                </div>

            @endif

            @if($errors->any())

                <div class="mb-6 rounded-xl bg-red-100 border border-red-200 text-red-700 p-4">

                    <ul class="list-disc ml-5 break-words">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <form
                action="{{ route('schedule.import.store') }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf

                <label class="block text-sm font-semibold text-gray-700 mb-3">

                    Schedule File

                </label>

                <input
                    type="file"
                    name="file"
                    required
                    class="block w-full rounded-xl border border-gray-300
                           file:mr-4
                           file:rounded-lg
                           file:border-0
                           file:bg-[#0E4C6B]
                           file:px-4
                           file:py-2
                           file:text-white
                           hover:file:bg-[#0B3D56]">

                <div class="mt-10 flex flex-col-reverse sm:flex-row justify-end gap-3">

                    <a
                        href="{{ route('admin.schedules') }}"
                        class="w-full sm:w-auto text-center px-6 py-3 rounded-xl bg-gray-200 hover:bg-gray-300">

                        Cancel

                    </a>

                    <button
                        class="w-full sm:w-auto bg-[#0E4C6B] hover:bg-[#0B3D56]
                               text-white
                               px-8
                               py-3
                               rounded-xl
                               font-semibold">

                        Import Schedule

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection