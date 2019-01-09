{{-- 
Category confirm delete form
--}}
@extends('layouts.admin')
@section('breadcrumb')
    @parent
    <li><a href="{{ route('answers.admin', ['type' => $answer->to_type]) }}">{{ trans('answer.title') }}</a></li>
    <li class="active">{{ trans('answer.form.legend.delete') }}</li>
@stop
@section('content')
    @include('form.confirm.delete', ['route' => ['answer.delete.admin', ['id' => $answer->id]], 'name' => trans('answer.answer_number', ['number' => $answer->id]), 'cancelUrl' => URL::previous() == route('answer.delete.form.admin', ['id' => $answer->id]) ? route('answers.admin', ['type' => $answer->to_type]) : null, 'text' => $answer->text])
@stop
