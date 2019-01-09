{{-- Страница редактирования компании --}}

@extends('layouts.app')
@extends('layouts.page.one')

@section('end')
    @parent
    <script src="{{ asset2('assets/scripts/view-others.js') }}"></script>
@stop

@section('breadcrumb')
    @parent
    <li><a href="{{ route('companies') }}">@lang('company.title')</a></li>
    <li><a href="{{ route('company', ['company' => $company]) }}">{{ $company->name }}</a></li>
    <li class="active">@lang('company.update_company')</li>
@endsection

@include('form.fields')
@section('content')
    <form action="{{ route('company.update', ['company' => $company]) }}" method="POST" role="form" class="form-company" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <legend class="form-title">@lang('form.editing')</legend>
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                @macros(input, 'name', $company, ['form' => 'company', 'required' => true])

                @macros(textarea, 'description', $company, ['form' => 'company', 'rows' => 3])
            </div>
            
            <div class="col-xs-12 col-sm-6">
                @macros(input, 'logo', $company, ['form' => 'company', 'type' => 'file' ])
                @if ($company->logo)
                    <img src="{{ $company->logo->url }}" alt="{{ $company->name }}" class="image" width="70">
                @endif
            </div>
        </div>

        @macros(textarea, 'text', $company, ['form' => 'company', 'rows' => 20])

        <div class="form-group">
            <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span> - {{ trans('app._required_fields') }}
        </div>

        <div class="form-group form-actions">
            <button type="submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> {{ trans("form.action.update") }}</button> 
        </div>

    </form>

@endsection