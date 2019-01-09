{{-- Список ответов. --}}
@php
    $reply = isset($reply) ? $reply : true;
    $cost = isset($cost) ? $cost : false;
    $currenUser = Auth::user();
    $likes = isset($likes) ? $likes : true;
    $clarifies = isset($clarifies) ? $clarifies : false;
    $chat = isset($chat) ? $chat : true;
@endphp

@unlessmacros('answer_cost')
    @include('answer.cost')
@endif
@unlessmacros('clarify_list')
    @include('clarify.macros')
@endif
@unlessmacros('like_thumbs')
    @include('like.thumbs')
@endif
@unlessmacros('answer_replies')
    @include('answer.replies')
@endif


@forelse ($model->answers as $answer)
    <article class="answer answer-for-{{ $type }} @if ($answer->is)answer-is @endif" id="answer{{ $answer->id }}">
        @php
            $can = [
                'answer-is' => Gate::allows('answer-is', $answer),
                'clarify-answer' => Gate::allows('clarify-answer', [App\Models\Clarify::class, $answer]) && $clarifies,
                'reply' => Gate::allows('reply', [App\Models\Answer::class, $answer]) && $reply,
                'complain' => Gate::allows('complain', [App\Models\Complaint::class, $answer]),
                'content-access' => Gate::allows('answer-content', $answer),
                'clarifies' => Gate::allows('clarifies', $answer) && $clarifies,
            ];
        @endphp

        @if ($answer->from)
            @php
                $lawyer = $answer->from;
                $user = $lawyer->user;
                $answerSelf = $user->id === $currenUser->id;
            @endphp
            <div class="row">
                <div class="col-xs-4 col-sm-1">
                    @php
                        $sizes = config('site.user.photo.sizes');
                        $src = $user->photo ? $user->photo->url : default_user_photo($user);
                    @endphp
                    <img src="{{ $src }}" alt="{{ $user->display_name }}" width="{{ $sizes['small'][0] }}">
                </div>
                <div class="col-xs-8 col-sm-9">
                    <h3 class="lawyer lawyer-answer">
                        @if ($answer->is && !$can['answer-is'])
                            <span class="text-success glyphicon glyphicon-ok-sign answer-is-label toggle-tooltip" aria-hidden="true" title="@lang('answer.is_answer.' . $type)"></span>
                        @endif
                        <a href="{{ route('lawyer', ['lawyer' => $lawyer]) }}" class="lawyer-answer-link">{{ $answerSelf ? trans('app.your') : $user->display_name }}</a> <a href="{{ route($route['name'], $route['params'] + ['answer' => $answer]) }}" class="small answer-anchor toggle-tooltip" data-container="body" title="{{ trans('answer.link_to.' . $type) }}"><span class="glyphicon glyphicon-link" aria-hidden="true"></span></a>
                        @if (Gate::allows('chat', $user) && $chat)
                            <a href="{{ route('user.chat', ['user' => $user]) }}" class="small chat answer-chat chat-action chat-start ajax toggle-tooltip" data-on="click" data-ajax-url="{{ route('user.chat', ['user' => $user]) }}" data-ajax-method="POST" data-ajax-context="this" data-ajax-data-type="json" data-ajax-error="App.messageOnError" data-ajax-success="App.Chat.startSuccess" title="@lang('chat.send_message')"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span></a>
                        @endif
                    </h3>
                    <span class="small">{{ ($lawyer->status ? $lawyer->statusLabel : '') }}@if ($user->city){{ ($lawyer->status ? ', ' : '') . $user->city->name }} @endif</span>
                </div>
                <div class="col-xs-6 col-sm-1 text-center">
                    @php
                        $count = [
                            'answers' => $answer->from->qanswers->count(),
                            'likes' => $answer->from->liked->count(),
                        ];
                    @endphp
                    <a href="{{ route('lawyer.questions', ['lawyer' => $lawyer]) }}" class="h3 text-success"><div class="count">{{ $count['answers'] }}</div><div class="small">{{ trans_choice('answer.count_answers_suffix', $count['answers']) }}</div></a>
                </div>
                <div class="col-xs-6 col-sm-1 text-center">
                    <a href="{{ route('lawyer.liked', ['lawyer' => $answer->from]) }}" class="h3 text-success"><div class="count">{{ $count['likes'] }}</div><div class="small">{{ trans_choice('like.count_likes_suffix', $count['answers']) }}</div></a>
                </div>
            </div>
        @else
            <h3 class="text-muted">@lang('user.user_not_found_or_delete')</h3>
        @endif

        @if ($can['content-access'])
            <div class="text text-answer">{{ $answer->text }}</div>
        @endif

        @if ($can['clarifies'])
            <div id="clarifies{{ $answer->id }}" class="collapse answer-clarifies clarifies-answer-{{ $type }}">
                <div class="row">
                    <div class="col-xs-10 col-xs-offset-1">
                        <div class="clarify-items clarify-answer-items" id="clarifiesAnswer{{ $answer->id }}">
                            @macros(clarify_list, ['clarifies' => $answer->clarifies, 'type' => 'answer'])
                        </div>
                    </div>
                </div>
            </div>
        @endif
            
        @if ($can['clarify-answer'])
            <div class="collapse collapse-clarify-answer" id="clarifyAnswer{{ $answer->id }}">
                <div class="parent-container parent-container-clarify" id="parentClarifyAnswer{{ $answer->id }}"></div>
            </div>
        @endif

        <div class="clearfix item-info item-info-answer">
            @if ($likes && $answer->likes)
                <div class="pull-right info-likes">
                    @macros(like_thumbs,['type' => 'answer', 'model' => $answer])
                </div>
            @endif

            @if ($can['clarifies'])
                <a href="#clarifies{{ $answer->id }}" class="script-action state action-clarifies action-clarifies-answer toggle-collapse collapsed exists-clarifies-answer{{ $answer->id }}" data-toggle="collapse" aria-controls="clarifies{{ $answer->id }}" aria-expanded="false" style="display: @if ($answer->clarifies->count() > 0) inline @else none @endif;">@lang('clarify.title')</a>
            @endif

            <time pubdate datetime="{{ $answer->created_at->toIso8601String() }}" class="date date-pub">{{ $answer->created_at->format(config('site.date.long', 'j F Y, H:i')) }}</time>
            @if ($answer->update_at)
                <time datetime="{{ $answer->update_at->toIso8601String() }}" class="date date-updated">{{ trans('answer._edited_date', ['date' => $answer->update_at->format(config('site.date.long', 'j F Y, H:i'))]) }} </time>
            @endif
            @if ($answer->file && Gate::allows('access', $answer->file) && $can['content-access'])
                <a href="{{ route('file', ['file' => $answer->file, 'name' => $answer->file->basename]) }}" class="file-link" target="_blank" rel="nofollow"><span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> {{ trans('answer.uploaded_file') }}</a>
            @endif
            
        </div>
        
        <div class="clearfix actions actions-answer">
            @if ($can['clarify-answer'])
                <a href="#clarifyAnswer{{ $answer->id }}" class="script-action state action-clarify-answer toggle-collapse toggle-clarify-answers toggle-parent collapsed" data-toggle="collapse" aria-expanded="false" aria-controls="clarifyAnswer{{ $answer->id }}" data-url="{{ route('clarify.create.answer', ['id' => $answer->id]) }}"  data-child="formClarifyAnswer" data-parent="parentClarifyAnswer{{ $answer->id }}" rel="nofollow"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> {{ trans('answer.add_clarify') }}</a>
            @endif
            @if ($can['answer-is'])
                {{-- <a href="#" title="{{ trans($answer->is ? 'answer.is_no_help.' . $type : 'answer.is_help.' . $type) }}" class="btn btn-sm @if ($answer->is)btn-success action-is @else btn-info @endif action toggle-tooltip ajax" data-on="click" data-ajax-url="{{ route("answer.is.{$type}", ['answer' => $answer]) }}" data-ajax-data='{"is" : {{ $answer->is ? 0 : 1 }} }' data-ajax-method="PUT" data-ajax-data-type="json" data-ajax-context="this" data-ajax-before-send="App.beforeSetIsFor{{ ucfirst($type) }}" data-ajax-success="App.setIsFor{{ ucfirst($type) }}" data-ajax-error="App.messageOnError" rel="nofollow"><span class="icon glyphicon @if ($answer->is) glyphicon-ok @else glyphicon-screenshot
                @endif" aria-hidden="true"></span> <span class="text">{{ trans('app.' . ($answer->is ? 'selected' : 'select')) }}</span></a> --}}
                <a href="#answerSetIs" data-toggle="modal" title="@lang('answer.is_help.' . $type)" class="btn btn-sm btn-primary action toggle-tooltip answer-is-{{ $type }}" data-set-url="{{ route("answer.is.{$type}", ['answer' => $answer]) }}" rel="nofollow"><span class="icon glyphicon glyphicon-screenshot" aria-hidden="true"></span> <span class="text">@lang('app.select')</span></a>
            @endif
            @if ($cost)
                @macros(answer_cost, $answer)
            @endif
            <a href="#replyAnswer{{ $answer->id }}" class="script-action state action-reply toggle-collapse toggle-reply-answers toggle-parent collapsed" data-toggle="collapse" aria-expanded="false" aria-controls="replyAnswer{{ $answer->id }}" data-child="replyPanel" data-parent="parentReplyAnswer{{ $answer->id }}" data-url="{{ route('answer.create.answer', ['id' => $answer->id]) }}" style="display: @if ($can['reply'] && $reply) inline @else none @endif"  rel="nofollow"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span> {{ trans('answer.reply') }}</a>
            @if ($can['complain'])
                <a href="#complainAnswer{{ $answer->id }}" class="script-action state text-warning action-complain action-complain-answer toggle-parent toggle-collapse collapsed" data-toggle="collapse" aria-controls="complainAnswer{{ $answer->id }}" aria-expanded="false" data-child="formComplainAgainst" data-parent="parentComplainAnswer{{ $answer->id }}" data-url="{{ route('complain.answer', ['id' => $answer->id]) }}" rel="nofollow"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> {{ trans('complaint.complain') }}</a>
            @endif
            @can('admin', App\Models\UserPolicy::class)
                <a href="{{ route('answer.delete.form.admin', ['id' => $answer->id, 'iframe' => 'y']) }}" class="script-action text-danger action-delete link link-admin" target="iframeAdmin" data-target="#modalAdmin"><span class="glyphicon glyphicon-trash" aria-hidden="true" rel="nofollow"></span> @lang('form.action.delete')</a>
            @endcan
            @if ($currenUser->id !== $user->id)
                <a href="{{ route('lawyer', ['lawyer' => $lawyer, 'thanking' => 1]) }}" class="btn btn-default btn-sm action-thanking" target="_blank" rel="nofollow"><span class="small glyphicon glyphicon-rub" aria-hidden="true"></span> @lang('user.thanking')</a>
            @endif
        </div>
        @if ($can['complain'])
            <div class="collapse collapse-complain-answer" id="complainAnswer{{ $answer->id }}">
                <div class="parent-container parent-container-complain" id="parentComplainAnswer{{ $answer->id }}"></div>
            </div>
        @endif
        @if ($can['reply'])
            <div class="collapse collapse-reply-answer" id="replyAnswer{{ $answer->id }}">
                <div class="parent-container parent-container-reply" id="parentReplyAnswer{{ $answer->id }}"></div>
            </div>
        @endif
        @can('replies', $answer)
            <div class="row row-replies">
                <div class="col-xs-10 col-xs-offset-2 col-replies answer-replies">
                    @macros(answer_replies, $answer->answers, $model)
                </div>
            </div>
        @endcan
    </article>
    @if (!$loop->last)
        <hr>
    @endif
@empty
    <div class="empty">{{ trans('answer.not_found.' . $type) }}</div>
    <hr>
@endforelse
