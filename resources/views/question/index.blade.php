{{--
    Список вопросов постранично
--}}
@extends('layouts.app')
@extends('layouts.page.one')

@php
    $filtered = isset($filtered) ? $filtered : null;
    if (isset($filtered)) {
        $trans_title = trans('question.questions.' . str_replace('-', '_', $filtered['type']));
    }
    else {
        $filtered = null;
        $trans_title = trans('question.all_questions');
    }
@endphp

@section('end')
    @parent
    <script src="{{ asset2('assets/scripts/list-general.js') }}"></script>
@stop

@can('admin', \App\Models\User::class)
    @section('admin-links')
        <li class="item"><a href="{{ route('questions.admin', ['iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-list" aria-hidden="true"></span> <span class="text">{{ trans('question.title') }}</span></a></li>
        @parent
    @stop
@endcan

@section('breadcrumb')
    @parent
    @if ($filtered)
        <li><a href="{{ route('questions') }}">{{ trans('question.all_questions') }}</a></li>
        @if (!empty($filtered['parents']))
            @foreach ($filtered['parents'] as $parent)
                <li><a href="{{ $parent['url'] }}">{{ $parent['name'] }}</a></li>
            @endforeach
        @endif
        <li>{{ trans($trans_title) . ' ' . $filtered['title'] }}</li>
    @else
        <li class="active">{{ $trans_title }}</li>
    @endif
@stop

@include('form.fields')

@section('content')
    <div class="clearfix items questions @if ($filtered)questions-{{ $filtered['type'] }} @endif">
        <h1>
            {{ $trans_title }} @if ($filtered) &laquo;{{ $filtered['title'] }}&raquo; @endif
            @if(Auth::guest() || Auth::check() && Gate::allows('create', App\Models\Question::class))
                <span class="small pull-right"><a href="{{ route('question.create.form') }}" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> {{ trans('question.ask') }}</a></span>
            @endif
        </h1>
        @if (!empty($filtered['description']))
            <div class="description-page">
                {{ $filtered['description'] }}
            </div>
        @endif
        @if (isset($search) && $search)
            <div class="search">
                @include('question.search_form')
            </div>
        @endif
        <div class="articles">
            @include('question.items', ['questions' => $questions, 'categoryLawShow' => !$filtered || ($filtered && $filtered['type'] !== 'category-law'), 'bookmarkShow' => true])
        </div>
        <nav class="pagination-nav" aria-label="@lang('app.pagination_label')">
            {{ $questions->links() }}
        </nav>
    </div>
@stop