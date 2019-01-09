@unlessmacros('answer_replies')
    @include('answer.replies')
@endif
@unlessmacros('status')
    @include('common.status')
@endif
@unlessmacros('macros_like_thumbs')
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
        'clarify' => Gate::allows('clarify', [App\Models\Clarify::class, $document]),
        'answer' => Gate::allows('answer', [App\Models\Answer::class, $document]),
        'complain' => Gate::allows('complain', [App\Models\Complaint::class, $document]),
        'chat' => Gate::allows('chat', $document->user)
    ];
@endphp

<div class="statuses">
    @macros(status, $document)
</div>
<h1 class="title title-document">{{ $document->title }}</h1>

<div class="description description-document">{{ $document->description }}</p>

<div class="clearfix item-info item-info-document">
    <div class="row">
        <div class="col-xs-12 col-sm-4">
            <time pubdate datetime="{{ $document->created_at->toIso8601String() }}" class="date date-pub">{{ $document->created_at->format(config('site.date.long', 'd.m.Y, H:i')) }}</time>,
            <span class="number">{{ trans('document.label_number', ['number' => $document->id]) }}</span>
        </div>
        <div class="col-xs-12 col-sm-3 col-sm-offset-1">
            @if ($user && $user->id == $document->user->id)
                <span class="user user-document self-document author">@lang('app.your')</span>
            @else
                @if ($document->user)
                    @can('documents-user', App\Models\Document::class)
                        <a href="{{ route('documents.user', ['user' => $document->user]) }}" class="user user-document author">{{ $document->user->firstname }}</a>
                    @else
                        <span class="user user-document author">{{ $document->user->firstname }}</span>
                    @endcan
                @endif
                @if ($document->city)
                    <span class="city city-document"><span class="city-label">@lang('city.from_city')</span> <a href="{{ route('documents.city', ['city' => $document->city]) }}" class="city-value">{{ $document->city->name }}</a></span>
                @endif
            @endif
        </div>
        <div class="col-xs-12 col-sm-4">
            @if ($document->file && Gate::allows('access', $document->file))
                <div class="file file-document">
                    <a href="{{ route('file', ['file' => $document->file, 'name' => $document->file->basename]) }}" class="pull-right file-link file-link-type-{{ pathinfo($document->file->basename, PATHINFO_EXTENSION) }}" target="_blank"><span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> {{ trans('file.uploaded_file') }}</a>
                </div>
            @endif
        </div>
    </div>
</div>

@php
    $count = $document->clarifies->count();
@endphp
<section class="clarifies clarifies-document @if ($count == 0) hide @endif" id="clarifies">
    <h4 class="title title-clarifies title-clarifies-document">{{ trans_choice('clarify.count_clarifies', $count, ['count' => $count]) }}
    </h4>
    <div class="clarify-items clarify-document-items" id="clarifiesDocument{{ $document->id }}">
        @macros(clarify_list, ['type' => 'document', 'clarifies' => $document->clarifies])
    </div>
</section>

@php
    $deniesAllActions = empty(array_filter($canActions));
@endphp
@if (!$deniesAllActions)
    <div class="clearfix actions actions-document">
        <hr>
        @if($canActions['small-info'])
            <a href="#userInfo" class="script-action state action-userinfo action-userinfo-document toggle-collapse collapsed" data-toggle="collapse" aria-controls="userInfo" aria-expanded="false"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> {{ trans('user.about_client') }}</a>
        @endif
        @if($canActions['clarify'])
            <a href="#clarifyDocument" class="script-action toggle-collapse toggle-clarifies toggle-clarify-form collapsed" data-toggle="collapse" aria-controls="clarifyDocument" aria-expanded="false"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> <span class="show-text">{{ trans('clarify.add_clarification') }}</span><span class="hide-text">{{ trans('clarify.hide_form_clarification') }}</span></a>
        @endif
        @if($canActions['answer'])
            <a href="#answerDocumentPanel" class="script-action action-go-form-answer scroll-to"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span> {{ trans('document.offer_to_create_document') }}</a>
        @endif
        @if ($canActions['chat'])
            <a href="{{ route('user.chat', ['user' => $document->user]) }}" class="chat document-chat script-action chat-action chat-start ajax" data-user="{{ $user->lawyer->id }}" data-on="click" data-ajax-url="{{ route('user.chat', ['user' => $document->user]) }}" data-ajax-method="POST" data-ajax-context="this" data-ajax-data-type="json" data-ajax-error="App.messageOnError" data-ajax-success="App.Chat.startSuccess"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span></a>
        @endif
        {{-- @if($canActions['complain'])
            <a href="#complainDocument" class="script-action text-warning action-complain action-complain-document toggle-parent toggle-collapse collapsed" data-toggle="collapse" aria-controls="complainDocument" aria-expanded="false" data-child="formComplainAgainst" data-parent="parentComplainDocument" data-url="{{ route('complain.document', ['id' => $document->id]) }}"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> {{ trans('complaint.complain') }}</a>
        @endif --}}
    </div>
@endif

@if ($canActions['small-info'])
    <div id="userInfo" class="collapse userinfo-document">
        <hr>
        @include('user.info', ['user' => $document->user])
    </div>
@endif

@if ($canActions['clarify'])
    <div class="collapse clarify-document-form" id="clarifyDocument">
        <hr>
        @include('clarify.form', ['type' => 'document', 'to' => $document])
    </div>
@endif

{{-- @if ($canActions['complain'])
    <div class="collapse complain complain-document" id="complainDocument">
        <div class="parent-container parent-container-complain-document" id="parentComplainDocument"></div>
    </div>
@endif --}}
@php
    $answerIs = $document->answers()->where('is', 1)->first();
@endphp

@if ($answerIs && $answerIs->from && $answerIs->from->user && $answerIs->from->user->id === $user->id)
    <hr>
    <div class="alert alert-success">
        <b>@lang('document.clarify_file')</b>
    </div>
@endif

@if (Gate::allows('pay', $document))
    <hr>
    <div class="alert alert-info">
        <a href="#pay" class="script-action action-to-pay">@lang('document.pay_need')</a>
    </div>
@endif

<hr>
<section class="answers answers-document" id="answers">
    @php
        $count = $document->answers->count();
    @endphp
    <h2>
        <span class="title-text">{{ trans('document.offers_from_law') }}</span>
        <span class="small">(<span class="count count-title">{{ trans_choice('document.count_offers', $count, ['count' => $count]) }}</span>)</span>
    </h2>
    @if (Gate::allows('answers', $document))
        <div class="answer-items">
            @include('answer.list', ['type' => 'document', 'route' => ['name' => 'document.answer', 'params' => ['document' => $document]],  'model' => $document, 'cost' => true, 'likes' => false, 'clarifies' => true])
        </div>
    @endif
    @if(Gate::allows('pay', $document))
        <hr>
        <div id="pay">
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">@lang('document.pay_need')</h3>
                        </div>
                        <div class="panel-body">
                            @include('pay.form', [
                                'user' => $document->user,
                                'order' => $document->payment->id,
                                'url' => [
                                    'default' => route('document.view', ['document' => $document]),
                                    'success' => route('document.view', ['document' => $document, 'payed' => 1]),
                                    'fail' => route('document.view', ['document' => $document, 'failed' => 1]),
                                ],
                                'type' => 'document',
                                'pay' => [
                                    'readonly' => true,
                                    'value' => $document->payCost,
                                ],
                            ])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</section>

@if (!$answerIs)
    @include('document.answer_is_modal')   
@endif
