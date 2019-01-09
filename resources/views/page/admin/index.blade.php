{{-- Список страниц для админа. --}}

@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li class="active">{{ trans('page.title') }}</li>
@stop
@section('content')
    <h1>{{ trans('page.title') }} <a href="{{ route('page.create.form.admin') }}" class="btn btn-primary btn-sm pull-right"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> @lang('page.add_page')</a></h1>

    @include('page.admin.filter')

    <table class="table table-striped table-bordered table-hover table-admin table-type-document-type">
        <thead>
            <tr>
                <th>@lang('app.id')</th>
                <th class="normal">{{ trans('page.form.title') }}</th>
                <th>{{ trans('page.form.status') }}</th>
                <th>{{ trans('page.form.layout') }}</th>
                <th>{{ trans('page.form.page_layout') }}</th>
                <th class="action"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></th>
                <th class="action"><span class="glyphicon glyphicon-trash text-danger" aria-hidden="true"></span></th>
            </tr>
        </thead>
        <tbody>
        @foreach ($pages as $page)
            <tr>
                <td>{{ $page->id }}</td>
                <td class="normal"><a href="{{ route('page.update.form.admin', ['id' => $page->id]) }}">{{ $page->title }}</a></td>
                <td>{{ $page->statusLabel }}</td>
                <td>{{ $page->layoutLabel }}</td>
                <td>{{ $page->layoutPageLabel }}</td>
                <td class="action action-view">
                    <a href="{{ route('page', ['page' => $page]) }}" class="btn btn-default btn-xs" target="_blank"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
                </td>
                <td class="action action-delete">
                    <a href="{{ route('page.delete.form.admin', ['id' => $page->id]) }}" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $pages->links() }}
@stop
