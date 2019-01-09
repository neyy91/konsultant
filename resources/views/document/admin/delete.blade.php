{{-- 
    Форма удаления документа.
--}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li><a href="{{ route('documents.admin') }}">{{ trans('document.title') }}</a></li>
    <li><a href="{{ route('document.update.form', ['id' => $document->id]) }}">{{ $document->title }}</a></li>
    <li class="active">{{ trans('document.form.legend.delete') }}</li>
@stop
@section('content')
    @include('form.confirm.delete', ['route' => ['document.delete', ['id' => $document->id]], 'name' => $document->title])
@stop
