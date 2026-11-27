@extends('layouts.admin')

@section('title', 'Facility Reservations')

@section('content')

<div class="max-w-7xl mx-auto p-6 space-y-6">

    {{-- Page Header --}}
    @include('admin.facility-reservations.partials.header')

    {{-- Success Message --}}
    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <p class="font-semibold mb-2">
                Please check the following:
            </p>

            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Statistics --}}
    @include('admin.facility-reservations.partials.statistics')

    {{-- Filters --}}
    @include('admin.facility-reservations.partials.filters')

    {{-- Reservations Table --}}
    @include('admin.facility-reservations.partials.table')

</div>

{{-- Reservation Modals --}}
@if(auth()->user()->hasAdminPermission('facility_reservations', 'full'))

    @include('admin.facility-reservations.partials.add-modal')

    @include('admin.facility-reservations.partials.edit-modal')

@endif

{{-- View Modal: Available to View and Full Access --}}
@include('admin.facility-reservations.partials.view-modal')

{{-- Scripts --}}
@include('admin.facility-reservations.partials.scripts')

@endsection
