@extends('adminlte::page')
@section('title', $action . ' Admin')
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.AdminCustom', true)

@push('css')
<style>
    .iti {
        width: 100% !important;
    }
</style>
@endpush

@section('content_header')
<h1>{{ $action }} Admin</h1>
{{ Breadcrumbs::render('admins_alter', $action) }}
@stop

@section('content')
<div class="card">
    <form action="{{ $actionUrl }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if ($action != 'Add') @method('PUT') @endif
        <div class="card-header">
            <div class="float-right">
                <a href="{{ route('admin.admins.index') }}" class="btn btn-default"><i class="fa fa-arrow-alt-circle-left"></i> Back</a>
            </div>
            <p>Please add appropriate details to {{ $action }} Admin</p>
        </div>
        <div class="card-body">

            <div class="row">

                @if(config('constants.feature_permission'))
                <x-form-select size="12" name="role" label="Admin Role" data="{{ $roleData->pluck('name','name') }}" value="{{ $user?->getRoleNames()[0] }}" />
                @endif

                <x-form-input name="name" type="text" label="Name" value="{{ $user->name }}" />
                <x-form-input name="email" type="email" label="Email Address" value="{{ $user->email }}" />
                <x-form-input name="phone" id="phone" type="tel" label="Mobile Number (Optional)" placeholder="Enter Mobile Number here" value="{{ $user->phone }}" />

                @if ($action != 'Add')
                <div class="form-group col-md-6"></div>
                @else
                <x-form-input name="password" type="password" label="Password" />
                @endif
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-success">{{ $action }} Data</button>
            <a href="{{ route('admin.admins.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@stop
