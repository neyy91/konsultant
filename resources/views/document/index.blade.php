{{--
    Список документов постранично.
--}}

@extends('layouts.app')
@extends('layouts.page.one')

@include('admin.dropdown')
@include('common.status')

@php
    $filtered = isset($filtered) ? $filtered : null;
    if (isset($filtered)) {
        $trans_title = trans('document.documents.' . str_replace('-', '_', $filtered['type']));
    }
    else {
        $filtered = null;
        $trans_title = trans('document.all_documents');
    }
@endphp

@can('admin', \App\Models\User::class)
    @section('admin-links')
        <li class="item"><a href="{{ route('documents.admin', ['iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-list" aria-hidden="true"></span> <span class="text">{{ trans('document.title') }}</span></a></li>
        @parent
    @stop
@endcan

@section('breadcrumb')
    @parent
    @if ($filtered)
        <li><a href="{{ route('documents') }}">{{ trans('document.all_documents') }}</a></li>
        @if (!empty($filtered['parents']))
            @foreach ($filtered['parents'] as $parent)
                <li><a href="{{ $parent['url'] }}">{{ $parent['name'] }}</a></li>
            @endforeach
        @endif
        <li>{{ $trans_title . ' ' . $filtered['title'] }}</li>
    @else
        <li class="active">{{ $trans_title }}</li>
    @endif
@stop

@section('content')
    <div class="clearfix items documents @if ($filtered)documents-{{ $filtered['type'] }} @endif">
        <h1>
            {{ $trans_title }} @if ($filtered) <span class="small">{{ $filtered['title'] }}</span> @endif
            @can('create', App\Models\Document::class)
                <span class="small pull-right"><a href="{{ route('document.create.form') }}" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> {{ trans('document.order_document') }}</a></span>
            @endcan
        </h1>

        @if (!empty($filtered['description']))
            <div class="description-page">
                {{ $filtered['description'] }}
            </div>
        @endif

        <div class="articles">
        @forelse ($documents as $num => $document)
            @if ($num > 0)
                <hr>
            @endif
            <article class="clearfix article document">
                <div class="statuses">
                    @macros(status, $document)
                </div>

                @can('admin', App\Models\User::class)
                    @macros(admin_dropdown, ['type' => 'document', 'model' => $document, 'dropdownClass' => 'pull-right', 'btnClass' => 'btn-default btn-xs'])
                @endcan

                <h3 class="title title-document"><a href="{{ route('document.view', ['document' => $document]) }}" class="title-link">{{ $document->title }}</a></h3>

                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-1">
                        <time pubdate datetime="{{ $document->created_at->toIso8601String() }}">{{ $document->created_at->format(config('site.date.long', 'd.m.Y H:i')) }}</time>, <span class="number">{{ trans('document.label_number', ['number' => $document->id]) }}</span>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-sm-offset-1 col-2">
                        <span class="user user-document author">{{ $document->user->firstname }}</span>
                        <span class="city city-document"><span class="city-label">@lang('city.from_city')</span> <span class="city-value">{{ $document->city->name }}</span></span>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-3">
                        @php
                            $count = $document->answers->count();
                        @endphp
                        <a href="{{ route('document.view', ['document' => $document]) }}#answers" class="pull-right count"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span> <span class="count-text">{{ trans_choice('document.count_offers', $count) }}</span></a>
                    </div>
                </div>
            </article>
        @empty
            <div class="empty">{{ trans('document.not_found') }}</div>
        @endforelse
        </div>
        {{ $documents->links() }}
    </div>
@stop