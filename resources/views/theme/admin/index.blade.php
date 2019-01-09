{{-- 
    Список тем для админов.
 --}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li class="active">{{ trans('theme.title') }}</li>
@stop
@section('content')
    <h1>{{ trans('theme.title') }} <a href="{{ route('theme.create.form.admin') }}" class="btn btn-primary btn-sm pull-right"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> {{ trans('theme.add_theme') }}</a></h1>
    @include('theme.admin.filter')
    <table class="table table-striped table-bordered table-hover table-admin table-type-themes">
        <thead>
            <tr>
                <th>ID</th>
                <th>{{ trans('theme.field.name') }}</th>
                <th>{{ trans('theme.field.status') }}</th>
                <th>{{ trans('theme.field.sort') }}</th>
                <th>{{ trans('theme.field.category_law') }}</th>
                <th class="action action-view"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></th>
                <th class="action action-delete"><span class="glyphicon glyphicon-trash text-danger" aria-hidden="true"></span></th>
            </tr>
        </thead>
        <tbody>
        @foreach ($themes as $theme)
        <tr>
            <td>{{ $theme->id }}</td>
            <td class="normal"><a href="{{ route('theme.update.form.admin', ['id' => $theme->id]) }}">{{ $theme->name }}</a></td>
            <td>{{ $theme->statusLabel }}</td>
            <td>{{ $theme->sort }}</td>
            <td>{{ $theme->categoryLaw->name }}</td>
            <td class="action action-view">
                <a href="{{ route('theme.view', ['theme' => $theme]) }}" class="btn btn-default btn-xs toggle-tooltip" data-container="body" title="{{ trans('form.action.view') }}" target="_blank"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
            </td>
            <td class="action action-delete">
                <a href="{{ route('theme.delete.form.admin', ['id' => $theme->id]) }}" class="btn btn-danger btn-xs toggle-tooltip" data-container="body" title="{{ trans('form.action.delete') }}"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    {{ $themes->links() }}
@stop
