@extends('admin.layouts.master')
@section('module', 'Admin')
@section('action', 'Dashboard')

@section('admin-content')
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="far fa-user"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Tổng số người dùng</h4>
                    </div>
                    <div class="card-body">
                        {{ $totalUsers }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                 <i class="fas fa-th"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Tổng số sản phẩm</h4>
                    </div>
                    <div class="card-body">
                        {{ $totalProducts }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                    <i class="far fa-user"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Tổng số khách hàng</h4>
                    </div>
                    <div class="card-body">
                        {{ $totalCustomers }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="far fa-user"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Người dùng gần đây</h4>
                    </div>
                    <div class="card-body">
                        {{ $newUsers }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
