{{-- 
    Добавление вопроса
--}}
@extends('layouts.app')
@extends('layouts.page.one')
@section('breadcrumb')
    @parent
    <li><a href="{{ route('questions') }}">{{ trans('question.all_questions') }}</a></li>
    <li class="active">{{ trans('question.form.legend.create') }}</li>
@stop
@section('content')
    @include('question.form', ['question' => null])
@stop
