{{-- Создание страницы --}}
@extends('layouts.admin')

@section('breadcrumb')
    @parent
    <li><a href="{{ route('pages.admin') }}">{{ trans('page.title') }}</a></li>
    <li class="active">@lang('page.adding_page')</li>
@stop
@section('content')
    @include('page.admin.form', ['route' => ['create', null], 'page' => null])
@stop
