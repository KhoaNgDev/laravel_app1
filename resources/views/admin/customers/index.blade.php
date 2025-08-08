@extends('admin.layouts.master')
@section('module', 'Khách hàng')
@section('action', 'Danh sách')
@section('modals')
    @include('admin.customers.blocks.modal-create')
@endsection

@section('admin-content')
    <link rel="stylesheet" href="{{ asset('css/admin/customer.css') }}">
    @include('admin.customers.blocks.header')
    <div class="card">
        @include('admin.customers.blocks.filter')
        <div class="card-body">

            @include('admin.customers.blocks.table')
        </div>
    </div>
@endsection

@push('scripts')
    @include('admin.customers.blocks.script')
@endpush
