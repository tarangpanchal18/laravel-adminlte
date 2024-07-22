@extends('layouts.app')
@section('title', 'Register')

@section('content')
<div class="container mt-5">
    <div class="row">
        <form method="POST" action="{{ route('register') }}">
            <div class="card bg-secondary mb-3">
                <div class="card-header">
                    <h4>Register</h4>
                </div>
                <div class="card-body">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="first_name" placeholder="Enter first name" class="form-control">
                        @error('first_name')<p class='text-danger'>{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <input type="text" name="last_name" placeholder="Enter last name" class="form-control">
                        @error('last_name')<p class='text-danger'>{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <input type="text" name="email" placeholder="Enter email" class="form-control">
                        @error('email')<p class='text-danger'>{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Enter password" class="form-control">
                        @error('password')<p class='text-danger'>{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <input type="password" name="password_confirmation" placeholder="Confirm password" class="form-control">
                        @error('password')<p class='text-danger'>{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form-group">
                        <input type="submit" value="Register" class="btn btn-success">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
