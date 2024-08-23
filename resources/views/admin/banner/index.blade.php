@extends('adminlte::page')
@section('title', 'Banner')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.AdminCustom', true)

@section('content_header')
    <h1>Banner Module</h1>
    {{ Breadcrumbs::render('banner_list') }}
@stop

@section('content')
    <div class="card">
        <form action="{{route('admin.banner.index')}}">
            <div class="card-body row">
                <div class="form-group col-md-3">
                    <label>Filter By Status</label>
                    <select name="status" id="filter_status" class="form-control select2">
                        <option value="">Select Status</option>
                        <option {{ request()->query('status') == "Active" ? 'selected' : '' }} value="Active">Filter By Active</option>
                        <option {{ request()->query('status') == "InActive" ? 'selected' : '' }} value="InActive">Filter By InActive</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <button class="btn btn-default filter-search"><i class="fa fa-search"></i> Search</button>
                    <a href="{{ route('admin.banner.index') }}" class="btn btn-default filter-search"><i class="fas fa-undo"></i> Reset</a>
                </div>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="float-right">
                <a href="{{ route('admin.banner.create') }}" class="btn btn-default"><i class="fa fa-plus"></i> Add Data</a>
            </div>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered" id="data-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
        </table>
        </div>
    </div>
@stop

@section('js')
<x-alert-msg />

<script>
$(document).ready(function() {
    generateDataTable('{{ route("admin.banner.index") }}', [
            {
                data: 'image',
                name: 'image',
                render: function (data) {
                    return "<img class='admin-preview-banner-table' src='/storage/{{ $upload_path }}/"+ data +"' alt='No Image Avialable'/>"
                },
            },
            {data: 'name', name: 'name'},
            {data: 'order', name: 'order'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
    ], {'status' : $("#filter_status").val()});

});

function removeData(id) {
    removeDataFromDatabase('{{route("admin.banner.index")}}', id);
}
</script>
@stop
