{{--
    Отображение консультации по телефону.
--}}
@extends('layouts.app')
@extends('layouts.page.one')

@can('admin', App\Models\User::class)
    @section('admin-links')
        <li class="item"><a href="{{ route('call.update.form.admin', ['id' => $call->id, 'iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-pencil" aria-hidden="true"></span> <span class="text">{{ trans('call.form.action.update') }}</span></a></li>
        <li class="item"><a href="{{ route('call.delete.form.admin', ['id' => $call->id]) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> <span class="text">{{ trans('call.form.action.delete') }}</span></a></li>
        <li class="item"><a href="{{ route('calls.admin', ['iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-list" aria-hidden="true"></span> <span class="text">{{ trans('call.title') }}</span></a></li>
        @parent
    @stop
@endcan

@section('end')
    @parent
    <script src="{{ asset2('assets/scripts/view-general.js') }}"></script>
    <script>
        App.runCall();
    </script>
@stop

@section('breadcrumb')
    @parent
    @if (Gate::allows('list-call', $call))
        <li><a href="{{ route('calls') }}">@lang('call.consultations_by_phone')</a></li>
    @else
        <li>@lang('call.consultations_by_phone')</li>
    @endif
    <li class="active">{{ str_limit($call->title, config('site.breadcrumb.limit', 50)) }}</li>
@stop

@include('form.fields')

@php
    $user = Auth::user();
@endphp

@section('content')
    <article class="call-page" id="call">
        @include('call.article', ['call' => $call])
    </article>

    {{-- <div class="child-container child-container-clarify-answer">
        @include('clarify.form', ['type' => 'answer', 'to' => null, 'legend' => true])
    </div> --}}

    <div class="child-container child-container-complain">
        @include('complaint.form')
    </div>

    {{-- <div class="child-container child-container-reply-answer">
        @include('answer.form', ['type' => 'answer', 'to' => null, 'panel' => 'default'])
    </div> --}}

    @if (Gate::allows('answer', [App\Models\Answer::class, $call]))
        @include('answer.form_request', ['to' => $call])
    @endif
@stop