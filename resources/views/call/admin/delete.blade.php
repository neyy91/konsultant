{{-- 
    Форма удаления звонков.
--}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li><a href="{{ route('calls.admin') }}">{{ trans('call.title') }}</a></li>
    <li><a href="{{ route('call.update.form', ['id' => $call->id]) }}">{{ $call->title }}</a></li>
    <li class="active">{{ trans('call.form.legend.delete') }}</li>
@stop
@section('content')
    @include('form.confirm.delete', ['route' => ['call.delete', ['id' => $call->id]], 'name' => $call->title])
@stop
