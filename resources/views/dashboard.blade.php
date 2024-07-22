@extends('layouts.app')
@section('title', 'Homepage')

@section('content')
<div class="container mt-5">
    <div class="row">
        <h3>Hello {{auth()->user()->name}},</h3>
        <p>This is your dashboard. you can do whatever you like.</p>
    </div>
</div>
@endsection
