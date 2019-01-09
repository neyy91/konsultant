{{-- 
    Обновление региона.
--}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li><a href="{{ route('cities.admin') }}">@lang('city.cities_and_regions')</a></li>
    <li class="active">{{ trans('city.form.legend.update') }}</li>
@stop
@section('content')
    @include('city.admin.form', ['route' => ['update', ['id' => $city->id]], 'city' => $city])
@stop
