{{--
    Список файлов
--}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li class="active">{{ trans('file.title') }}</li>
@stop
@section('content')
    <h1>{{ trans('file.title') }}</h1>
    @include('file.admin.filter')
    <table class="table table-striped table-bordered table-hover table-admin table-type-files">
        <thead>
            <tr>
                <th>ID</th>
                <th>{{ trans('file.field.basename') }}</th>
                <th>{{ trans('file.field.mime_type') }}</th>
                <th>{{ trans('file.field.size') }}</th>
                <th>{{ trans('file.field.owner') }}</th>
                <th class="action"><span class="glyphicon glyphicon-eye-open text-primary" aria-hidden="true"></span></th>
                <th class="action"><span class="glyphicon glyphicon-trash text-danger" aria-hidden="true"></span></th>
            </tr>
        </thead>
        <tbody>
        @foreach ($files as $file)
        @php
            $mime_trans = 'file.mime_type.' . str_replace('/','_', $file->mime_type);
            $mime_type = trans($mime_trans);
            $mime_type = $mime_type == $mime_trans ? $file->mime_type : $mime_type;
        @endphp
        <tr>
            <td>{{ $file->id }}</td>
            <td><a href="{{ route('file', ['file' => $file]) }}" title="{{ $file->path }}">{{ $file->basename }}</a></td>
            <td>{{ $mime_type }}</td>
            <td>{{ round($file->size/1024) }} @lang('app.kb')</td>
            <td class="normal">{{ trans($file->owner_type . '.about') }}: 
                @php
                    $route = $file->owner_type . '.update.form';
                @endphp
                @if (Route::has($route))
                    <a href="{{ route($route, ['id' => $file->owner->id]) }}" target="_blank">{{ $file->owner->about }}</a>
                @else
                    <span>{{ $file->owner->about }}</span>
                @endif
            </td>
            <td class="action action-view"><a href="{{ route($file->owner_type, [$file->owner_type => $file->owner]) }}" target="_blank"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a></td>
            <td class="action action-delete">
                <a href="{{ route('file.delete.form', ['id' => $file->id]) }}" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    {{ $files->links() }}
@stop
