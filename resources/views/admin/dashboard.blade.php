@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="space-y-7">

    {{-- Compact Welcome Header --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">

        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#0E4C6B] mb-2">
                UCU SMART+ 
            </p>

           <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-800">
    Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 18 ? 'Afternoon' : 'Evening') }},
    {{ auth()->user()->first_name
        ? auth()->user()->first_name . ' ' . auth()->user()->last_name
        : auth()->user()->name ?? 'User' }}!
</h1>

            <p class="text-sm text-slate-500 mt-2">
                Here's what's happening across your campus today.
            </p>
        </div>

        <div class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 shrink-0">
            <x-heroicon-o-calendar-days class="w-5 h-5 text-[#0E4C6B]"/>

            <span>{{ now()->format('l, F d, Y') }}</span>
        </div>

    </div>


    {{-- Campus Statistics --}}
    @include('admin.dashboard.stats')


    {{-- Main Dashboard Content --}}
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-x-8 gap-y-8">

        {{-- Quick Actions --}}
        <section class="xl:col-span-4 min-w-0">
            @include('admin.dashboard.quick-actions')
        </section>

        {{-- Pending Approvals --}}
        <section class="xl:col-span-4 min-w-0">
            @include('admin.dashboard.pending')
        </section>

        {{-- Recent Users --}}
        <section class="xl:col-span-4 min-w-0">
            @include('admin.dashboard.recent-users')
        </section>

    </div>


    {{-- Academic / Room Overview --}}
    <section class="pt-2">
        @include('admin.dashboard.academic')
    </section>

</div>

@endsection
