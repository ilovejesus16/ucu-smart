@extends('layouts.admin')

@section('title', 'Import Rooms Preview')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Header -->

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">
                Import Rooms
            </h1>

            <p class="mt-1 text-slate-500">
                Review the rooms before adding them to the system.
            </p>

        </div>

        <a
            href="{{ route('rooms.index') }}"
            class="inline-flex items-center justify-center px-5 py-3 rounded-xl
                   border border-slate-300 bg-white text-slate-700
                   hover:bg-slate-100 transition">

            ← Cancel

        </a>

    </div>


    <!-- Statistics -->

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <!-- Total -->

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

            <p class="text-sm text-slate-500">
                Total Rows
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-800">
                {{ $total }}
            </p>

        </div>


        <!-- Ready -->

        <div class="bg-white rounded-2xl border border-green-200 shadow-sm p-6">

            <p class="text-sm text-slate-500">
                Ready to Import
            </p>

            <p class="mt-2 text-3xl font-bold text-green-600">
                {{ $ready }}
            </p>

        </div>


        <!-- Duplicates -->

        <div class="bg-white rounded-2xl border border-yellow-200 shadow-sm p-6">

            <p class="text-sm text-slate-500">
                Duplicates
            </p>

            <p class="mt-2 text-3xl font-bold text-yellow-600">
                {{ $duplicates }}
            </p>

        </div>


        <!-- Invalid -->

        <div class="bg-white rounded-2xl border border-red-200 shadow-sm p-6">

            <p class="text-sm text-slate-500">
                Invalid
            </p>

            <p class="mt-2 text-3xl font-bold text-red-600">
                {{ $invalid }}
            </p>

        </div>

    </div>


    <!-- Preview Table -->

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        <div class="px-6 py-5 border-b border-slate-200">

            <h2 class="text-xl font-bold text-slate-800">
                Import Preview
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Only rows marked as ready will be imported.
            </p>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full min-w-[1000px]">

                <thead class="bg-[#0E4C6B] text-white">

                    <tr>

                        <th class="px-6 py-4 text-left text-sm font-semibold">
                            #
                        </th>

                        <th class="px-6 py-4 text-left text-sm font-semibold">
                            Building
                        </th>

                        <th class="px-6 py-4 text-left text-sm font-semibold">
                            Room Number
                        </th>

                        <th class="px-6 py-4 text-left text-sm font-semibold">
                            Room Name
                        </th>

                        <th class="px-6 py-4 text-center text-sm font-semibold">
                            Capacity
                        </th>

                        <th class="px-6 py-4 text-center text-sm font-semibold">
                            Floor
                        </th>

                        <th class="px-6 py-4 text-center text-sm font-semibold">
                            Status
                        </th>

                        <th class="px-6 py-4 text-left text-sm font-semibold">
                            Remarks
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($rooms as $index => $room)

                        <tr class="hover:bg-slate-50 transition">

                            <td class="px-6 py-4 text-sm text-slate-500">
                                {{ $index + 1 }}
                            </td>


                            <td class="px-6 py-4">

                                <span class="font-medium text-slate-800">
                                    {{ $room['building_name'] ?? '—' }}
                                </span>

                            </td>


                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $room['room_number'] ?? '—' }}
                            </td>


                            <td class="px-6 py-4">

                                <span class="font-medium text-slate-800">
                                    {{ $room['room_name'] ?? '—' }}
                                </span>

                            </td>


                            <td class="px-6 py-4 text-center text-sm text-slate-700">
                                {{ $room['capacity'] ?? 0 }}
                            </td>


                            <td class="px-6 py-4 text-center text-sm text-slate-700">
                                {{ $room['floor'] ?? 1 }}
                            </td>


                            <td class="px-6 py-4 text-center">

                                @if(($room['status'] ?? '') === 'new')

                                    <span class="inline-flex items-center px-3 py-1 rounded-full
                                                 text-xs font-semibold
                                                 bg-green-100 text-green-700">

                                        Ready

                                    </span>

                                @elseif(($room['status'] ?? '') === 'duplicate')

                                    <span class="inline-flex items-center px-3 py-1 rounded-full
                                                 text-xs font-semibold
                                                 bg-yellow-100 text-yellow-700">

                                        Duplicate

                                    </span>

                                @else

                                    <span class="inline-flex items-center px-3 py-1 rounded-full
                                                 text-xs font-semibold
                                                 bg-red-100 text-red-700">

                                        Invalid

                                    </span>

                                @endif

                            </td>


                            <td class="px-6 py-4 text-sm text-slate-500">

                                {{ $room['remarks'] ?? '—' }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="px-6 py-12 text-center text-slate-500">

                                No rooms were found in the uploaded file.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        <!-- Actions -->

        <div class="border-t border-slate-200 p-6">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                <div>

                    @if($ready > 0)

                        <p class="text-sm text-slate-600">

                            <span class="font-semibold text-green-600">
                                {{ $ready }}
                            </span>

                            room(s) are ready to be imported.

                        </p>

                    @else

                        <p class="text-sm text-red-600 font-medium">
                            There are no valid rooms to import.
                        </p>

                    @endif

                </div>


                <div class="flex flex-col sm:flex-row gap-3">

                    <a
                        href="{{ route('rooms.index') }}"
                        class="inline-flex items-center justify-center px-6 py-3
                               rounded-xl border border-slate-300
                               bg-white text-slate-700
                               hover:bg-slate-100 transition">

                        Cancel

                    </a>


                    @if($ready > 0)

                        <form
                            action="{{ route('rooms.store.import') }}"
                            method="POST">

                            @csrf

                            <button
                                type="submit"
                                class="w-full sm:w-auto inline-flex items-center justify-center
                                       px-6 py-3 rounded-xl
                                       bg-[#0E4C6B] text-white
                                       font-semibold
                                       hover:bg-[#0B3D56]
                                       transition">

                                Confirm Import

                            </button>

                        </form>

                    @else

                        <button
                            type="button"
                            disabled
                            class="w-full sm:w-auto inline-flex items-center justify-center
                                   px-6 py-3 rounded-xl
                                   bg-slate-300 text-slate-500
                                   font-semibold cursor-not-allowed">

                            Confirm Import

                        </button>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection