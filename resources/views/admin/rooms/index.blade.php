@extends('layouts.admin')

@section('title', 'Room Management')

@section('content')

<div class="max-w-7xl mx-auto p-6 space-y-6">

    {{-- Header --}}
    @include('admin.rooms.partials.header')

    {{-- Statistics --}}
    @include('admin.rooms.partials.statistics')

    {{-- Filters --}}
    @include('admin.rooms.partials.filters')

    {{-- Table --}}
    @include('admin.rooms.partials.table')

</div>

{{-- Modals --}}
@include('admin.rooms.partials.add-modal')

@include('admin.rooms.partials.edit-modal')

@include('admin.rooms.partials.view-modal')

@include('admin.rooms.partials.delete-modal')

{{-- Import Modal --}}
@includeWhen(
    view()->exists('admin.rooms.partials.import-modal'),
    'admin.rooms.partials.import-modal'
)

{{-- Scripts --}}
@include('admin.rooms.partials.scripts')

@endsection