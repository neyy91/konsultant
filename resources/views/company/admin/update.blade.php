{{-- 
    Обновление компании.
--}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li><a href="{{ route('companies.admin') }}">@lang('company.title')</a></li>
    <li class="active">{{ trans('company.action.edit_company', ['company' => $company->name]) }}</li>
@stop
@section('content')
    @include('company.admin.form', ['route' => ['update', ['id' => $company->id]], 'company' => $company])
@stop
