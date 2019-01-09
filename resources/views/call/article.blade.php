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
        'clarify' => Gate::allows('clarify', [App\Models\Clarify::class, $call]),
        'answer' => Gate::allows('answer', [App\Models\Answer::class, $call]),
        // 'complain' => Gate::allows('complain', [App\Models\Complaint::class, $call]),
    ];
@endphp

<div class="statuses">
    @macros(status, $call)
</div>
<h1 class="title title-call">{{ $call->title }}</h1>
<div class="description description-call">{{ $call->description }}</div>
<div class="clearfix item-info item-info-call">
    <div class="row">
        <div class="col-xs-12 col-sm-4">
            <time pubdate datetime="{{ $call->created_at->toIso8601String() }}" class="date date-pub">{{ $call->created_at->format(config('site.date.long', 'd.m.Y, H:i')) }}</time>,
            <span class="number">{{ trans('call.label_number', ['number' => $call->id]) }}</span>
        </div>
        <div class="col-xs-12 col-sm-3 col-sm-offset-1">
            @if ($user && $user->id === $call->user->id)
                <span class="user user-call self-call author">@lang('app.your')</span>
            @else
                @can('calls-user', App\Models\Call::class)
                    <a href="{{ route('calls.user', ['user' => $call->user]) }}" class="user user-call author">{{ $call->user->firstname }}</a>
                @else
                    <span class="user user-call author">{{ $call->user->firstname }}</span>
                @endcan
                @if ($call->city)
                    <span class="city city-call"><span class="city-label">@lang('city.from_city')</span> <a href="{{ route('calls.city', ['city' => $call->city]) }}" class="city-value">{{ $call->city->name }}</a></span>
                @endif
            @endif
        </div>
        <div class="col-xs-12 col-sm-4">
            @if ($call->file && Gate::allows('access', $call->file))
                <div class="file file-call">
                    <a href="{{ route('file', ['file' => $call->file, 'name' => $call->file->basename]) }}" class="pull-right file-link file-link-type-{{ pathinfo($call->file->basename, PATHINFO_EXTENSION) }}" target="_blank"><span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> {{ trans('file.uploaded_file') }}</a>
                </div>
            @endif
        </div>
    </div>
    @can('contacts', $call)
        <hr>
        <h3 class="title-telephone">@lang('call.contact_phone'): <a class="user-telephone" href="tel:{{ $call->user->telephone }}"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span> {{ $call->user->telephone }}</a></h3>
    @endcan

    @php
        $answerIs = $call->answers()->where('is', 1)->first();
    @endphp
    @if ($answerIs && $call->user_id === $user->id)
        <hr>
        <div class="alert alert-success">
            <b>@lang('call.lawyer_will_contact')</b>
        </div>
    @endif

</div>


@php
    $count = $call->clarifies->count();
@endphp
<section class="clarifies clarifies-call @if ($count == 0) hide @endif" id="clarifies">
    <h4 class="title title-clarifies title-clarifies-call">{{ trans_choice('clarify.count_clarifies', $count, ['count' => $count]) }}
    </h4>
    <div class="clarify-items clarify-call-items" id="clarifiesCall{{ $call->id }}">
        @macros(clarify_list, ['type' => 'call', 'clarifies' => $call->clarifies])
    </div>
</section>
@php
    $deniesAllActions = empty(array_filter($canActions));
@endphp
@if (!$deniesAllActions)
    <hr>
    <div class="clearfix actions actions-call">
        @if($canActions['small-info'])
            <a href="#userInfo" class="script-action state action-userinfo action-userinfo-call toggle-collapse collapsed" data-toggle="collapse" aria-controls="userInfo" aria-expanded="false"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> {{ trans('user.about_client') }}</a>
        @endif
        @if ($canActions['clarify'])
            <a href="#clarifyCall" class="script-action state toggle-collapse toggle-clarifies toggle-clarify-form collapsed" data-toggle="collapse" aria-controls="clarifyCall" aria-expanded="false"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> <span class="show-text">{{ trans('clarify.add_clarification') }}</span><span class="hide-text">{{ trans('clarify.hide_form_clarification') }}</span></a>
        @endif
        @if ($canActions['answer'])
            <a href="#answerCallPanel" class="script-action state action-go-form-answer scroll-to"><span class="glyphicon glyphicon-phone" aria-hidden="true"></span> {{ trans('call.to_request_contacts_for_consultation') }}</a>
        @endif
        {{-- @if ($canActions['complain'])
            <a href="#complainCall" class="script-action state text-warning action-complain action-complain-call toggle-parent toggle-collapse collapsed" data-toggle="collapse" aria-controls="complainCall" aria-expanded="false" data-child="formComplainAgainst" data-parent="parentComplainCall" data-url="{{ route('complain.call', ['id' => $call->id]) }}"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> {{ trans('complaint.complain') }}</a>
        @endif --}}
    </div>
@endif

@if($canActions['small-info'])
    <div id="userInfo" class="collapse userinfo-call">
        <hr>
        @include('user.info', ['user' => $call->user])
    </div>
@endif

@if ($canActions['clarify'])
    <div class="collapse clarify-call-form" id="clarifyCall">
        <hr>
        @include('clarify.form', ['type' => 'call', 'to' => $call])
    </div>
@endif

{{-- @if ($canActions['complain'])
    <div class="collapse complain complain-call" id="complainCall">
        <div class="parent-container parent-container-complain-call" id="parentComplainCall"></div>
    </div>
@endif --}}

<hr>
<section class="answers answers-call" id="answers">
    @php
        $count = $call->answers->count();
    @endphp
    <h2>
        <span class="title-text">{{ trans('call.requests_from_lawyers') }}</span>
        <span class="small">(<span class="count count-title">{{ trans_choice('call.count_requests', $count, ['count' => $count]) }}</span>)</span>
    </h2>
    @if (Gate::allows('answers', $call))
        <div class="answer-items">
            @include('answer.list', ['type' => 'call', 'reply' => false, 'route' => ['name' => 'call.answer', 'params' => ['call' => $call]],  'model' => $call, 'likes' => false, 'clarifies' => false])
        </div>
    @elseif(Gate::allows('pay', $call))
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">@lang('call.pay_need')</h3>
                    </div>
                    <div class="panel-body">
                        @include('pay.form', [
                            'user' => $call->user,
                            'order' => $call->payment->id,
                            'url' => [
                                'default' => route('call.view', ['call' => $call]),
                                'success' => route('call.view', ['call' => $call, 'payed' => 1]),
                                'fail' => route('call.view', ['call' => $call, 'failed' => 1]),
                            ],
                            'type' => 'call',
                            'pay' => [
                                'readonly' => true,
                                'value' => $call->payCost,
                            ],
                        ])
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-danger">
            <strong>@lang('app.no_access')</strong>
        </div>
    @endif
</section>

@if (!$answerIs)
    @include('call.answer_is_modal')   
@endif