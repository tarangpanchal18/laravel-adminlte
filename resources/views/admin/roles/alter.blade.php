@extends('adminlte::page')
@section('title', $action . ' Users')
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.AdminCustom', true)

@section('content_header')
    <h1>{{ $action }} Role</h1>
    {{ Breadcrumbs::render('roles_alter', $action) }}
@stop

@section('content')
    <div class="card">
        <form action="{{ $actionUrl }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if ($action != 'Add') @method('PUT') @endif
            <div class="card-header">
                <div class="float-right">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-default"><i class="fa fa-arrow-alt-circle-left"></i> Back</a>
                </div>
                <p>Please add appropriate details to {{ $action }} Role</p>
            </div>
            <div class="card-body">

                <div class="row">

                    <x-form-input size="12" name="name" type="text" label="Role Name" value="{{ $data->name }}" />

                    <div class="form-group col-md-12 mt-4">
                        <label>Permissions</label>
                    </div>
                    @forelse($permissions as $permission)
                    <div class="form-group col-md-3">
                        <div class="custom-control custom-checkbox">
                            <input
                                class="custom-control-input"
                                name="permissions[]"
                                type="checkbox"
                                id="permission_{{ $permission->id }}"
                                value="{{ $permission->id }}"
                                {{ $rolePermissions && in_array($permission->id, $rolePermissions) ? 'checked' : '' }}
                            >
                            <label for="permission_{{ $permission->id }}" class="custom-control-label">{{ ucwords($permission->name) }}</label>
                        </div>
                    </div>
                    @empty
                    <i>No Permissions Available</i>
                    @endforelse
                </div>
                @error('permissions')<p class="text-danger">{{ $message }}</p>@enderror
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success">{{ $action }} Data</button>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-default">Cancel</a>
            </div>
        </form>
    </div>
@stop

@section('js')
<script>

</script>
@endsection
