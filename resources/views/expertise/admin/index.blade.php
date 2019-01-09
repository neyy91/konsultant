{{-- Список экспертиз. --}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li class="active">{{ trans('expertise.title') }}</li>
@stop
@section('content')
    <h1>{{ trans('expertise.title') }}</h1>

    @include('expertise.admin.filter')

    <table class="table table-striped table-bordered table-hover table-admin table-type-expertises">
        <thead>
            <tr>
                <th>@lang('app.id')</th>
                <th>@lang('expertise.field.type')</th>
                <th class="normal">@lang('expertise.field.question')</th>
                <th>@lang('expertise.field.lawyer')</th>
                <th class="action"><span class="glyphicon glyphicon-trash text-danger" aria-hidden="true"></span></th>
            </tr>
        </thead>
        <tbody>
        @foreach ($expertises as $expertise)
            <tr>
                <td>{{ $expertise->id }}</td>
                <td>{{ $expertise->typeLabel }}</td>
                <td class="normal"><a href="{{ route('question.view', ['question' => $expertise->question]) }}#expertises" target="__blank">{{ $expertise->question->title }}</a> (@lang('app.id') : {{ $expertise->question->id }})</td>
                <td><a href="{{ route('lawyer', ['lawyer' => $expertise->lawyer]) }}" target="__blank">{{ $expertise->lawyer->user->display_name }}</a> (@lang('app.id') : {{ $expertise->lawyer->id }})</td>
                <td class="action action-delete">
                    <a href="{{ route('expertise.delete.form.admin', ['id' => $expertise->id]) }}" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $expertises->links() }}
@stop
