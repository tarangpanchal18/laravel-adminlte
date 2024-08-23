@extends('adminlte::page')
@section('title', 'Page')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.AdminCustom', true)

@section('content_header')
<h1>Page</h1>

{{ Breadcrumbs::render('page_list') }}
@stop

@section('content')
<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-bordered" id="data-table">
            <thead>
                <tr>
                    <th>Page Name</th>
                    <th>SEO Title</th>
                    <th>Updated at</th>
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
        generateDataTable('{{ route("admin.pages.index") }}', [
            {
                data: 'page_name',
                name: 'page_name',
            },
            {
                data: 'page_seo_title',
                name: 'page_seo_title',
            },
            {
                data: 'updated_at',
                name: 'updated_at',
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ], {
            'status': $("#filter_status").val(),
        }, [0, 1, 2], false);

    });

    function removeData(id) {
        removeDataFromDatabase('{{route("admin.pages.index")}}', id);
    }
</script>
@stop
