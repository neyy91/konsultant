{{-- 
    Контет личной страницы пользователя.
 --}}
@extends('layouts.app')
@extends('layouts.page.user')

@section('breadcrumb')
    @parent
    <li class="active">{{ trans('user.dashboard') }}</li>
@stop

@section('content')

    <div class="row">
        <div class="col-xs-12 col-sm-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"><b>{{ trans('question.title') }}</b></h3>
                </div>
                <div class="panel-body">
                    {{ trans('question.description') }} <a href="{{ route('questions') }}" class="more">{{ trans('app.learn_more') }}</a>
                </div>
                <div class="panel-footer">
                    <a href="{{ route('question.create.form') }}" class="btn btn-primary btn-lg btn-block">{{ trans('question.ask') }}</a>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"><b>{{ trans('call.title') }}</b></h3>
                </div>
                <div class="panel-body">
                    {{ trans('call.description') }}
                </div>
                <div class="panel-footer">
                    <a href="{{ route('call.create.form') }}" class="btn btn-primary btn-lg btn-block">{{ trans('call.request_call') }}</a>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"><b>{{ trans('document.title') }}</b></h3>
                </div>
                <div class="panel-body">
                    {{ trans('document.description') }}
                </div>
                <div class="panel-footer">
                    <a href="{{ route('document.create.form') }}" class="btn btn-primary btn-lg btn-block">{{ trans('document.orders_documents') }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-8">
            <div class="panel panel-default panel-type-user panel-type-user-orders">
                <div class="panel-heading">
                    <h2 class="panel-title">{{ trans('user.my_orders') }}</h2>
                </div>
                <div class="panel-body user-orders">
                    @if ($questions->count() > 0)
                        <section class="user-orders-questions">
                            <h3 class="title title-user-order-questions">{{ trans('question.title') }} <a href="{{ route('user.questions') }}" class="pull-right small title-more">{{ trans('question._all_questions') }}</a></h3>
                            @foreach ($questions as $question)
                                <article class="order order-question">
                                    <h5 class="article-title"><a href="{{ route('question.view', ['question' => $question]) }}">{{ str_limit($question->title, config('site.user.order.limit')) }}</a></h5>
                                    @php
                                        $count = $question->answers->count();
                                    @endphp
                                    <span class="text-muted comment"><span class="glyphicon glyphicon-comment icon icon-comment" aria-hidden="true"></span> <span class="count comment-count">{{ trans_choice('question.count_answers', $count, ['count' => $count]) }}</span></span>
                                </article>
                            @endforeach

                        </section>
                        <hr>
                    @endif
                    @if ($documents->count() > 0)
                        <section class="user-orders-documents">
                            <h3 class="title title-user-order-documents">{{ trans('document.title') }} <a href="{{ route('user.documents') }}" class="pull-right small title-more">{{ trans('document._all_documents') }}</a></h3>
                            @foreach ($documents as $document)
                                <article class="order order-document">
                                    <h5 class="article-title"><a href="{{ route('document.view', ['document' => $document]) }}">{{ str_limit($document->title, config('site.user.order.limit')) }}</a></h5>
                                    @php
                                        $count = $document->answers->count();
                                    @endphp
                                    <span class="text-muted comment"><span class="glyphicon glyphicon-comment icon icon-comment" aria-hidden="true"></span> <span class="count comment-count">{{ trans_choice('document.count_offers', $count, ['count' => $count]) }}</span></span>
                                </article>
                            @endforeach
                        </section>
                        <hr>
                    @endif
                    @if ($calls->count() > 0)
                        <section class="user-orders-calls">
                            <h3 class="title title-user-order-calls">{{ trans('call.title') }} <a href="{{ route('user.calls') }}" class="pull-right small title-more">{{ trans('call._all_consultations') }}</a></h3>
                            @foreach ($calls as $call)
                                <article class="order order-call">
                                    <h5 class="article-title"><a href="{{ route('call.view', ['call' => $call]) }}">{{ str_limit($call->title, config('site.user.order.limit')) }}</a></h5>
                                    @php
                                        $count = $call->answers->count();
                                    @endphp
                                    <span class="text-muted comment"><span class="glyphicon glyphicon-comment icon icon-comment" aria-hidden="true"></span> <span class="count comment-count">{{ trans_choice('call.count_requests', $count, ['count' => $count]) }}</span></span>
                                </article>
                            @endforeach
                        </section>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ trans('app.how_to_get_started') }}</h3>
                </div>
                <div class="panel-body">
                {{ trans('app.how_to_get_started_description') }}
                </div>
                <div class="panel-footer">
                    <a href="{{ route('home') }}">{{ trans('app.tour_website') }}</a>
                </div>
            </div>
        </div>
    </div>

@stop
