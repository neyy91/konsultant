{{-- 
    Удаление компании.
--}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li><a href="{{ route('companies.admin') }}">{{ trans('company.title') }}</a></li>
    <li class="active">{{ trans('company.action.delete') }}</li>
@stop
@section('content')
    @include('form.confirm.delete', ['route' => ['company.delete.admin', ['id' => $company->id]], 'name' => $company->name])
@stop
