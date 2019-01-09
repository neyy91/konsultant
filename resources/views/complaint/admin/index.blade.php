{{-- 
    Список жалоб.
 --}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li class="active">{{ trans('complaint.title') }}</li>
@stop
@section('content')
    <h1>{{ trans('complaint.title') }}</h1>
    @include('complaint.admin.filter')
    <table class="table table-striped table-bordered table-hover table-admin table-type-complaints">
        <thead>
            <tr>
                <th>ID</th>
                <th>{{ trans('complaint.field.created') }}</th>
                <th>{{ trans('complaint.field.against') }}</th>
                <th>{{ trans('complaint.field.comment') }}</th>
                <th class="action action-delete"><span class="glyphicon glyphicon-trash text-danger" aria-hidden="true"></span></th>
            </tr>
        </thead>
        <tbody>
        @foreach ($complaints as $complaint)
        <tr>
            <td>{{ $complaint->id }}</td>
            <td class="date">{{ $complaint->dateCreateShort }}</td>
            <td class="normal">@if ($complaint->against)
                {{ trans($complaint->against_type . '.about') }}: <a href="{{ route($complaint->against_type, ['id' => $complaint->against->id]) }}">{{ $complaint->against->about }}</a></td>
            @else
                {{ trans('app.may_be_deleted') }}
            @endif</td>
            <td class="normal">{{ str_limit($complaint->comment, 100) }}
            <td class="action action-delete">
                <a href="{{ route('complaint.delete.form.admin', ['id' => $complaint->id]) }}" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    {{ $complaints->links() }}
@stop
