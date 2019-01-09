{{--
    Список документов постранично.
--}}
@extends('layouts.app')
@extends('layouts.page.user')
@section('breadcrumb')
    @parent
    <li><a href="{{ route('user.dashboard') }}">{{ trans('user.dashboard') }}</a></li>
    <li class="active">{{ trans('document.title') }}</li>
@stop
@section('content')
    <h1 class="page-title">{{ trans('document.title') }}</h1>
    <div class="panel panel-default">
        <div class="panel-body">
            @include('user.document.filter')
        </div>
    </div>
    <div class="clearfix items user-items documents user-documents">
        @forelse ($documents as $num => $document)
            @if ($num > 0)
                <hr>
            @endif
            <article class="clearfix document">
                <h3 class="title title-document"><a href="{{ route('document.view', ['document' => $document]) }}" class="title-link">{{ $document->title }}</a></h3>
                <div class="description description-document">{{ $document->description }}</div>
                <time pubdate datetime="{{ $document->created_at->toIso8601String() }}">{{ $document->created_at->format(config('site.date.long', 'd.m.Y H:i')) }}</time>, <span class="number">{{ trans('document.label_number', ['number' => $document->id]) }}</span>, <a href="{{ route('documents.city', ['city' => $document->city]) }}" class="city city-document"><span class="city-label">г.</span> <span class="city-value">{{ $document->city->name }}</span></a>
                @php
                    $count = $document->answers->count();
                @endphp
                <a href="{{ route('document.view', ['document' => $document]) }}#answers" class="pull-right count"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span> <span class="count-text">{{ trans_choice('document.count_offers', $count, ['count' => $count]) }}</span></a>
            </article>
        @empty
            <div class="empty">{{ trans('document.not_found') }}</div>
            <a href="{{ route('document.create.form') }}" class="btn btn-default btn-big">{{ trans('document.request_consultation_by_phone') }}</a>
        @endforelse
        {{ $documents->links() }}
    </div>
@stop