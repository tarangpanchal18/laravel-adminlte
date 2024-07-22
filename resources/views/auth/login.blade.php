@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="container mt-5">
    <div class="row">
        <form action="{{ route('login') }}" method="POST">
            <div class="card bg-secondary mb-3">
                <div class="card-header">
                    <h4>Login</h4>
                </div>
                <div class="card-body">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="email" placeholder="Enter Email" class="form-control">
                        @error('email')<p class='text-danger'>{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Enter Password" class="form-control">
                        @error('password')<p class='text-danger'>{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form-group mt-3">
                        <input type="submit" value="Login" class="btn btn-success">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
