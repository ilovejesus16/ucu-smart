@extends('layouts.admin')

@section('title', 'Building Management')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    @include('admin.buildings.partials.header')

    {{-- Statistics --}}
    @include('admin.buildings.partials.statistics')

    {{-- Search --}}
    @include('admin.buildings.partials.search')

    {{-- Table --}}
    @include('admin.buildings.partials.table')

</div>

{{-- Add Building --}}
@include('admin.buildings.partials.add-modal')

{{-- View Building --}}
@include('admin.buildings.partials.view-modal')

{{-- Edit Building --}}
@include('admin.buildings.partials.edit-modal')

{{-- Delete Building --}}
@include('admin.buildings.partials.delete-modal')

{{-- Import --}}
@include('admin.buildings.partials.import-modal')

{{-- Scripts --}}
@include('admin.buildings.partials.scripts')

@endsection