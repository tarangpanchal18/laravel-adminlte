@extends('adminlte::page')
@section('title', $action . ' Pages')
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.AdminCustom', true)

@section('content_header')
<h1>{{ $action }} Page</h1>
<script src="{{asset('vendor/ckeditor5/build/ckeditor.js')}}"></script>
{{ Breadcrumbs::render('page_alter', $action) }}
@stop

@section('content')
<div class="card">
    <form action="{{ $actionUrl }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if ($action != 'Add') @method('PUT') @endif
        <div class="card-header">
            <div class="float-right">
                <a href="{{ route('admin.pages.index') }}" class="btn btn-default"><i class="fa fa-arrow-alt-circle-left"></i> Back</a>
            </div>
            <p>Please add appropriate details to {{ $action }} Page</p>
        </div>
        <div class="card-body">
            <div class="row">
                <x-form-input size="4" name="page_name" type="text" label="Page Name" value="{!! $page->page_name !!}" placeholder="Enter Page name here" />

                <x-form-input size="4" name="page_seo_title" type="text" label="Page SEO Title" value="{!! $page->page_seo_title !!}" placeholder="Enter Page SEO Title here" />

                <x-form-input size="4" name="page_seo_description" type="text" label="Page SEO Description" value="{!! $page->page_seo_description !!}" placeholder="Enter Page SEO Description here" />

                <div class="form-group col-md-12">
                    <label>Description</label>
                    <textarea name="page_description" id="editor" rows="10">{{ old('page_description', @$page->page_description) }}</textarea>
                    @error('page_description')<span class="text-danger"> {{ $message }}</span>@enderror
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-success">{{ $action }} Data</button>
            <a href="{{ route('admin.pages.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@stop
@section('js')
<script>
    var myEditor;

    if (myEditor) {
        myEditor.destroy()
            .then(() => {
                console.log('Editor destroyed');
            })
            .catch(error => {
                console.error('Error destroying editor:', error);
            });
    }

    ClassicEditor
        .create(document.querySelector('#editor'))
        .then(editor => {
            myEditor = editor;
            myEditor.config.basicEntities = false,
                myEditor.config.entities_additional = '',
                myEditor.config.entities_greek = false,
                myEditor.config.entities_latin = false
        })
        .catch(error => {
            console.error(error);
        })

    ClassicEditor
        .create(document.querySelector('#ar_editor'))
        .then(editor => {
            myEditor = editor;
            myEditor.config.basicEntities = false,
                myEditor.config.entities_additional = '',
                myEditor.config.entities_greek = false,
                myEditor.config.entities_latin = false
        })
        .catch(error => {
            console.error(error);
        })

    ClassicEditor
        .create(document.querySelector('#es_editor'))
        .then(editor => {
            myEditor = editor;
            myEditor.config.basicEntities = false,
                myEditor.config.entities_additional = '',
                myEditor.config.entities_greek = false,
                myEditor.config.entities_latin = false
        })
        .catch(error => {
            console.error(error);
        })
</script>
@stop
