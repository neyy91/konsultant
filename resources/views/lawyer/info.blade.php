{{-- Информация о юристе --}}
@php
    $education = $lawyer->education;
@endphp
<div class="panel panel-default profile-lawyer-panel">
    <div class="panel-body">
    <div class="row">
        <div class="col-xs-5 col-sm-2">
            <img src="{{ $user->photo->url }}" alt="{{ $user->fullname }}" class="image img-responsive">
        </div>
        <div class="col-xs-7 col-sm-7">
            <h1 class="fullname">@if (isset($link) && $link) <a href="{{ $link }}">{{ $user->fullname }}</a> @else {{ $user->fullname }} @endif <span class="small">ID: {{ $user->lawyer->id }}</span></h1>
            @if ($lawyer->status)
                <span class="lawyer-status lawyer-status-id-{{ $lawyer->status }}">{{ $lawyer->statusLabel }}</span>
            @endif
            <a href="{{ route('lawyers.city', ['city' => $user->city]) }}" class="city city-lawyer">{{ $user->city->name }}</a>
            <div class="row">
                <div class="col-xs-2">
                    <div class="liked-procent">
                        @if ($liked['count']['all'] > 0)
                            {{ round($liked['count']['like'] * 100/$liked['count']['all']) }} %
                        @else
                            <span class="text-muted">@lang('app.no_data')</span>
                        @endif
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="liked-text">@lang('lawyer._satisfied_customers')</div>
                    <a href="{{ route('lawyer.liked', ['lawyer' => $lawyer]) }}" class="liked-count">{{ trans_choice('user.liked_count', $liked['count']['all'], ['count' => $liked['count']['all']]) }}</a>
                </div>
                @if ($education)
                    <div class="col-xs-4">
                        <div class="education-status">
                            @if ($education->checked)
                                <span class="text-success"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span> @lang('user.education_checked')</span>
                            @else
                                <span class="text-warning"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> @lang('user.education_no_checked')</span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-xs-12 col-sm-3">
            <div class="text-center">
                <div class="text-success rating-text">@if ($lawyer->rating) {{ $lawyer->rating }} @else @lang('user.no_data_rating') @endif</div>
                @if (Gate::allows('chat', $lawyer->user))
                    <a href="{{ route('user.chat', ['user' => $lawyer->user]) }}" class="btn btn-success ajax" data-on="click" data-ajax-url="{{ route('user.chat', ['user' => $user]) }}" data-ajax-method="POST" data-ajax-context="this" data-ajax-data-type="json" data-ajax-error="App.messageOnError" data-ajax-success="App.Chat.startSuccess">@lang('user.chatting')</a>
                @endif
            </div>
        </div>
    </div>
    </div>
</div>