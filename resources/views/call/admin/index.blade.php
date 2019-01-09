{{-- 
    Список звонков для админов.
 --}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li class="active">{{ trans('call.consultations_by_phone') }}</li>
@stop
@section('content')
    <h1>{{ trans('call.consultations_by_phone') }}</h1>
    @include('call.admin.filter')
    <table class="table table-striped table-bordered table-hover table-admin table-type-call">
        <thead>
            <tr>
                <th>ID</th>
                <th class="normal">{{ trans('call.field.title') }}</th>
                 <th>{{ trans('call.field.status') }}</th>
                 <th>{{ trans('call.field.created_at') }}</th>
                 <th>{{ trans('call.field.city') }}</th>
                 <th class="action"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></th>
                 <th class="action"><span class="glyphicon glyphicon-trash text-danger" aria-hidden="true"></span></th>
            </tr>
        </thead>
        <tbody>
        @foreach ($calls as $call)
        <tr>
            <td>{{ $call->id }}</td>
            <td class="normal"><a href="{{ route('call.update.form', ['id' => $call->id]) }}">{{ $call->title }}</a></td>
            <td>{{ $call->statusLabel }}</td>
            <td>{{ $call->created_at->format(config('site.date.middle', 'd.m.Y H:i')) }}</td>
            <td>{{ $call->city->name }}</td>
            <td class="action action-view">
                <a href="{{ route('call.view', ['call' => $call]) }}" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
            </td>
            <td class="action action-delete">
                <a href="{{ route('call.delete.form', ['id' => $call->id]) }}" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    {{ $calls->links() }}
@stop
