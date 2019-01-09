{{-- 
    Список документов для админов.
 --}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li class="active">{{ trans('document.title') }}</li>
@stop
@section('content')
    <h1>{{ trans('document.title') }}</h1>
    @include('document.admin.filter')
    <table class="table table-striped table-bordered table-hover table-admin table-type-document">
        <thead>
            <tr>
                <th>ID</th>
                <th class="normal">{{ trans('document.field.title') }}</th>
                 <th>{{ trans('document.field.status') }}</th>
                 <th>{{ trans('document.field.created_at') }}</th>
                 <th>{{ trans('document.field.document_type') }}</th>
                 <th>{{ trans('document.field.city') }}</th>
                 <th class="action"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></th>
                 <th class="action"><span class="glyphicon glyphicon-trash text-danger" aria-hidden="true"></span></th>
            </tr>
        </thead>
        <tbody>
        @foreach ($documents as $document)
        <tr>
            <td>{{ $document->id }}</td>
            <td class="normal"><a href="{{ route('document.update.form.admin', ['id' => $document->id]) }}">{{ $document->title }}</a></td>
            <td>{{ $document->statusLabel }}</td>
            <td>{{ $document->created_at->format(config('site.date.middle', 'd.m.Y H:i')) }}</td>
            <td><a href="{{ route('document_type.update.form.admin', ['id' => $document->documentType->id]) }}">{{ $document->documentType->name }}</a></td>
            <td><a href="{{ route('city.update.form.admin', ['id' => $document->city->id]) }}">{{ $document->city->name }}</a></td>
            <td class="action action-view">
                <a href="{{ route('document.view', ['document' => $document]) }}" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
            </td>
            <td class="action action-delete">
                <a href="{{ route('document.delete.form.admin', ['id' => $document->id]) }}" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    {{ $documents->links() }}
@stop
