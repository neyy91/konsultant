{{--
    Список консультации постранично.
--}}
@extends('layouts.app')
@extends('layouts.page.user')
@section('breadcrumb')
    @parent
    <li><a href="{{ route('user.dashboard') }}">{{ trans('user.dashboard') }}</a></li>
    <li class="active">{{ trans('call.title') }}</li>
@stop
@section('content')
    <h1 class="page-title">{{ trans('call.title') }}</h1>
    <div class="panel panel-default">
        <div class="panel-body">
            @include('user.call.filter')
        </div>
    </div>
    <div class="clearfix items user-items calls user-calls">
        @forelse ($calls as $num => $call)
            @if ($num > 0)
                <hr>
            @endif
            <article class="clearfix call">
                <h3 class="title title-call"><a href="{{ route('call.view', ['call' => $call]) }}" class="title-link">{{ $call->title }}</a></h3>
                <div class="description description-call">{{ $call->description }}</div>
                <time pubdate datetime="{{ $call->created_at->toIso8601String() }}">{{ $call->created_at->format(config('site.date.long', 'd.m.Y H:i')) }}</time>, <span class="number"><span class="number-label">{{ trans('call.label_number', ['number' => $call->id]) }}</span>, <a href="{{ route('calls.city', ['city' => $call->city]) }}" class="city city-call"><span class="city-label">г.</span> <span class="city-value">{{ $call->city->name }}</span></a></span>
                @php
                    $count = $call->answers->count();
                @endphp
                <a href="{{ route('call.view', ['call' => $call]) }}#answers" class="pull-right count"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span> <span class="count-text">{{ trans_choice('call.count_requests', $count, ['count' => $count]) }}</span></a>
            </article>
        @empty
            <div class="empty">{{ trans('call.not_found') }}</div>
            <a href="{{ route('call.create.form') }}" class="btn btn-default btn-big">{{ trans('call.request_consultation_by_phone') }}</a>
        @endforelse
        {{ $calls->links() }}
    </div>
@stop