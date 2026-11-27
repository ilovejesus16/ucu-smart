@extends('layouts.admin')

@section('title', 'Facility Management')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-6">

    @include('admin.facility-reservations.partials.facility-header')

    {{-- Success Message --}}
    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <p class="font-semibold mb-1">Please check the following:</p>
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @include('admin.facility-reservations.partials.facility-statistics')

    @include('admin.facility-reservations.partials.facility-table')

</div>

@if(auth()->user()->hasAdminPermission('facility_reservations', 'full'))
    @include('admin.facility-reservations.partials.add-facility-modal')
    @include('admin.facility-reservations.partials.edit-facility-modal')
@endif

@include('admin.facility-reservations.partials.facility-scripts')

@endsection
