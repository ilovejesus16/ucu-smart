@extends('layouts.instructor')

@section('title','Room Availability')

@section('content')

<div class="max-w-7xl mx-auto">

    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">

        <div>

            <h1 class="text-2xl sm:text-3xl font-bold">

                Room Availability

            </h1>

            <p class="text-gray-500 mt-1">

                Browse available classrooms across the campus.

            </p>

        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

        @foreach($rooms as $room)

        <div class="bg-white rounded-2xl shadow hover:shadow-xl transition">

            <div class="p-5 sm:p-6">

                <div class="flex justify-between items-start gap-3">

                    <h2 class="text-lg sm:text-xl font-bold break-words">

                        {{ $room->room_name }}

                    </h2>

                    @if($room->occupiedSchedule)

                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap">

                        Occupied

                    </span>

                    @else

                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap">

                        Available

                    </span>

                    @endif

                </div>

                <p class="text-gray-500 mt-2 break-words">

                    {{ $room->building->building_name }}

                </p>

                @if($room->occupiedSchedule)

                <p class="text-red-600 mt-3 font-semibold break-words">

                    {{ $room->occupiedSchedule->subject_code }}

                </p>

                <p class="text-gray-500 text-sm">

                    Until

                    {{ date('g:i A', strtotime($room->occupiedSchedule->end_time)) }}

                </p>

                @else

                <p class="text-green-600 mt-3 font-semibold">

                    No Current Class

                </p>

                @endif

                <div class="mt-6 space-y-2 text-gray-700">

                    <p>
                        <strong>Room No:</strong>
                        {{ $room->room_number }}
                    </p>

                    <p>
                        <strong>Floor:</strong>
                        {{ $room->floor }}
                    </p>

                    <p>
                        <strong>Capacity:</strong>
                        {{ $room->capacity }}
                    </p>

                </div>

                <button
                    class="w-full mt-6 bg-[#1E3A8A] text-white py-3 rounded-xl hover:bg-blue-800 transition">

                    View Today's Schedule

                </button>

            </div>

        </div>

        @endforeach

    </div>

</div>

@endsection