{{-- 
    Создания города.
--}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li><a href="{{ route('cities.admin') }}">{{ trans('city.cities_and_regions') }}</a></li>
    <li class="active">{{ trans('city.form.legend.create') }}</li>
@stop
@section('content')
    @include('city.admin.form', ['route' => ['create', null], 'city' => null])
@stop
