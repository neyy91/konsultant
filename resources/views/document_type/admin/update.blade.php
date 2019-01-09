{{-- Обновление типа документа --}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li><a href="{{ route('document_types.admin') }}">{{ trans('document_type.title') }}</a></li>
    <li class="active">{{ trans('document_type.form.legend.update') }}</li>
@stop
@section('content')
    @include('document_type.admin.form', ['route' => ['update', ['id' => $documentType->id]], 'documentType' => $documentType])
@stop
