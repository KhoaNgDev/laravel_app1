@extends('admin.layouts.master')
@section('module', 'Người dùng')
@section('action', 'Danh sách')
@section('modals')
    @include('admin.users.blocks.modal-ce')
@endsection
@section('admin-content')
    <link rel="stylesheet" href="{{ asset('css/admin/user.css') }}">
    @include('admin.users.blocks.header')
    <div class="card">
        @include('admin.users.blocks.filter')
        <div class="card-body">
            @include('admin.users.blocks.table')
        </div>
    </div>


@endsection

@push('scripts')
    @include('admin.users.blocks.script')
@endpush
