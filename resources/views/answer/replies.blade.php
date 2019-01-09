@macros(answer_replies($replies, $model))

    @foreach($replies as $reply)
       <article class="reply reply-answer" id="reply{{ $reply->id }}">
            <div class="row">
                <div class="col-xs-12 col-sm-11">
                    <div class="text text-reply">{{ $reply->text }}</div>
                    <time pubdate datetime="{{ $reply->created_at->toIso8601String() }}" class="date date-pub">{{ $reply->created_at->format(config('site.date.long', 'd.m.Y, H:i')) }}</time>
                    @if ($reply->file)
                        | <a href="{{ route('file', ['file' => $reply->file, 'name' => $reply->file->basename]) }}" class="file-link file-type-"><span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> {{ trans('answer.uploaded_file') }}</a>
                    @endif
                </div>
                <div class="col-xs-4 col-sm-1">
                    @php
                        $sizes = config('site.user.photo.sizes');
                        $user = $reply->from;
                    @endphp
                    @if ($user->photo)
                        <img src="{{ $user->photo->url }}" alt="{{ $user->fullname }}" width="{{ $sizes['xsmall'][0] }}">
                    @else
                        @php
                            $src = default_user_photo($user);
                        @endphp
                        <img src="{{ $src }}" alt="{{ $user->fullname }}" width="{{ $sizes['xsmall'][0] }}">
                    @endif
                </div>
            </div>
            
       </article>
    @endforeach

@endmacros