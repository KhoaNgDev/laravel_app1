@extends('admin.layouts.master')
@section('module', 'Sản phẩm')
@section('action', 'Danh sách')
@section('modals')
    @include('admin.products.blocks.modal-ce')
@endsection
@section('admin-content')
    <link rel="stylesheet" href="{{ asset(path: 'css/admin/product.css') }}">
    @include('admin.products.blocks.header')
    <div class="card">
        @include('admin.products.blocks.filter')
        <div class="card-body">
            @include('admin.products.blocks.table')
        </div>
    </div>
@endsection
@push('scripts')
    @include('admin.products.blocks.script')
@endpush
