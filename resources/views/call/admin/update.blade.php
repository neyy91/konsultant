{{-- 
    Обновление звонков админом.
--}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li><a href="{{ route('calls.admin') }}">{{ trans('call.title') }}</a></li>
    <li class="active">{{ trans('call.form.legend.update') }}</li>
@stop
@section('content')
    @include('call.admin.form', ['call' => $call])
@stop
