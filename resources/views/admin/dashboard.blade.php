@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.AdminCustom', true)

@section('title', 'Dashboard')

@section('content_header')
<h1>Dashboard</h1>
{{ Breadcrumbs::render('dashboard') }}
@stop

@section('content')
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Active Users</span>
                <span class="info-box-number">{{ $totalActiveUsers }}</span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total InActive Users</span>
                <span class="info-box-number">{{ $totalInActiveUsers }}</span>
            </div>
        </div>
    </div>
</div>
@stop
