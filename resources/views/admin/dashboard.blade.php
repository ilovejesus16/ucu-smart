@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="space-y-8">

    {{-- Welcome Banner --}}
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

        <div class="px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 break-words">
                Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 18 ? 'Afternoon' : 'Evening') }}, Administrator!
            </h1>

            <p class="text-gray-500 mt-2 text-sm sm:text-base">
                Welcome to the UCU Smart+ Registrar Control Center
            </p>

            <div class="flex flex-wrap items-center gap-2 mt-5 text-blue-700">

                <x-heroicon-o-calendar-days class="w-5 h-5"/>

                <span class="font-medium break-words">

                    {{ now()->format('l, F d, Y') }}

                </span>

            </div>

        </div>

    </div>

    @include('admin.dashboard.stats')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div>
            @include('admin.dashboard.quick-actions')
        </div>

        <div>
            @include('admin.dashboard.pending')
        </div>

        <div>
            @include('admin.dashboard.recent-users')
        </div>

    </div>

    @include('admin.dashboard.academic')

</div>

@endsection