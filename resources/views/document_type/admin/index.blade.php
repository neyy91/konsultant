{{-- 
    Список типов документов для админов.
 --}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li class="active">{{ trans('document_type.title') }}</li>
@stop
@section('content')
    <h1>{{ trans('document_type.title') }} <a href="{{ route('document_type.create.form.admin') }}" class="btn btn-primary btn-sm pull-right"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> @lang('document_type.add_document_type')</a></h1>

    @include('document_type.admin.filter')

    <table class="table table-striped table-bordered table-hover table-admin table-type-document-type">
        <thead>
            <tr>
                <th>@lang('app.id')</th>
                <th class="normal">{{ trans('document_type.field.name') }}</th>
                <th>{{ trans('document_type.field.status') }}</th>
                <th>{{ trans('document_type.field.parent_id') }}</th>
                <th class="action"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></th>
                <th class="action"><span class="glyphicon glyphicon-trash text-danger" aria-hidden="true"></span></th>
            </tr>
        </thead>
        <tbody>
        @foreach ($documentTypes as $documentType)
            <tr>
                <td>{{ $documentType->id }}</td>
                <td class="normal"><a href="{{ route('document_type.update.form.admin', ['id' => $documentType->id]) }}">{{ $documentType->name }}</a></td>
                <td>{{ $documentType->statusLabel }}</td>
                <td>@if ($documentType->parent)
                    <a href="{{ route('document_type.update.form.admin', ['id' => $documentType->parent->id]) }}">{{ $documentType->parent->name }}</a>
                @else
                    &mdash;
                @endif</td>
                <td class="action action-view">
                    <a href="{{ route('document_type.view', ['type' => $documentType]) }}" class="btn btn-default btn-xs" target="_blank"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
                </td>
                <td class="action action-delete">
                    <a href="{{ route('document_type.delete.form.admin', ['id' => $documentType->id]) }}" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $documentTypes->links() }}
@stop
