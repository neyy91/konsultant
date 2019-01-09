{{-- 
    Форма удаления типа документа.
--}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li><a href="{{ route('document_types.admin') }}">{{ trans('document_type.title') }}</a></li>
    <li><a href="{{ route('document_type.update.form.admin', ['id' => $documentType->id]) }}">{{ $documentType->name }}</a></li>
    <li class="active">{{ trans('document_type.form.legend.delete') }}</li>
@stop
@section('content')
    @include('form.confirm.delete', ['route' => ['document_type.delete.admin', ['id' => $documentType->id]], 'name' => $documentType->name])
@stop
