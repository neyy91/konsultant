{{--
    Карточка города
--}}

@extends('layouts.app')
@extends('layouts.page.one')

@section('breadcrumb')
    @parent
    <li><a href="{{ route('cities') }}">{{ trans('city.title') }}</a></li>
    <li class="active">{{ $city->name }}, <span class="region small">{{ $city->region->name }}</span></span></li>
@stop

@can('admin', App\Models\User::class)
    @section('admin-links')
        <li class="item"><a href="{{ route('city.update.form.admin', ['id' => $city->id, 'iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-pencil" aria-hidden="true"></span> <span class="text">@lang('city.update_city')</span></a></li>
        <li class="item"><a href="{{ route('city.delete.form.admin', ['id' => $city->id, 'iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-trash" aria-hidden="true"></span> <span class="text">@lang('city.delete_city')</span></a></li>
        <li class="item"><a href="{{ route('city.create.form.admin', ['iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-plus" aria-hidden="true"></span> <span class="text">{{ trans('city.add_city') }}</span></a></li>
        <li class="item"><a href="{{ route('cities.admin', ['iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-list" aria-hidden="true"></span> <span class="text">{{ trans('city.title') }}</span></a></li>
        @parent
    @endsection
@endcan

@section('content')
    <article class="city-page">
        <h1>{{ $city->name }} <span class="small">{{ $city->region->name }}</span></h1>
        <p style="font-style: italic;">{{ $city->description }}</p>
        <div class="text">{{ $city->text }}</div>
    </article>
    @if (isset($questions) && count($questions) > 0)
        <section class="city-questions">
            <h2>{{ trans('question.questions.city') }}</h2>
            @include('question.list', ['questions' => $questions])
            <h4 class="all all-questions"><a href="{{ route('questions') }}" class="pull-right all-questions">@lang('question.view_all_questions')</a></h4>
            <h4 class="all all-city"><a href="{{ route('questions.city', ['city' => $city]) }}" class="all-link all-link-city">@lang('question.view_all_questions_in_city', ['name' => $city->name])</a></h4>
        </section>
    @endif
@stop