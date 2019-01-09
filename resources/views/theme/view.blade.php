{{--
    Отображение категории права.
--}}

@extends('layouts.app')
@extends('layouts.page.one')

@section('end')
    @parent
    <script src="{{ asset2('assets/scripts/list-others.js') }}"></script>
@stop

@can('admin', App\Models\User::class)
    @section('admin-links')
        <li class="item"><a href="{{ route('theme.update.form.admin', ['id' => $theme->id, 'iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-pencil" aria-hidden="true"></span> <span class="text">{{ trans('theme.update_theme') }}</span></a></li>
        <li class="item"><a href="{{ route('theme.delete.form.admin', ['id' => $theme->id, 'iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-trash" aria-hidden="true"></span> <span class="text">{{ trans('theme.delete_theme') }}</span></a></li>
        <li class="item"><a href="{{ route('theme.create.form.admin', ['iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-plus" aria-hidden="true"></span> <span class="text">{{ trans('theme.add_theme') }}</span></a></li>
        <li class="item"><a href="{{ route('themes.admin', ['iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-list" aria-hidden="true"></span> <span class="text">{{ trans('theme.title') }}</span></a></li>
        @parent
    @stop
@endcan

@section('breadcrumb')
    @parent
    <li><a href="{{ route('themes') }}">@lang('theme.title')</a></li>
    <li class="active">{{ $theme->name }}</li>
@endsection


@section('content')
    <article class="theme-page">
        <h1>{{ $theme->name }}</h1>

        <div class="description">{{ $theme->description }}</div>

        <div class="text">
            {{ $theme->text }}
        </div>

    </article>
    @if (isset($questions) && count($questions) > 0)
        <section class="theme-questions">
            <h2>@lang('question.questions.theme') <span class="small">{{ $theme->name }}</span></h2>
            @include('question.list', ['questions' => $questions])
            <h4 class="all all-questions pull-right"><a href="{{ route('questions') }}" class="all-link">{{ trans('question.view_all_questions') }}</a></h4>
            <h4 class="all all-theme"><a href="{{ route('questions.theme', ['theme' => $theme]) }}" class="all-link all-link-theme">{{ trans('theme.view_all_questions_theme', ['name' => $theme->name]) }}</a></h4>
        </section>
    @endif
@stop