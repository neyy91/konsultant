{{-- 
    Список компании для админов.
 --}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li class="active">@lang('company.title')</li>
@stop
@section('content')
    <div class="companies-admin">
        <h1>@lang('company.title')</h1>

        @include('company.admin.filter')

        <table class="table table-striped table-bordered table-hover table-admin table-type-document-type">
            <thead>
                <tr>
                    <th>@lang('app.ID')</th>
                    <th class="normal">@lang('company.field.name')</th>
                    <th>@lang('company.field.status')</th>
                    <th class="action action-view"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></th>
                    <th class="action action-edit"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></th>
                    <th class="action action-danger"><span class="glyphicon glyphicon-trash text-danger" aria-hidden="true"></span></th>
                </tr>
            </thead>
            <tbody>
            @foreach ($companies as $company)
                <tr>
                    <td>{{ $company->id }}</td>
                    <td class="normal"><a href="{{ route('company.update.form.admin', ['id' => $company->id]) }}">{{ $company->name }}</a></td>
                    <td>{{ $company->statusLabel }}</td>
                    <td class="action action-view">
                        <a href="{{ route('company', ['company' => $company]) }}" class="btn btn-default btn-xs" target="_block"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
                    </td>
                    <td class="action action-update">
                        <a href="{{ route('company.update.form.admin', ['id' => $company->id]) }}" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                    </td>
                    <td class="action action-delete">
                        <a href="{{ route('company.delete.form.admin', ['id' => $company->id]) }}" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $companies->links() }}
    </div>
@stop
