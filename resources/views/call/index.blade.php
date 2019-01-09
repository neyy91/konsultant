{{--
    Список консультации по телефону постранично
--}}

@extends('layouts.app')
@extends('layouts.page.one')

@include('admin.dropdown')
@include('common.status')

@php
    $filtered = isset($filtered) ? $filtered : null;
    if (isset($filtered)) {
        $trans_title = trans('call.calls.' . str_replace('-', '_', $filtered['type']));
    }
    else {
        $filtered = null;
        $trans_title = trans('call.consultations_by_phone');
    }
@endphp

@can('admin', \App\Models\User::class)
    @section('admin-links')
        <li class="item"><a href="{{ route('calls.admin', ['iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-list" aria-hidden="true"></span> <span class="text">{{ trans('call.title') }}</span></a></li>
        @parent
    @stop
@endcan

@section('breadcrumb')
    @parent
    @if ($filtered)
        <li><a href="{{ route('calls') }}">{{ trans('call.consultations_by_phone') }}</a></li>
        <li>{{ $trans_title . ' ' . $filtered['title'] }}</li>
    @else
        <li class="active">{{ $trans_title }}</li>
    @endif
@stop

@section('content')
    <div class="clearfix items calls @if ($filtered)calls-{{ $filtered['type'] }} @endif">
        <h1>
            {{ $trans_title }} @if ($filtered) <span class="small">{{ $filtered['title'] }}</span> @endif
            @can('create', App\Models\Call::class)
                <span class="small pull-right"><a href="{{ route('call.create.form') }}" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span> {{ trans('call.request_call') }}</a></span>
            @endcan
        </h1>
        @if (!empty($filtered['description']))
            <div class="description-page">
                {{ $filtered['description'] }}
            </div>
        @endif

        <div class="articles">
        @forelse ($calls as $num => $call)
            @if ($num > 0)
                <hr>
            @endif
            <article class="clearfix article call">
                <div class="statuses">
                    @macros(status, $call)
                </div>

                @can('admin', App\Models\User::class)
                    @macros(admin_dropdown, ['type' => 'call', 'model' => $call, 'dropdownClass' => 'pull-right', 'btnClass' => 'btn-default btn-xs'])
                @endcan

                <h3 class="title title-call"><a href="{{ route('call.view', ['call' => $call]) }}" class="title-link">{{ $call->title }}</a></h3>

                <div class="description description-call">{{ $call->description }}</div>

                <div class="row">
                    <div class="col-xs-12 col-sm-4">
                        <time pubdate datetime="{{ $call->created_at->toIso8601String() }}">{{ $call->created_at->format(config('site.date.long', 'd.m.Y H:i')) }}</time>,
                        <span class="number"><span class="number-label">{{ trans('call.label_number', ['number' => $call->id]) }}</span></span>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-sm-offset-1">
                        <span class="user user-call author">{{ $call->user->firstname }}</span>
                        <span class="city city-call"><span class="city-label">@lang('city.from_city')</span> <span class="city-value">{{ $call->city->name }}</span></span>
                    </div>
                    <div class="col-xs-12 col-sm-4">
                        @php
                            $count = $call->answers->count();
                        @endphp
                        <a href="{{ route('call.view', ['call' => $call]) }}#answers" class="pull-right count"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span> <span class="count">{{ trans_choice('call.count_requests', $count, ['count' => $count]) }}</span></a>
                    </div>
                </div>
            </article>
        @empty
            <div class="empty">{{ trans('call.not_found') }}</div>
        @endforelse
        </div>
        {{ $calls->links() }}
    </div>
@stop