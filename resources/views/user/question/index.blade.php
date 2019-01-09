{{--
    Список вопросов постранично.
--}}
@extends('layouts.app')
@extends('layouts.page.user')
@section('breadcrumb')
    @parent
    <li><a href="{{ route('user.dashboard') }}">{{ trans('user.dashboard') }}</a></li>
    <li class="active">{{ trans('question.title') }}</li>
@stop
@section('content')
    <h1 class="page-title">{{ trans('question.title') }}</h1>
    <div class="panel panel-default">
        <div class="panel-body">
            @include('user.question.filter')
        </div>
    </div>
    <div class="clearfix items user-items questions user-questions">
        @forelse ($questions as $num => $question)
            @if ($num > 0)
                <hr>
            @endif
            <article class="clearfix article question">
                <h3 class="title title-question"><a href="{{ route('question.view', ['question' => $question]) }}" class="title-link">{{ $question->title }}</a></h3>
                <div class="description description-question">{{ $question->description }}</div>
                <time pubdate datetime="{{ $question->created_at->toIso8601String() }}">{{ $question->created_at->format(config('site.date.long', 'd.m.Y H:i')) }}</time>, <span class="number"><span class="number-label">{{ trans('question.label_number', ['number' => $question->id]) }}</span></span>, <a href="{{ route('questions.city', ['city' => $question->city]) }}" class="city toggle-tooltip" title="{{ $question->city->region->name }}"><span class="city-label">г.</span> <span class="city-value">{{ $question->city->name }}</span></a>
                @php
                    $count = $question->answers->count();
                @endphp
                <a href="{{ route('question.view', ['question' => $question]) }}#answers" class="pull-right count"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span> <span class="count">{{ trans_choice('question.count_answers', $count, ['count' => $count]) }}</span></a>
            </article>
        @empty
            <div class="empty">{{ trans('question.not_found') }}</div>
            <a href="{{ route('question.create.form') }}" class="btn btn-default btn-big">{{ trans('question.ask') }}</a>
        @endforelse
        {{ $questions->links() }}
    </div>
@stop