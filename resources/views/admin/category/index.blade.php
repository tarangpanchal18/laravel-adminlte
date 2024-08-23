@extends('adminlte::page')
@section('title', 'Category')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.AdminCustom', true)

@section('content_header')
    <h1>Category Module</h1>
    {{ Breadcrumbs::render('category_list') }}
@stop

@section('content')
    <div class="card">
        <form action="{{route('admin.category.index')}}">
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
                    <label>Filter By Parent Category</label>
                    <select name="category" id="filter_category" class="form-control select2">
                        <option value="">Select Category</option>
                        @foreach ($categoryData as $cat)
                        <option {{ request()->query('category') == $cat->id ? 'selected' : '' }} value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <button class="btn btn-default filter-search"><i class="fa fa-search"></i> Search</button>
                    <a href="{{ route('admin.category.index') }}" class="btn btn-default filter-search"><i class="fas fa-undo"></i> Reset</a>
                </div>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="float-left">
                <select id="select-operation" class="form-control d-none" data-url="{{ route('admin.category.index') }}">
                    <option value="">Select Operation</option>
                    <option value="1">Mark as Active</option>
                    <option value="2">Mark as InActive</option>
                </select>
            </div>
            <div class="float-right">
                <a href="{{ route('admin.category.create') }}" class="btn btn-default"><i class="fa fa-plus"></i> Add Data</a>
            </div>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered" id="data-table">
                <thead>
                    <tr>
                        <th><input type="checkbox" class="multi-select-all"></th>
                        <th>Parent Category</th>
                        <th>Name</th>
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
    generateDataTable('{{ route("admin.category.index") }}', [
            {data: 'DT_RowIndex', name: 'id'},
            {data: 'parent', name: 'parent', orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
    ], {
        'status' : $("#filter_status").val(),
        'category' : $("#filter_category").val(),
    },
    [
        0, 1, 2
    ]);
});

function removeData(id) {
    removeDataFromDatabase(
        '{{route("admin.category.index")}}',
        id,
        'You won\'t be able to revert this!<br><span class="text-danger"><strong>CAUTION :</strong> All child category will also be deleted.</span>'
    );
}
</script>
@stop
