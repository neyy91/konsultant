@unlessmacros('answer_replies')
    @include('answer.replies')
@endif
@unlessmacros('status')
    @include('common.status')
@endif
@unlessmacros('like_thumbs')
    @include('like.thumbs')
@endif
@unlessmacros('input')
    @include('form.fields')
@endif
@unlessmacros('clarify_list')
    @include('clarify.macros')
@endif

@php
    $user = Auth::user();
    $canActions = [
        'small-info' => Gate::allows('small-info', App\Models\User::class),
        'clarify' => Gate::allows('clarify', [App\Models\Clarify::class, $question]),
        'answer' => Gate::allows('answer', [App\Models\Answer::class, $question]),
        'chat' => Gate::allows('chat', $question->user)
        // 'complain' => Gate::allows('complain', [App\Models\Complaint::class, $question]),
    ];
@endphp

<div class="statuses">
    @macros(status, $question)
</div>
<h1 class="title title-question">{{ $question->title }}</h1>
<div class="description description-question">{{ $question->description }}</div>
<div class="clearfix item-info item-info-question">
    <div class="row">
        @if ($question->created_at)
            <div class="col-xs-12 col-sm-4">
                <time pubdate datetime="{{ $question->created_at->toIso8601String() }}" class="date date-pub">{{ $question->created_at->format(config('site.date.long', 'd.m.Y, H:i')) }}</time>,
                <span class="number">{{ trans('question.label_number', ['number' => $question->id]) }}</span>
            </div>
        @endif
        <div class="col-xs-12 col-sm-3 col-sm-offset-1">
            @if ($user && $user->id == $question->user->id)
                <span class="user user-question self-question author">@lang('app.your')</span>
            @else
                @if ($question->user)
                    @can('questions-user', App\Models\Question::class)
                        <a href="{{ route('questions.user', ['user' => $question->user]) }}" class="user user-question author">{{ $question->user->firstname }}</a>
                    @else
                        <span class="user user-question author">{{ $question->user->firstname }}</span>
                    @endcan
                @endif
                @if ($question->city)
                    <span class="city city-question"><span class="city-label">@lang('city.from_city')</span> <a href="{{ route('questions.city', ['city' => $question->city]) }}" class="city-value">{{ $question->city->name }}</a></span>
                @endif
            @endif
        </div>
        <div class="col-xs-12 col-sm-4">
            @if ($question->file && Gate::allows('access', $question->file))
                <div class="file file-question">
                    <a href="{{ route('file', ['file' => $question->file, 'name' => $question->file->basename]) }}" class="pull-right file-link file-link-type-{{ pathinfo($question->file->basename, PATHINFO_EXTENSION) }}" target="_blank" rel="nofollow"><span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> {{ trans('file.uploaded_file') }}</a>
                </div>
            @endif
        </div>
    </div>
</div>

@php
    $count = $question->clarifies->count();
@endphp
<section class="clarifies clarifies-question @if ($count == 0) hide @endif" id="clarifies">
    <h4 class="title title-clarifies title-clarifies-question">{{ trans_choice('clarify.count_clarifies', $count, ['count' => $count]) }}</h4>
    <div class="clarify-items clarify-question-items" id="clarifiesQuestion{{ $question->id }}">
        @macros(clarify_list, ['type' => 'question', 'clarifies' => $question->clarifies])
    </div>
</section>
@php
    $deniesAllActions = empty(array_filter($canActions));
@endphp
@if (!$deniesAllActions)
    <div class="clearfix actions actions-question">
        <hr>
        @if($canActions['small-info'])
            <a href="#userInfo" class="script-action state action-userinfo action-userinfo-question toggle-collapse collapsed" data-toggle="collapse" aria-controls="userInfo" aria-expanded="false"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> {{ trans('user.about_client') }}</a>
        @endif
        @if($canActions['clarify'])
            <a href="#clarifyQuestion" class="script-action state action-clarify action-clarify-question toggle-collapse collapsed" data-toggle="collapse" aria-controls="clarifyQuestion" aria-expanded="false"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> {{ trans('clarify.add_clarification') }}</a>
        @endif
        @if($canActions['answer'])
            <a href="#answerQuestionPanel" class="script-action action-go-form-answer scroll-to"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span> {{ trans('question.answer_to_this_question') }}</a>
        @endif
        @if ($canActions['chat'])
            <a href="{{ route('user.chat', ['user' => $question->user]) }}" class="chat document-chat script-action chat-action chat-start ajax" data-user="{{ $user->lawyer->id }}" data-on="click" data-ajax-url="{{ route('user.chat', ['user' => $question->user]) }}" data-ajax-method="POST" data-ajax-context="this" data-ajax-data-type="json" data-ajax-error="App.messageOnError" data-ajax-success="App.Chat.startSuccess"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span></a>
        @endif
        {{-- @if($canActions['complain'])
            <a href="#complainQuestion" class="script-action state text-warning action-complain action-complain-question toggle-parent toggle-collapse collapsed" data-toggle="collapse" aria-controls="complainQuestion" aria-expanded="false" data-child="formComplainAgainst" data-parent="parentComplainQuestion" data-url="{{ route('complain.question', ['id' => $question->id]) }}"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> {{ trans('complaint.complain') }}</a>
        @endif --}}
    </div>
@endif

@if ($canActions['small-info'])
    <div id="userInfo" class="collapse userinfo-question">
        <hr>
        @include('user.info', ['user' => $question->user])
    </div>
@endif

@if ($canActions['clarify'])
    <div class="collapse clarify-question-form" id="clarifyQuestion">
        <hr>
        @include('clarify.form', ['type' => 'question', 'to' => $question])
    </div>
@endif

{{--         @if ($canActions['complain'])
    <div class="collapse complain complain-question" id="complainQuestion">
        <div class="parent-container parent-container-complain-question" id="parentComplainQuestion"></div>
    </div>
@endif
--}}
<hr>
<section class="answers answers-question" id="answers">
    @php
        $count = $question->answers->count();
    @endphp
    <h2>
        <span class="title-text">{{ trans('answer.title_law') }}</span>
        <span class="small">(<span class="count count-title">{{ trans_choice('question.count_answers', $count, ['count' => $count]) }}</span>)</span>
    </h2>
    @if (Gate::allows('answers', $question))
        <div class="answer-items">
            @include('answer.list', ['type' => 'question', 'route' => ['name' => 'question.answer', 'params' => ['question' => $question]],  'model' => $question, 'clarifies' => true])
        </div>
    @elseif(Gate::allows('pay', $question))
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">@lang('question.pay_need')</h3>
                    </div>
                    <div class="panel-body">
                        @include('pay.form', [
                            'user' => $question->user,
                            'order' => $question->payment->id,
                            'url' => [
                                'default' => route('question.view', ['question' => $question]),
                                'success' => route('question.view', ['question' => $question, 'success' => 1]),
                                'fail' => route('question.view', ['question' => $question, 'fail' => 1]),
                            ],
                            'type' => 'question',
                            'pay' => [
                                'readonly' => $question->pay === $question::PAY_VIP ? false : true,
                                'value' => $question->payCost,
                            ],
                        ])
                    </div>
                    @if ($question->pay === $question::PAY_VIP)
                        <div class="panel-footer">
                            @lang('question.pay_vip')
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-danger">
            <strong>@lang('app.no_access')</strong>
        </div>
    @endif
</section>

@include('question.answer_is_modal')