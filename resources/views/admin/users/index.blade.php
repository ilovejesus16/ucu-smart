
@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    @include('admin.users.partials.header')

    {{-- Statistics --}}
    @include('admin.users.partials.statistics')

    {{-- Filters --}}
    @include('admin.users.partials.filters')

    {{-- User Table --}}
    @include('admin.users.partials.table')

</div>

{{-- Modals --}}
@include('admin.users.partials.delete-modal')
@include('admin.users.partials.import-student-modal')
@include('admin.users.partials.import-instructor-modal')
@include('admin.users.partials.view-modal')
@include('admin.users.partials.edit-modal')
@include('admin.users.partials.add-modal')

{{-- Scripts --}}
@include('admin.users.partials.scripts')
@endsection
