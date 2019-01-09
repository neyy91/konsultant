{{-- Отзыв о юристе --}}
@macros(like_item($like))
    <div class="like-item">
        <div class="row">
            <div class="col-xs-2 text-center">
                <span class="glyphicon @if ($like->rating == 1) glyphicon-thumbs-up text-success icon-like @else glyphicon-thumbs-down text-danger icon-dislike @endif icon" aria-hidden="true"></span>
            </div>
            <div class="col-xs-10">
                <div class="text">
                    @if ($like->text)
                        {{ $like->text }}
                    @else
                        {{ trans('like.' . ($like->rating == 1 ? 'liked_text' : 'disliked_text')) }}
                    @endif
                </div>
                <div class="text-muted info">
                    <div class="row">
                        <div class="col-xs-9 col-sm-12">
                            <span class="user-label">@lang('app.from2')</span>
                            @php
                                $user = $like->user;
                            @endphp
                            <span class="user">{{ $user->firstname }}</span>,
                            <span class="date">{{ $user->created_at->format('j F Y') }}</span>
                        </div>
                        <div class="col-xs-9 col-sm-12">
                            <a href="{{ route('question.view', ['question' => $like->entity->to]) }}" class="link">@lang('question._to_question')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endmacros