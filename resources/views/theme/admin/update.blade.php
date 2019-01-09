{{-- 
    Обновление категории права
--}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li><a href="{{ route('themes.admin') }}">{{ trans('theme.title') }}</a></li>
    <li class="active">{{ trans('theme.form.legend.update') }}</li>
@stop
@section('content')
    @include('theme.admin.form', ['route' => ['update', ['id' => $theme->id]], 'theme' => $theme])
@stop
