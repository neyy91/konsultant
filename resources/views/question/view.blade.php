{{--
    Отображение вопроса.
--}}

@extends('layouts.app')
@extends('layouts.page.one')

@can('admin', App\Models\User::class)
    @section('admin-links')
        <li class="item"><a href="{{ route('question.update.form.admin', ['id' => $question->id, 'iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-pencil" aria-hidden="true"></span> <span class="text">{{ trans('question.form.action.update') }}</span></a></li>
        <li class="item"><a href="{{ route('question.delete.form.admin', ['id' => $question->id]) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> <span class="text">{{ trans('question.form.action.delete') }}</span></a></li>
        <li class="item"><a href="{{ route('questions.admin', ['iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-list" aria-hidden="true"></span> <span class="text">{{ trans('question.title') }}</span></a></li>
        @parent
    @stop
@endcan

@section('breadcrumb')
    @parent
    <li><a href="{{ route('questions') }}">{{ trans('question.all_questions') }}</a></li>
    @if ($question->categoryLaw)
        @if ($question->categoryLaw->parent)
            <li><a href="{{ route('questions.category', ['category' => $question->categoryLaw->parent]) }}">{{ $question->categoryLaw->parent->name }}</a></li>
        @endif
        <li><a href="{{ route('questions.category', ['category' => $question->categoryLaw]) }}">{{ $question->categoryLaw->name }}</a></li>
    @endif
    <li class="active">{{ str_limit($question->title, config('site.breadcrumb.limit', 50)) }}</li>
@stop

@section('end')
    @parent
    <script src="{{ asset2('assets/scripts/view-general.js') }}"></script>
    <script>
        App.runQuestion();
    </script>
@stop

@include('form.fields')
@include('clarify.macros')

@section('content')
    <article class="page-view question-page" id="question">
        @include('question.article', ['question' => $question])
    </article>
    
    <div class="child-container child-container-clarify-answer">
        @include('clarify.form', ['type' => 'answer', 'to' => null, 'legend' => true])
    </div>

    <div class="child-container child-container-complain">
        @include('complaint.form')
    </div>

    <div class="child-container child-container-reply-answer">
        @include('answer.form_reply')
    </div>
    
    @if (Gate::allows('answer', [App\Models\Answer::class, $question])) 
        <hr>
        @include('answer.form', ['type' => 'question', 'to' => $question, 'panel' => 'primary'])
    @endif

    @if ($question->expertises->count() > 0)
        <div id="expertises">
            @can('onexpertise', $question)
                <div class="clearfix alert alert-info question-onexpertise">
                    <div class="pull-left">
                        <strong>@lang('expertise.send_question_expertise_description')</strong>
                    </div>
                    <div class="pull-right">
                        <a href="#" class="btn btn-primary ajax" data-on="click" data-ajax-url="{{ route('question.expertise', ['id' => $question->id]) }}" data-ajax-method="PUT" data-ajax-context="#expertises" data-ajax-data-type="json" data-ajax-success="App.questionExpertise" data-ajax-error="App.messageOnError">@lang('expertise.send_question_expertise')</a>
                    </div>
                </div>
            @endcan
            <div class="expertises-container">
                @can('expertises', [App\Models\Expertise::class, $question])
                    <hr>
                    @include('expertise.question', ['expertises' => $expertises, 'question' => $question])
                @elseif($question->status == $question::STATUS_EXPERTISE)
                    <div class="alert alert-warning expertise-info">
                        <strong>@lang('expertise.there_is_expertise')</strong> @lang('expertise.question_sent_to_expertise')
                    </div>
                @endcan
            </div>
        </div>
    @endif
    
@stop
