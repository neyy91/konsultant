{{-- Обновление страницы --}}
@extends('layouts.admin')

@section('breadcrumb')
    @parent
    <li><a href="{{ route('pages.admin') }}">{{ trans('page.title') }}</a></li>
    <li class="active">{{ trans('page.updating_page') }}</li>
@stop

@section('content')
    @include('page.admin.form', ['route' => ['update', ['id' => $page->id]], 'page' => $page])
@stop
