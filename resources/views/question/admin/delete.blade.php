{{-- 
    Форма удаления вопроса
--}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li><a href="{{ route('questions.admin') }}">{{ trans('question.title') }}</a></li>
    <li><a href="{{ route('question.update.form.admin', ['id' => $question->id]) }}">{{ $question->title }}</a></li>
    <li class="active">{{ trans('question.form.action.delete') }}</li>
@stop
@section('content')
    @include('form.confirm.delete', ['route' => ['question.delete.admin', ['id' => $question->id]], 'name' => $question->title])
@stop
