{{-- 
    Удаление города.
--}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li><a href="{{ route('cities.admin') }}">@lang('city.cities_and_regions')</a></li>
    <li class="active">{{ trans('city.form.legend.delete') }}</li>
@stop
@section('content')
    @include('form.confirm.delete', ['route' => ['city.delete.admin', ['id' => $city->id]], 'name' => $city->name])
@stop
