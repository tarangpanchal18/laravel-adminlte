@extends('adminlte::page')
@section('title', $action . ' Users')
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.AdminCustom', true)

@push('css')
<style>
.iti {
    width: 100%!important;
}
</style>
@endpush

@section('content_header')
    <h1>{{ $action }} User</h1>
    {{ Breadcrumbs::render('users_alter', $action) }}
@stop

@section('content')
    <div class="card">
        <form action="{{ $actionUrl }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if ($action != 'Add') @method('PUT') @endif
            <div class="card-header">
                <div class="float-right">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-default"><i class="fa fa-arrow-alt-circle-left"></i> Back</a>
                </div>
                <p>Please add appropriate details to {{ $action }} User</p>
            </div>
            <div class="card-body">

                <div class="row">

                    <x-form-input name="first_name" type="text" label="First Name" value="{{ $user->first_name }}" />
                    <x-form-input name="last_name" type="text" label="Last Name" value="{{ $user->last_name }}" />

                    <x-form-input name="email" type="email" label="Email Address" value="{{ $user->email }}" />

                    <x-form-input type="hidden" name="country" id="country" label="Country" value="{{ $user->country->iso2 }}" />
                    <x-form-input type="hidden" name="country_code" id="country_code" label="Country Code" value="{{ $user->country_code }}" />
                    <x-form-input
                        name="phone"
                        id="phone"
                        type="tel"
                        label="Mobile Number"
                        placeholder="Enter Mobile Number here"
                        value="{{ $user->phone }}"
                    />

                    @if ($action != 'Add')
                    <div class="form-group col-md-6"></div>
                    @else
                    <x-form-input name="password" type="password" label="Password" />
                    @endif
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success">{{ $action }} Data</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-default">Cancel</a>
            </div>
        </form>
    </div>
@stop

@section('js')
<script>
    const phoneInput = document.querySelector("#phone");
    const iti = window.intlTelInput(phoneInput, {
        initialCountry: "{{ $user->country->iso2 ? $user->country->iso2 : 'in' }}",
        separateDialCode: true,
        onlyCountries: ["in", "al", "ad", "ae", "at", "by", "be", "ba", "bg", "hr", "cz", "dk",
        "ee", "fo", "fi", "fr", "de", "gi", "gr", "va", "hu", "is", "ie", "it", "lv",
        "li", "lt", "lu", "mk", "mt", "md", "mc", "me", "nl", "no", "pl", "pt", "ro",
        "ru", "sm", "rs", "sk", "si", "es", "se", "ch", "ua", "gb", "us"],
        utilsScript: "/intl-tel-input/js/utils.js?1720774106479" // just for formatting/placeholders etc
    });
    phoneInput.addEventListener("countrychange", function() {
        var countrydata = iti.getSelectedCountryData();
        $("#country").val(countrydata.iso2);
        $("#country_code").val(countrydata.dialCode);
    });
</script>
@endsection
