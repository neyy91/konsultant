{{-- 
Category confirm delete form
--}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li><a href="{{ route('categories.admin') }}">{{ trans('category.title') }}</a></li>
    <li><a href="{{ route('category.update.form.admin', $categoryLaw->id) }}">{{ $categoryLaw->name }}</a></li>
    <li class="active">{{ trans('category.form.legend.delete') }}</li>
@stop
@section('content')
    @include('form.confirm.delete', ['route' => ['category.delete.admin', ['id' => $categoryLaw->id]], 'name' => $categoryLaw->name, 'cancelUrl' => URL::previous() == route('category.delete.form.admin', ['id' => $categoryLaw->id]) ? route('category.update.form.admin', ['id' => $categoryLaw->id]) : null ])
@stop
