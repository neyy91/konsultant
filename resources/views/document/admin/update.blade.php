{{-- 
    Обновление вопроса админом.
--}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li><a href="{{ route('documents.admin') }}">{{ trans('document.title') }}</a></li>
    <li class="active">{{ trans('document.form.legend.update') }}</li>
@stop
@section('content')
    @include('document.admin.form', ['document' => $document])
@stop
