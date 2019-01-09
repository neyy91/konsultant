{{-- Удаление экспертизы. --}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li><a href="{{ route('expertises.admin') }}">@lang('expertise.title')</a></li>
    <li class="active">{{ trans('expertise.delete_expertise') }}</li>
@stop
@section('content')
    @include('form.confirm.delete', [
        'route' => ['expertise.delete.admin', ['id' => $expertise->id]],
        'legend' => trans('expertise.delete_form_name.' . $expertise->type, ['name' => $expertise->lawyer->user->display_name]),
        'text' => $expertise->type == $expertise::TYPE_MESSAGE ? $expertise->message : trans('question.question') . ': ' . $expertise->question->title,
    ])
@stop
