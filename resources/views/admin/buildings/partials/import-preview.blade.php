@extends('layouts.admin')

@section('title', 'Building Import Preview')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header -->

    <div>

        <h1 class="text-3xl font-bold text-slate-800">
            Building Import Preview
        </h1>

        <p class="text-slate-500 mt-1">
            Review the uploaded buildings before importing.
        </p>

    </div>

    <!-- Statistics -->

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

            <p class="text-sm text-slate-500">Total Rows</p>

            <h2 class="text-3xl font-bold mt-2">

                {{ $total }}

            </h2>

        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-green-200 p-6">

            <p class="text-sm text-green-600">

                Ready to Import

            </p>

            <h2 class="text-3xl font-bold text-green-600 mt-2">

                {{ $ready }}

            </h2>

        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-yellow-200 p-6">

            <p class="text-sm text-yellow-600">

                Duplicates

            </p>

            <h2 class="text-3xl font-bold text-yellow-600 mt-2">

                {{ $duplicates }}

            </h2>

        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-red-200 p-6">

            <p class="text-sm text-red-600">

                Invalid

            </p>

            <h2 class="text-3xl font-bold text-red-600 mt-2">

                {{ $invalid }}

            </h2>

        </div>

    </div>

    <!-- Preview Table -->

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

        <table class="min-w-full">

            <thead class="bg-slate-50">

                <tr>

                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">

                        Building Name

                    </th>

                    <th class="px-6 py-4 text-center text-sm font-semibold text-slate-600">

                        Status

                    </th>

                </tr>

            </thead>

            <tbody>

                @foreach($buildings as $building)

                <tr class="border-t">

                    <td class="px-6 py-4">

                        {{ $building['building_name'] ?: '-' }}

                    </td>

                    <td class="px-6 py-4 text-center">

                        @if($building['status']=='new')

                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">

                                ✓ New

                            </span>

                        @elseif($building['status']=='duplicate')

                            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">

                                ⚠ Duplicate

                            </span>

                        @else

                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">

                                ✕ Invalid

                            </span>

                        @endif

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

    <!-- Buttons -->

    <div class="flex justify-end gap-3">

        <a
            href="{{ route('buildings.index') }}"
            class="px-5 py-3 rounded-xl border border-slate-300 hover:bg-slate-100">

            Cancel

        </a>

        <form
            action="{{ route('buildings.store.import') }}"
            method="POST">

            @csrf

            <button
                class="px-5 py-3 rounded-xl bg-[#0E4C6B] hover:bg-[#0B3D56] text-white">

                Import Buildings

            </button>

        </form>

    </div>

</div>

@endsection