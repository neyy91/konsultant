{{-- 
    Обновление категории права
--}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li><a href="{{ route('categories.admin') }}">{{ trans('category.title') }}</a></li>
    <li class="active">{{ trans('category.form.legend.update') }}</li>
@stop
@section('content')
    @include('category.admin.form', ['route' => ['update', ['id' => $categoryLaw->id]], 'categoryLaw' => $categoryLaw])
@stop
