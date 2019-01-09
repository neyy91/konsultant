{{-- 
Category create form
--}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li><a href="{{ route('themes.admin') }}">{{ trans('theme.title') }}</a></li>
    <li class="active">{{ trans('theme.form.legend.create') }}</li>
@stop
@section('content')
    @include('theme.admin.form', ['route' => ['create', null], 'theme' => null])
@stop
