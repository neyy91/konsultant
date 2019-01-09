@include('user.photo')

@forelse ($lawyers as $lawyer)
    @php
        $routeLawyer = route('lawyer', ['lawyer' => $lawyer]);
        $user = $lawyer->user;
    @endphp
    <div class="panel panel-default lawyer-item">
        <div class="panel-body lawyer-body">
            <div class="row lawyer-row">
                <div class="col-xs-12 col-sm-2">
                    <a href="{{ $routeLawyer }}" class="lawyer-photo-link">@macros(user_photo, ['user' => $user, 'size' => 'large', 'attributes' => ['class' => 'img-responsive lawyer-image']])</a>
                </div>
                <div class="col-xs-12 col-sm-5 lawyer-col-left">
                    <div class="clearfix lawyer-head">
                        @if ($user->isOnline())<div class="pull-right text-success user-online"><span class="glyphicon glyphicon-globe" aria-hidden="true"></span> @lang('user.online')</div>@endif
                        <h3 class="pull-left lawyer-name"><a href="{{ $routeLawyer }}" class="lawyer-name-link">{{ $user->display_name }}</a></h3>
                    </div>
                    <div class="lawyer-info">
                        <span class="lawyer-status">
                            @if ($lawyer->is_company)
                                @lang('company.about'),
                            @else
                                {{ $lawyer->statusLabel }},
                            @endif
                        </span>
                        @if ($user->city)
                            @if (isset($city) && $city->id = $user->city->id)
                                <span class="lawyer-city">{{ $user->city->name }}</span>
                            @else
                                <a href="{{ route('lawyers.city', ['city' => $user->city]) }}" class="lawyer-city">{{ $user->city->name }}</a>
                            @endif
                        @endif
                        <a href="{{ route('user.chat', ['user' => $user]) }}" class="lawyer-chat script-action chat-action chat-start ajax" data-user="{{ $lawyer->id }}" data-on="click" data-ajax-url="{{ route('user.chat', ['user' => $user]) }}" data-ajax-method="POST" data-ajax-context="this" data-ajax-data-type="json" data-ajax-error="App.messageOnError" data-ajax-success="App.Chat.startSuccess"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> @lang('user._private_message')</a>
                    </div>
                    <div class="lawyer-about">
                        {{ $lawyer->aboutmyself }}
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            @php
                                $count = $lawyer->specializations ? $lawyer->specializations->count() : 0;
                            @endphp
                            <a href="{{ $routeLawyer }}#specializations">{{ trans_choice('user.specializations_count', $count, ['count' => $count]) }}</a>
                        </div>
                        @if ($lawyer->experience)
                            <div class="col-xs-4">
                                <span class="lawyer-experience">{{ $lawyer->experienceLabel }}</span>
                            </div>
                        @endif
                        <div class="col-xs-4">
                            @if ($lawyer->expert)
                                <span class="text-success lawyer-expert">
                                    <span class="glyphicon glyphicon-education" aria-hidden="true"></span>
                                    <span class="lawyer-expert-label">@lang('lawyer.expert')</span>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4 lawyer-col-right">
                    {{-- TODO: рейтинг --}}
                    <div class="text-success rating-text">@if ($lawyer->rating) {{ $lawyer->rating }} @else @lang('user.no_data_rating') @endif</div>
                    <div class="lawyer-chat">
                        <a href="#" role="button" class="btn btn-success btn-lg lawyer-chat-link ajax" data-on="click" data-ajax-url="{{ route('user.chat', ['user' => $user]) }}" data-ajax-method="POST" data-ajax-context="this" data-ajax-data-type="json" data-ajax-error="App.messageOnError" data-ajax-success="App.Chat.startSuccess">@lang('lawyer.contact_lawyer')</a>
                    </div>
                    <div class="lawyer-liked">
                        @php
                            $count = $lawyer->liked ? $lawyer->liked->count() : 0;
                        @endphp
                        <a href="{{ route('lawyer.liked', ['lawyer' => $lawyer]) }}" class="lawyer-liked">{{ trans_choice('user.liked_count', $count, ['count' => $count]) }}</a> @lang('user._from_clients')
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="empty">@lang(isset($empty) ? $empty : 'lawyer.not_found')</div>
@endforelse