{{-- 
    Контет личной страницы юриста.
 --}}
@extends('layouts.app')
@extends('layouts.page.lawyer')

@section('breadcrumb')
    @parent
    <li class="active">{{ trans('user.dashboard') }}</li>
@stop

@section('content')

<div class="row">
    <div class="col-xs-12 col-sm-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">@lang('question.title')</h3>
            </div>
            <div class="panel-body">
                @lang('question.description_dashboard')
            </div>
            <div class="panel-footer">
                <a href="{{ route('questions') }}" class="btn btn-block btn-primary">@lang('question.go_to_questions')</a>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">@lang('document.title')</h3>
            </div>
            <div class="panel-body">
                @lang('document.description_dashboard')
            </div>
            <div class="panel-footer">
                <a href="{{ route('documents') }}" class="btn btn-block btn-primary">@lang('document.go_to_documents')</a>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">@lang('call.title')</h3>
            </div>
            <div class="panel-body">
                @lang('call.description_dashboard')
            </div>
            <div class="panel-footer">
                <a href="{{ route('calls') }}" class="btn btn-block btn-primary">@lang('call.go_to_calls')</a>
            </div>
        </div>
    </div>
</div>

<div class="panel panel-default panel-type-user panel-type-user-orders">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('user.my_answers') }}</h3>
    </div>
    <div class="panel-body questions user-orders">
        <div class="user-order-questions">
            @foreach ($answers as $answer)
                <article class="order order-answer">
                    <h5>{{ trans('answer.answered_to.' . $answer->to_type) }} <a href="{{ route($answer->to_type . '.view', [$answer->to_type => $answer->to]) }}">{{ str_limit($answer->to->title, config('site.user.answer.limit')) }}</a></h5>
                </article>
            @endforeach
        </div>
    </div>
</div>


@stop
