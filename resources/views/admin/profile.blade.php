@extends('adminlte::page')
@section('title', 'My Profile')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.AdminCustom', true)

@section('content_header')
    <h1>Manage Your Profile</h1>
    {{ Breadcrumbs::render('profile') }}
@stop

@section('content')
    <form action="{{ $actionUrl }}" method="POST" enctype="multipart/form-data">
        <div class="card">
            @csrf
            @method('PUT')
            <div class="card-header">
                <p>Manage Your Profile here</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <x-form-input name="name" type="text" label="Your Name" value="{{ $user->name }}" />
                    <x-form-input name="email" type="email" label="Your Email" value="{{ $user->email }}" />
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-sm btn-default">Update Profile</button>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <p>Change your password</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <x-form-input size="6" autocomplete="off" name="password" type="password" label="Create new Password" :helpText="config('constants.default_password_help')" />
                    <x-form-input size="6" autocomplete="off" name="password_confirmation" type="password" label="Confirm Password" />
                    <x-form-input size="6" autocomplete="off" name="current_password" type="password" label="Your Current Password" />
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-sm btn-default">Update Password</button>
            </div>
        </div>
    </form>
@stop

@section('js')
<x-alert-msg />
@stop
