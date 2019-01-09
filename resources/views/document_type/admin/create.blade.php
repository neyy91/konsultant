{{-- 
Category create form
--}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li><a href="{{ route('document_types.admin') }}">{{ trans('document_type.title') }}</a></li>
    <li class="active">@lang('document_type.form.legend.create')</li>
@stop
@section('content')
    @include('document_type.admin.form', ['route' => ['create', null], 'documentType' => null])
@stop
