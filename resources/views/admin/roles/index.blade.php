@extends('adminlte::page')
@section('title', 'Roles')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.AdminCustom', true)

@section('content_header')
    <h1>Role Module</h1>
    {{ Breadcrumbs::render('roles_list') }}
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="float-right">
                <a href="{{ route('admin.roles.create') }}" class="btn btn-default"><i class="fa fa-plus"></i> Add</a>
            </div>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered" id="data-table">
                <thead>
                    <tr>
                        <th style="width: 10%;">Name</th>
                        <th style="width: 75%;">Permissions</th>
                        <th style="width: 15%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pageData as $data)
                    <tr>
                        <td><a href="{{ route('admin.roles.edit', $data->id) }}">{{ ucwords($data->name) }}</a></td>
                        <td>
                            <p><b>{{ $data->permissions->count() }} Permissions</b></p>
                            {{ implode(',', $data->permissions->pluck('name')->toArray()) }}
                        </td>
                        <td>
                            <form action="{{ route('admin.roles.destroy', $data->id) }}" method="POST" onSubmit="if(!confirm('Are you sure ?')){return false;}">
                                @method('DELETE')
                                @csrf
                                <button class="btn btn-sm btn-default"><i class="fa fa-trash"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center"> No Data found !</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('js')
<x-alert-msg />
@stop
