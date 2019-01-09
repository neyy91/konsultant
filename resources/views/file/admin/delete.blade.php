{{-- 
    Форма удаления файла.
--}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li><a href="{{ route('files') }}">{{ trans('file.title') }}</a></li>
    <li class="active">{{ trans('file.form.legend.delete') }}</li>
@stop
@section('content')
    @include('form.confirm.delete', ['route' => ['file.delete', ['id' => $file->id]], 'name' => $file->path])
@stop
