{{-- 
    Обновление вопроса.
--}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li><a href="{{ route('questions.admin') }}">{{ trans('question.title') }}</a></li>
    <li class="active">{{ trans('question.form.legend.update') }}</li>
@stop
@section('content')
    <h1>{{ $question->title }}</h1>
    @include('question.admin.form', ['question' => $question])
@stop
