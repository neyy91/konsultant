{{--
    Отображение категории права.
--}}

@extends('layouts.app')
@extends('layouts.page.one')

@section('breadcrumb')
    @parent
    <li><a href="{{ route('categories') }}">{{ trans('category.title') }}</a></li>
    @if ($categoryLaw->parent && $categoryLaw->parent->parent)
        <li><a href="{{ route('category.view', ['category' => $categoryLaw->parent]) }}">{{ $categoryLaw->parent->name }}</a></li>
    @endif
    <li class="active">{{ $categoryLaw->name }}</li>
@stop

@can('admin', App\Models\User::class)
    @section('admin-links')
        <li class="item"><a href="{{ route('category.update.form.admin', ['id' => $categoryLaw->id, 'iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-pencil" aria-hidden="true"></span> <span class="text">@lang('category.update_category')</span></a></li>
        <li class="item"><a href="{{ route('category.delete.form.admin', ['id' => $categoryLaw->id, 'iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-trash" aria-hidden="true"></span> <span class="text">@lang('category.delete_category')</span></a></li>
        <li class="item"><a href="{{ route('category.create.form.admin', ['iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-plus" aria-hidden="true"></span> <span class="text">{{ trans('category.add_category') }}</span></a></li>
        <li class="item"><a href="{{ route('categories.admin', ['iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-list" aria-hidden="true"></span> <span class="text">{{ trans('category.title') }}</span></a></li>
        @parent
    @endsection
@endcan

@section('content')
    <article class="category-page">
        <h1>{{ $categoryLaw->name }}</h1>
        <p style="font-style: italic;">{{ $categoryLaw->description }}</p>
        <div class="text">
            {{ $categoryLaw->text }}
        </div>
    </article>
    @if (isset($questions) && count($questions) > 0)
        <section class="category-questions">
            <h2>{{ trans('question.questions.category_law') }} <span class="small">{{ $categoryLaw->name }}</span></h2>
            @include('question.list', ['questions' => $questions])
            <h4 class="pull-right all all-question"><a href="{{ route('questions') }}" class="all-link">{{ trans('category.view_all_questions') }}</a></h4>
            <h4 class="all all-category"><a href="{{ route('questions.category', ['category' => $categoryLaw]) }}" class="all-link all-link-category">{{ trans('category.view_all_questions_category', ['name' => $categoryLaw->name]) }}</a></h4>
        </section>
    @endif
@stop