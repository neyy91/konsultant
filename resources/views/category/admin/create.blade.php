{{-- 
Category create form
--}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li><a href="{{ route('categories.admin') }}">{{ trans('category.title') }}</a></li>
    <li class="active">{{ trans('category.form.legend.create') }}</li>
@stop
@section('content')
    @include('category.admin.form', ['route' => ['create', null], 'categoryLaw' => null])
@stop
